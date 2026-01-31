# Étape — 31/01/2026 ✅

## Contexte rapide 🔧
- **Stack** : Laravel 10, PHP ^8.1, Vite + Tailwind pour le front.
- **Packages notables** : `laravel/breeze` (auth), `spatie/laravel-backup`, `spatie/laravel-medialibrary`.
- **Build & assets** : `package.json` / Vite (scripts `dev` / `build`).

---

## Authentification & création d'utilisateurs 🔐
- **Route d'inscription** : `GET|POST /register` (définie dans `routes/auth.php`).
- **Contrôleur** : `App\Http\Controllers\Auth\RegisteredUserController` (validation, création, login automatique).
- **Vue** : `resources/views/auth/register.blade.php` (formulaire : `name`, `email`, `password`, `password_confirmation`).
- **Tests** : couverture de l'inscription dans `tests/Feature/Auth/RegistrationTest.php` (chargement de la page + enregistrement effectif).
- **Conclusion** : L'inscription est fonctionnelle en local et prévue par les tests.

---

## Base de données & seeders 🗄️
- Migrations principales présentes dans `database/migrations/` (ex. `users`, `roles`, `vinyles`, `carts`, `orders`, `stock_alerts`).
- Seeder de production : `database/seeders/ProductionUserSeeder.php` crée un admin `admin@la-main-a-la-pate.online` avec mot de passe placeholder `CHANGE_ME_SECURE_PASSWORD` (à **changer immédiatement** en production).

---

## Tests & CI 🧪
- **Tests** : `php artisan test` / `phpunit.xml` existants, tests `Feature` pour auth, kiosque, etc.
- **CI** : Aucun workflow GitHub Actions détecté dans le repo (pas de `.github/workflows` trouvé). Recommandation : ajouter un workflow CI pour lancer `composer install` + `php artisan test` sur push/PR.

---

## Déploiement 🚀
- Scripts : `deploy.sh` et `push_deploy_prod.sh` présents et configurés pour le domaine `la-main-a-la-pate.online`.
- Processus : installation des dépendances, exécution des migrations (`--force`), optimisation, et (dans `push_deploy_prod.sh`) exécution des tests avant push (sauf si `--skip-tests`).
- Note sécurité : les scripts effectuent des migrations en production (prévoir backups avant migration, `spatie/backup` est disponible pour cela).

---

## Observations / Risques ⚠️
- Le seeder de production installe des comptes avec mots de passe par défaut — **vérifier et modifier** avant mise en production.
- L'inscription est accessible publiquement (`/register`). Si vous voulez restreindre les créations de comptes, prévoir une configuration (ex : mettre `Route::has('register')` conditionnel ou middleware / invitation).
- Pas de CI actuellement : risque de régression non détectée lors des pushes.
- Vérifier que la configuration mail (envoi d'emails de vérification / reset) est correctement paramétrée en production.

---

## Recommandations (prioritaires) ✅
1. **Changer immédiatement** les mots de passe créés par le seeder `ProductionUserSeeder` et/ou supprimer ce seeder en prod.
2. **Ajouter CI** (GitHub Actions) pour lancer `composer install` + `php artisan test` sur PR et push.
3. **Mettre en place un backup automatique** avant migrations (vérifier `spatie/laravel-backup` config).
4. **Vérifier l'envoi d'emails** (SMTP / credentials) en environnement production (pour la vérification d'email et reset).
5. Si nécessaire, décider d'une politique pour **ouvrir/fermer l'inscription publique** (ou utiliser des invitations).

---

## Prochaines actions proposées ✍️
- Ajouter un **workflow GitHub Actions** minimal pour tests.
- Auditer les seeders et supprimer/maquiller les comptes `CHANGE_ME`.
- Lancer une vérification manuelle sur l'instance `https://la-main-a-la-pate.online/register` après avoir sécurisé les comptes et les mails.

---

*Rédigé automatiquement — résumé rapide du projet au 31/01/2026.*
