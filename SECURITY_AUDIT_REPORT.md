# Rapport d'Audit Securite — Vinyles Stock

Date : 2025-06-08
Scope : Laravel 11+, SQLite, Spatie MediaLibrary, Stripe
Auditeur : Hermes Agent

---

## Resume Executive

| Vecteur | Statut | Severite |
|---------|--------|----------|
| Authentification / Authorization | Partiel | Majeur |
| Policies / Gates | Manquant | Majeur |
| Injection SQL | Correct | — |
| XSS (Blade) | Acceptable | Mineur |
| Uploads / Medias | Correct | — |
| Configuration .env | A corriger | Critique |
| Mots de passe / Reset | Correct | — |
| CSRF | Correct | — |
| Headers securite | A renforcer | Majeur |

**Score global : 6.5 / 10** (Critique 1, Majeur 3, Mineur 1)

---

## 1. Routes et Middleware

### 1.1 Routes sans auth (intentionnellement publiques)
- `GET /` (landing), `/about`, `/contact`, `/cgv`, `/mentions-legales`, `/confidentialite`
- `GET /kiosque/*` (catalogue public)
- `POST /contact` (formulaire contact avec rate-limit + honeypot)
- `POST /stripe/webhook` (webhook externe Stripe — legit)
- `POST /cookies/accept`

### 1.2 Routes admin protegees
- Toutes les routes `/admin/*` passent par `['auth', 'role:admin,employe']` ou `['auth', 'role:admin']`.
- Middleware `CheckRole` verifie `Auth::check()` puis `in_array($user->role, $allowedRoles)`.

### 1.3 Points de vigilance
- **Route debug** `/_debug/merge-cart-test` : protegee par `app()->environment('local')` → OK, mais a retirer avant production.
- **Middleware `role`** applique une redirection `redirect()->route('kiosque.index')` pour les utilisateurs non autorises. Pas de reponse 403 systematique pour les requetes non-JSON (redirection au lieu de 403 sur requetes standards).

---

## 2. Policies / Gates

### 2.1 Etat actuel
- `AuthServiceProvider` : `$policies = []` — **AUCUNE policy enregistree**.
- Aucune Gate definie dans le projet.
- Aucun appel `$this->authorize()` dans les controllers.

### 2.2 Autorisations manuelles
- `ConversationController` : checks manuels `$conversation->client_id !== Auth::id()` → `abort(403)` — correct mais fragile.
- `OrderController@myOrders` : `Order::where('user_id', Auth::id())` — correct.

### 2.3 Risque
- L'absence de policies rend l'autorisation dependante des checks manuels dans chaque controller. Omission probable sur `OrderController` pour les methodes `show`/`edit` (pas trouvees dans le code).

---

## 3. Form Requests

### 3.1 Existantes
| Request | `authorize()` | Validation |
|---------|---------------|------------|
| `StoreVinyleRequest` | `auth()->check() && hasRole(['admin','employe'])` | Complete |
| `UpdateVinyleRequest` | Idem | Complete |
| `ProfileUpdateRequest` | **Manquante** (retourne true par defaut) | Rules OK |
| `LoginRequest` | `true` | Complete + RateLimit |

### 3.2 Controllers sans Form Request
- `OrderController`, `AddressController`, `ConversationController`, `CartController`, `PaymentController` utilisent `Request` brut avec `$request->validate()` inline — pas de classe dediee.

---

## 4. Injection SQL

### 4.1 Utilisation de raw SQL
- `StatsController` : multiples `selectRaw`, `DB::raw` avec chaines hardcodees sans parametres utilisateur — **securise**.
- `StockAlertController` : `whereRaw('quantite_actuelle > 0 AND quantite_actuelle <= seuil_alerte')` — colonnes hardcodees, **securise**.
- `VinyleController@index` : `where('artiste', 'like', "%{$search}%")` — binding automatique Eloquent, **securise**.

### 4.2 Conclusion
- Aucune requete brute avec concatenation de donnees utilisateur detectee. Pas de vulnerabilite d'injection SQL identifiee.

---

## 5. XSS — Affichage Blade

### 5.1 Utilisations de `{!! !!}`
| Fichier | Usage | Risque |
|---------|-------|--------|
| `text-input.blade.php` | `$attributes->merge([...])` | Faible — controle par Laravel |
| `admin/reviews/index.blade.php` | `$review->statusBadge()` | Faible — methode interne |
| `admin/contact-messages/index.blade.php` | `$message->statutBadge()` | Faible |
| `admin/conversations/*.blade.php` | `$conversation->statutBadge()` | Faible |
| `admin/orders/*.blade.php` | `$order->statutBadge()` | Faible |
| `vendor/media-library/*.blade.php` | `$attributeString` | Faible — Spatie |

