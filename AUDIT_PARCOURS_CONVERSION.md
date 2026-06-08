# Audit Parcours Client / Conversion — Vinyles Stock

Date : 2026-06-08  
Projet : /home/aur-lien/projets/vinyles-stock  
Auteur : Hermes Agent

---

## 1. Résumé exécutif

Le parcours panier → commande → paiement Stripe → confirmation est fonctionnel dans le happy path, mais présente plusieurs failles de robustesse, des incohérences de données (enum, prix), et un bug critique de réservation de stock lors du merge de panier anonyme. Le système de réservation de stock (reserved_quantity) est globalement bien pensé, mais mal géré dans le merge et l'annulation.

---

## 2. Détail par étape

### 2.1 Panier (CartController) — Routes publiques

- **Routes** : `GET /cart`, `POST /cart/add`, `PATCH /cart/{item}`, `DELETE /cart/{item}`, `POST /cart/clear`, `GET /cart/count`
- **Stockage** : DB `carts` + `cart_items` (session pour anonyme, user_id pour authentifié)
- **Expiration** : `expires_at = now()->addHours(2)`; cron `carts:cleanup` toutes les minutes via `App\Console\Kernel`
- **Réservation** : `CartService::addVinyle()` incrémente `vinyles.reserved_quantity` et `fonds.reserved_quantity` en transaction. C'est correct.

### 2.2 Checkout (OrderController::create / store / payment)

- **Route auth** : `/orders/create` (formulaire livraison), `POST /orders` (validation), `GET /orders/payment` (récap + création commande)
- **Flow** :
  1. `create()` : vérifie panier non vide, affiche adresses et formulaire
  2. `store()` : valide données, vérifie stock via `StockService::verifierDisponibilite()`, stocke shipping/billing en session, redirige vers `orders.payment`
  3. `payment()` : vérifie panier + session, réutilise `pending_order_id` si existe et statut `en_attente`, sinon crée commande `createOrderFromSession()` avec retry sur doublon de numéro, stocke `pending_order_id` en session
- **Commande créée avant paiement** : statut `en_attente`, total figé. Si l'utilisateur abandonne ici, la commande reste orpheline en base.

### 2.3 Paiement Stripe (PaymentController)

