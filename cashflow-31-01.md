# Cashflow — point d'étape (31/01/2026)

## Objectif
Parcours client souhaité :
1. Inscription
2. Sélection des articles
3. Saisie des informations personnelles / adresse
4. Paiement
5. Envoi d'un mail récapitulatif à l'admin (et confirmation au client)

---

## État actuel (fait / partiel / à faire) 🔎

### 1) Inscription — FAIT ✅
- Routes : `routes/auth.php` (GET/POST `/register`).
- Contrôleur : `App\Http\Controllers\Auth\RegisteredUserController` (validation + login automatique).
- Vue : `resources/views/auth/register.blade.php`.
- Tests : `tests/Feature/Auth/RegistrationTest.php` (page accessible + enregistrement fonctionnel).

### 2) Sélection des articles — FAIT ✅
- Listing : `kiosque` route et `VinyleController::kiosque`.
- Ajout au panier : `CartController@add` et `CartService::addVinyle` (calcul du prix, gestion des fonds, vérification de stock).
- Panier : `resources/views/cart/index.blade.php` affiche les items, récapitulatif, gestion quantité/suppression.
- Tests/scripts : scripts de debugging (`scripts/merge_cart_test.php`, `merge_flow_test.ps1`) et logique de merge d'items existante.

### 3) Informations personnelles — PARTIEL ⚠️
- Modèle : `Order` supporte les champs client (`nom`, `prenom`, `email`, `telephone`, `adresse`, etc.).
- Route : `GET /orders/create` existe et appelle `OrderController::create`.
- Vue : **manquante** (`resources/views/orders/create.blade.php` n'existe pas) — il n'y a pas de formulaire pour saisir les informations ni choisir mode de livraison / paiement.

### 4) Paiement — À FAIRE ❌
- Aucune intégration de paiement (Stripe/PayPal ou autre) détectée.
- `VenteController` gère des ventes côté back-office / kiosque (création d'une vente hors panier), mais ne couvre pas le flow client avec paiement en ligne.
- La page panier affiche un bouton « Valider ma commande (bientôt) » désactivé.

### 5) Mail récapitulatif à l'admin — À FAIRE ❌
- Aucun Mailable ou notification spécifique de commande détecté.
- Existe : `StockCritiqueQuotidien` pour rapports de stock, mais pas d'email de commande ni de récap pour l'admin/client.

---

## Risques / remarques importantes ⚠️
- Le bouton de validation est désactivé : le parcours n'est pas consommable en prod pour un client.
- Le seeder de production crée un admin/kiosque avec mots de passe `CHANGE_ME_*` — risque de sécurité si non changé.
- Absence de tests pour le checkout / commande / envoi d'email : ajouter des tests Feature indispensables.
- Vérifier la configuration email (SMTP) en production avant d'activer l'envoi d'emails de commande.

---

## Proposition de plan d'action (priorisé) 🚀
1. **Créer la vue de checkout** (`resources/views/orders/create.blade.php`) avec formulaire : nom, prénom, email, téléphone, adresse, code postal, ville, notes client, mode de paiement (options initiales: `payment_on_delivery`, `paiement_sur_place`, `carte_sur_place`).
2. **Implémenter l'enregistrement de commande** (`OrderController@store`) :
   - Valider le panier & le stock (via `CartService::checkStock()`),
   - Créer `Order` + `OrderItem` en snapshot (titre/artiste/ref), générer `numero_commande` via `Order::generateNumero()`,
   - Décrémenter les stocks correspondants et vider le panier,
   - Gérer utilisateur connecté (associer `user_id` si présent),
   - Marquer `statut` initial `en_attente`.
3. **Ajouter Mailables** :
   - `OrderConfirmationToCustomer` (envoi au client),
   - `OrderRecapToAdmin` (envoi à une adresse admin configurable via `.env`, ex. `ADMIN_EMAIL`).
4. **Activer le bouton « Valider ma commande »** dans `cart.index` pour rediriger vers `orders.create` lorsque `stockErrors` est vide.
5. **Tests** : écrire des tests Feature pour le parcours complet (guest + user) : création de commande, envoi d'emails (Notification::fake / Mail::fake), décrémentation stock, redirection / page de success.
6. **Paiement en ligne (optionnel 2e étape)** : intégrer Stripe/checkout si besoin — commencer par un mode de paiement basique (paiement à la livraison) puis ajouter le paiement par carte (Stripe) si nécessaire.
7. **Sécurité / hardening** : supprimer/mettre sûr le `ProductionUserSeeder` ou forcer changement de mots de passe, et ajouter un workflow CI pour les tests.

---

## Petite estimation rapide (implémentation minimale)
- Formulaire de checkout + stockage de la commande + envoi d'emails (sans paiement en ligne) : **~1–2 jours**.
- Ajout de tests et CI : **~0.5–1 jour**.
- Intégration paiement (Stripe) + tests supplémentaires : **~1–2 jours**.

---

## Proposition immédiate : choix prioritaire
- Priorité 1 : permettre au client de finaliser une commande (étapes 3 & 5 ci-dessus) avec paiement différé (paiement à la livraison ou sur place). Cela permet d'avoir un flux fonctionnel rapidement.
- Priorité 2 : ajouter la confirmation mail à l'admin + tests.
- Priorité 3 : implémenter paiement en ligne si nécessaire.

---

Si tu veux, je peux commencer par :
- 1) créer la vue `orders.create` + route POST `orders.store` et le contrôleur d'enregistrement minimal (mode ")paiement différé"",
- 2) ajouter les Mailables et les tests Feature correspondants.

Dis-moi quelle action tu veux prioriser et je lance les modifications.