### 5.2 Aucun `{!! !!}` avec donnees utilisateur brutes
- Pas de `{{ $userInput }}` manquant d'echappement detecte.

---

## 6. Uploads Spatie

### 6.1 Validation
- `StoreVinyleRequest` / `UpdateVinyleRequest` :
  ```php
  'photos' => 'nullable|array|max:3',
  'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:5120',
  ```
- Types MIME restreints (image uniquement).
- Taille max 5 Mo.
- Limite 3 photos par vinyle.

### 6.2 Stockage
- Utilisation de `addMedia($photo)->toMediaCollection('photo')` via Spatie MediaLibrary.
- Pas de verification de type MIME cote serveur apres l'upload, mais la validation Laravel `image|mimes:...` est suffisante avant le traitement.

---

## 7. Configuration .env / App

### 7.1 Fichier .env (local detecte)
```
APP_ENV=local
APP_DEBUG=true
APP_KEY=base64:ReUm1FoZbGiTBhaS8K+7LH1/hY5w+YZtfGZ5/fSa87g=
APP_URL=http://127.0.0.1:8003
```

### 7.2 Problemes
- **APP_DEBUG=true** en environnement local — OK pour dev, mais **CRITIQUE** si jamais deploye en production sans modification.
- **APP_URL en HTTP** — pas de HTTPS force. Pour la production, il faut `APP_URL=https://...` et `forceScheme('https')` dans `AppServiceProvider`.
- Pas de `FORCE_HTTPS` ou `SECURE_HEADERS_HSTS` detecte.

---

## 8. Mots de passe

### 8.1 Model User
- `$casts` contient `'password' => 'hashed'` — OK (Laravel 11+).
- `$hidden` contient `password`, `remember_token` — OK.

### 8.2 Hashage
- `Hash::make($password)` utilise partout (`RegisteredUserController`, `NewPasswordController`, `PasswordController`, `Admin\UserController`, `UserController`).

### 8.3 Reset token
- Reset via Breeze standard : `token` valide dans `NewPasswordController`, `remember_token` regenere (`Str::random(60)`).

### 8.4 TrimStrings
- `TrimStrings` exclut `current_password`, `password`, `password_confirmation` — OK.

---

## 9. CSRF

### 9.1 Protection
- `@csrf` present dans **tous** les formulaires POST verifies (auth, contact, cart, admin, orders, conversations, etc.).
- `VerifyCsrfToken` : `$except = []` — aucune URI exemptee (hors webhook Stripe qui est POST externe sans session).

### 9.2 Route webhook
- `POST /stripe/webhook` — publique, sans CSRF. Legitime car webhook externe Stripe qui ne passe pas par le navigateur.

---

## 10. Headers de securite

### 10.1 Middleware `SecurityHeaders`
```php
$response->headers->set('X-Frame-Options', 'SAMEORIGIN');
$response->headers->set('X-Content-Type-Options', 'nosniff');
$response->headers->set('X-XSS-Protection', '1; mode=block');
$response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
```

### 10.2 Content Security Policy
```php
"default-src * 'self' 'unsafe-inline' 'unsafe-eval' ..."
```
- **Trop permissive** : `default-src *`, `'unsafe-inline'`, `'unsafe-eval'` — rend la CSP inefficace contre XSS via injection de scripts.
- **Absence de HSTS** : pas de `Strict-Transport-Security`.

---

## Scores et Recommandations

### Critique (1)
1. **CSP trop permissive + absence HSTS**
   - Restreindre `default-src` aux domaines requis.
   - Retirer `'unsafe-eval'` si possible.
   - Ajouter `Strict-Transport-Security: max-age=63072000; includeSubDomains; preload` en production.

### Majeur (3)
2. **Absence totale de Policies / Gates**
   - Creer `VinylPolicy`, `OrderPolicy`, `UserPolicy`.
   - Enregistrer dans `AuthServiceProvider`.
   - Utiliser `$this->authorize()` dans les controllers.

3. **APP_DEBUG=true / APP_URL en HTTP**
   - Forcer `APP_DEBUG=false` en production.
   - Forcer `https` dans `AppServiceProvider::boot()` (`URL::forceScheme('https')`).

4. **ProfileUpdateRequest sans authorize()**
   - Ajouter `public function authorize(): bool { return auth()->check(); }`.

### Mineur (1)
5. **Route debug `_debug/merge-cart-test`**
   - Retirer ou deplacer dans un environnement de test (feature flag ou test PHPUnit) avant release.

---

*Rapport genere automatiquement. A reviser manuellement pour validation contextuelle.*