- **Routes** : `POST /payment/checkout` (auth), `GET /payment/success`, `GET /payment/cancel`, `POST /stripe/webhook` (public)
- **Checkout** : crée `Stripe\Checkout\Session` avec `payment_method_types: ['card']` (limité, pas d'Apple Pay/Google Pay explicite). Crée un record `Payment` en `pending`.
- **Vérification propriétaire** : `if ($order->user_id !== auth()->id()) abort(403)` — correct.
- **Success (return URL)** : récupère session Stripe, vérifie `payment_status === 'paid'`, met à jour `Payment` + `Order`, vide le panier.
- **Webhook** : gère `checkout.session.completed` (même logique que success + envoi emails + réservation stock), `payment_intent.succeeded` (log seul), `payment_intent.payment_failed` (log seul).

### 2.4 Confirmation / Échec

- **Pages existantes** :
  - `payment/success` — utilisée (affiche numéro commande + liens "Mes commandes" / "Continuer")
  - `payment/cancel` — utilisée (message clair, lien retour panier)
  - `orders/success` — page legacy non utilisée dans le flow actuel
  - `orders/cancel` — page legacy non utilisée dans le flow actuel
- **Emails transactionnels** :
  - `OrderConfirmation` → client (depuis webhook)
  - `AdminOrderNotification` → admin (depuis webhook)
  - `OrderStatusUpdated` → client (depuis `OrderAdminController::updateStatus`)

### 2.5 Post-commande

- `GET /mes-commandes` — historique client paginé
- Admin : gestion des statuts (`en_attente`, `en_preparation`, `prete`, `livree`, `annulee`, `completed`)

---

## 3. Risques identifiés

### CRITIQUE

| # | Risque | Emplacement | Impact |
|---|--------|-------------|--------|
| C1 | **Merge panier anonyme : fuite de réservation de stock** | `CartService::mergeAnonymousCart()` ligne 429 | Le panier anonyme est supprimé (`items()->delete()`) mais `releaseStockForCart($anonCart)` n'est jamais appelé. Le stock reste réservé à jamais. Les items du user cart sont créés sans nouvelle réservation. Conséquence : stock virtuellement indisponible + risque d'oversell réel car `reserved_quantity` devient décorrélée des paniers. |
| C2 | **Enum DB vs valeurs insérées incohérentes** | `PaymentController::handleCheckoutCompleted()` ligne 197-198 | Le contrôleur fait `$order->update(['status' => 'paid', 'statut' => 'completed'])`. Or la migration définit l'enum `statut` avec : `en_attente, payee, en_preparation, prete, livree, annulee`. `'completed'` et `'paid'` ne sont PAS dans l'enum. MySQL lèvera une erreur 01000 ou bloquera l'update selon le mode strict. |
| C3 | **Prix affichés à 1/100 dans les emails** | `emails/order-confirmation.blade.php`, `emails/admin-order-notification.blade.php` | `number_format($order->total / 100, ...)` alors que `$order->total` est déjà en euros (cast decimal:2). Une commande de 25 € est affichée **0,25 €**. |

### MAJEUR

| # | Risque | Emplacement | Impact |
|---|--------|-------------|--------|
| M1 | **Commandes orphelines "en_attente" sans nettoyage** | `OrderController::payment()` | Une commande est créée à l'étape récapitulatif, avant le clic sur "Payer". Si l'utilisateur quitte, la commande reste `en_attente` indéfiniment. Pas de cron d'annulation auto des commandes non-payées après N heures. |
| M2 | **Payment multiples pending par commande** | `PaymentController::checkout()` | Chaque appel à `checkout()` crée une nouvelle `Stripe Session` et un nouveau record `Payment` en `pending`. Le success ne nettoie pas les autres sessions pending. Risque de confusion en base et de webhook tardif sur une vieille session. |
| M3 | **Webhook `payment_intent.payment_failed` inactif** | `PaymentController::handlePaymentFailed()` | Seulement un `Log::error`. La commande reste `en_attente`, le Payment reste `pending`. L'utilisateur n'est pas notifié, le stock n'est pas libéré. |
| M4 | **OrderObserver::updatingCanceled jamais appelé** | `app/Observers/OrderObserver.php` | Le nom de méthode `updatingCanceled` n'est pas un event Laravel standard. L'observer ne restitue donc **jamais** le stock lors d'une annulation admin. `OrderAdminController::cancel()` ne restitue pas non plus le stock via `StockService::restituerStock()`. |
| M5 | **`checkStock()` vérifie le stock physique au lieu du stock disponible** | `CartService::checkStock()` lignes 318, 322 | Compare `$vinyle->quantite < $item->quantite` au lieu de `($vinyle->quantite - $vinyle->reserved_quantity) < $item->quantite`. Affiche des faux positifs si le stock est réservé par d'autres paniers. |

### MINEUR

| # | Risque | Emplacement | Impact |
|---|--------|-------------|--------|
| m1 | **Nom/prénom identiques en base** | `OrderController::createOrderFromSession()` lignes 223, 230 | `prenom` et `nom` prennent tous deux `$shipping['nom']`. Le nom complet devient "Dupont Dupont". |
| m2 | **`success_url` / `cancel_url` en dur dans Stripe config** | `PaymentController::checkout()` | Utilise `$request->getSchemeAndHttpHost()` + chemins hardcodés. Pas configurable via `config/services.php`. |
| m3 | **`payment_method_types` limité à `['card']`** | `PaymentController::checkout()` | Pas d'activation explicite d'Apple Pay, Google Pay, iDEAL, etc. selon marché. |
| m4 | **Email `OrderStatusUpdated` utilise `$order->status` (anglais) pour le switch** | `emails/order-status-updated.blade.php` | Le `@switch($order->status)` teste `pending`, `processing`, `shipped`… mais la base utilise `en_attente`, `en_preparation`, etc. Le contenu contextuel de l'email ne s'affichera jamais. |
| m5 | **Pas de route protégée sur `orders.payment` au niveau propriétaire** | `OrderController::payment()` | Se base sur `Session::get('pending_order_id')` sans vérifier que l'`order->user_id == Auth::id()`. Bien que la session soit scopée navigateur, c'est une dépendance implicite. |
| m6 | **Deux champs de statut redondants (`statut` + `status`)** | `orders` table | Migration ajoute `status` string en parallèle de `statut` enum. Certains contrôleurs écrivent dans les deux, d'autres dans un seul. Source de vérité ambiguë. |

---

## 4. Recommandations

### Immédiat (avant mise en prod)

1. **Corriger le merge de panier** (`CartService::mergeAnonymousCart`) :
   - Appeler `releaseStockForCart($anonCart)` avant `$anonCart->items()->delete()`
   - Réserver le stock pour les items mergés dans le user cart (incrémenter `reserved_quantity` des vinyles/fonds correspondants)

2. **Corriger l'enum / les valeurs de statut** :
   - Soit ajouter `'completed'` et `'paid'` à l'enum DB (migration),
   - Soit harmoniser le code pour n'utiliser que les valeurs existantes (`payee`, `en_attente`, etc.) et supprimer le champ `status` redondant.

3. **Corriger les emails de prix** :
   - Retirer le `/ 100` dans `order-confirmation.blade.php` et `admin-order-notification.blade.php`.

4. **Corriger la restitution de stock à l'annulation** :
   - Renommer `OrderObserver::updatingCanceled` en `updated` avec la condition `if ($order->isDirty('statut') && $order->statut === 'annulee')`
   - OU appeler explicitement `StockService::restituerStock()` dans `OrderAdminController::cancel()`.

### Court terme

5. **Nettoyer les commandes "en_attente" orphelines** :
   - Créer une commande artisan `orders:cleanup-pending` qui annule (et restitue le stock) les commandes `en_attente` créées il y a plus de X heures sans paiement associé en `success`.
   - L'ajouter au scheduler (ex: toutes les heures).

6. **Éviter les Payment pending multiples** :
   - Dans `PaymentController::checkout()`, vérifier s'il existe déjà une `Payment` en `pending` pour cette commande. Si oui, soit réutiliser le `stripe_session_id` existant (si la session Stripe n'est pas expirée), soit annuler l'ancienne et en créer une nouvelle.

7. **Gérer `payment_intent.payment_failed`** :
   - Mettre à jour le `Payment` correspondant en `failed`, mettre à jour l'`Order` en `annulee` (ou laisser `en_attente` avec flag de retry), et notifier l'utilisateur.

8. **Unifier la logique de vérification de stock** :
   - Faire que `CartService::checkStock()`, `StockService::verifierDisponibilite()`, et `CartService::addVinyle` utilisent tous la même formule : `quantite - reserved_quantity`.

### Moyen terme

9. **Supprimer le champ `status` redondant** ou le synchroniser automatiquement via observer.
10. **Corriger l'email `order-status-updated`** pour utiliser `$order->statut` (français) au lieu de `$order->status`.
11. **Ajouter une vérification explicite de propriété** dans `OrderController::payment()` : `$order->user_id === Auth::id()`.
12. **Ajouter d'autres moyens de paiement Stripe** selon le marché cible (configurable).

---

## 5. Annexes — Fichiers audités

- `app/Http/Controllers/CartController.php`
- `app/Http/Controllers/OrderController.php`
- `app/Http/Controllers/PaymentController.php`
- `app/Http/Controllers/Admin/OrderAdminController.php`
- `app/Services/CartService.php`
- `app/Services/StockService.php`
- `app/Models/Order.php`
- `app/Models/Payment.php`
- `app/Observers/OrderObserver.php`
- `app/Console/Commands/CleanupExpiredCarts.php`
- `app/Console/Kernel.php`
- `app/Http/Middleware/MergeCartOnLogin.php`
- `routes/web.php`
- `resources/views/orders/payment.blade.php`
- `resources/views/payment/success.blade.php`
- `resources/views/payment/cancel.blade.php`
- `resources/views/emails/order-confirmation.blade.php`
- `resources/views/emails/admin-order-notification.blade.php`
- `resources/views/emails/order-status-updated.blade.php`
- `database/migrations/2025_12_31_141604_create_orders_table.php`
- `database/migrations/2026_03_05_142200_update_orders_table_for_checkout.php`
