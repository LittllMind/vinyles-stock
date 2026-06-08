# Audit SEO & Accessibilité — Vinyles Stock

**Date** : 2025-06-08  
**Scope** : Vue publique art-print (landing, kiosque, fiche vinyle, panier, checkout) + pages admin  
**Stack** : Laravel 11+ / Blade / Thème "art-print" / Pas de npm/Vue

---

## Résumé

Le projet dispose d'une **bonne base SEO technique** (Open Graph, Twitter Cards, canonical URL, meta description par defaut dans le layout) mais **ne l'exploite pas sur les pages produits**. L'accessibilité est **faible** : pas de Schema.org, pas de lazy-loading, contrastes admin defaillants, navigation mobile non dediee, et plusieurs hierarchies de titres incorrectes.

| Score | /10 |
|---|---|
| **SEO** | **5/10** |
| **Accessibilité** | **3/10** |

---

## Détail par catégorie

### 1. Balises `<title>`

| Page | Title | État |
|---|---|---|
| Landing (`/`) | `Accueil • FUN DISC` | ⚠️ Generique — devrait etre descriptif (ex: "Vinyles découpés en œuvres d'art — FUN DISC") |
| Kiosque | `Collection • FUN DISC` | ✅ OK |
| Vinyle détail | `Artiste – Modèle • FUN DISC` | ✅ Excellent |
| Panier | `Panier • FUN DISC` | ✅ OK |
| Checkout (livraison) | `Livraison • FUN DISC` | ✅ OK |
| Paiement | `Paiement • FUN DISC` | ✅ OK |
| Admin dashboard | `Tableau de bord Admin • FUN DISC` | ✅ OK |
| Auth forgot/verify/confirm/reset | `• FUN DISC` (vide) | ❌ **Critique** — 4 pages avec `<title>` vide |
| Admin vinyles index | `Vinyles • FUN DISC` | ✅ OK |
| Mode Marché | `Mode Marché — Vente sur place • FUN DISC` | ✅ OK |

**Recommandation** : corriger les 4 pages auth vides + enrichir le title de la landing.

---

### 2. Meta description, keywords, OpenGraph

| Élément | État |
|---|---|
| **Meta description** (layout) | ✅ Presente, generique mais fonctionnelle |
| **Meta description** (landing) | ✅ Surchargée avec description marketing |
| **Meta description** (fiche vinyle) | ❌ **Absente** — aucune `@section('meta_description')` dans `vinyles/show.blade.php` |
| **og:title / og:description** (landing) | ✅ Surchargés |
| **og:title / og:description** (fiche vinyle) | ❌ **Absents** — la fiche produit prend les valeurs par defaut du layout |
| **og:image** (fiche vinyle) | ❌ **Image generique** (`og-default.jpg`) au lieu de la photo du vinyle |
| **og:url** | ✅ `url()->current()` dans le layout |
| **Twitter Cards** | ✅ Presentes dans le layout |
| **Canonical** | ✅ Presente dans le layout |
| **Meta keywords** | N/A (Google n'y donne plus de poids) |

**Recommandation** : ajouter `@section('meta_description')`, `@section('og_title')`, `@section('og_description')` et `@section('og_image')` sur la fiche vinyle avec la photo du produit et un texte riche.

---

### 3. Schema.org / JSON-LD

| Page | Schema.org | État |
|---|---|---|
| Fiche vinyle | `Product`, `Offer` | ❌ **Totalement absent** |
| Kiosque | `ItemList` | ❌ Absent |
| Landing | `Organization` / `WebSite` | ❌ Absent |

**Recommandation** : injecter un bloc JSON-LD `<script type="application/ld+json">` dans `vinyles/show.blade.php` avec `Product` (nom, image, description, marque/artiste, SKU/reference) + `Offer` (prix, prixCurrency: EUR, availability, url).

---

### 4. Sitemap

| Élément | État |
|---|---|
| Route `/sitemap.xml` | ❌ **N'existe pas** |
| `robots.txt` | ✅ Present (`Disallow:` vide, tout autorise) |
| Référence sitemap dans robots.txt | ❌ Absente |

**Recommandation** : créer une route + controleur `SitemapController` qui genere un `sitemap.xml` avec les URLs statiques (landing, kiosque, about, contact, CGV) + toutes les fiches vinyles actives.

---

### 5. Images (alt + lazy loading)

| Contexte | `alt` | `loading="lazy"` |
|---|---|---|
| Fiche vinyle (principale) | ✅ `{{ $vinyle->artiste }} - {{ $vinyle->modele }}` | ❌ Absent |
| Fiche vinyle (miniatures) | ❌ **Vide** (`<img src="thumb">` sans alt) | ❌ Absent |
| Kiosque grille | ✅ `{{ $vinyle['artiste'] }}` | ❌ Absent |
| Landing featured | ✅ `{{ $vinyle->artiste }}` | ❌ Absent |
| Panier | ✅ `{{ $vinyle->artiste }}` | ❌ Absent |
| Carte `ap-card` | ✅ `{{ $title }}` | ❌ Absent |
| Stock-alerts (admin) | ❌ `alt=""` vide (3 img) | ❌ Absent |
| Marche (admin) | ✅ `:alt="vinyle.nom"` | ❌ Absent |

**Recommandation** :
- Ajouter `loading="lazy"` sur toutes les images en dessous du fold (kiosque, suggestions, miniatures).
- Corriger les `alt=""` vides dans `stock-alerts/index.blade.php`.
- Ajouter un alt descriptif sur les miniatures de la fiche vinyle.

---

### 6. Contrastes de couleur (WCAG AA)

| Couleur | Sur fond | Ratio | Conformité AA (texte <18pt) |
|---|---|---|---|
| `#1A1A1A` (texte principal) | `#FFFFFF` | ~16:1 | ✅ Pass |
| `#666666` (texte secondaire) | `#FFFFFF` | ~5.7:1 | ✅ Pass (limite) |
| `#999999` (texte muted) | `#FFFFFF` | ~2.8:1 | ❌ **Fail** |
| `#b8a77d` (gold landing) | `#1a1a1a` (dark) | ~4.5:1 | ⚠️ Limite (AA pass pour >18pt, fail pour <18pt) |
| `text-gray-400` (admin labels) | `#FFFFFF` | ~2.8:1 | ❌ **Fail** |
| `text-gray-500` (admin sous-titres) | `#FFFFFF` | ~3.5:1 | ❌ **Fail** |
| `#dc2626` (rouge sorties) | blanc | ~5.6:1 | ✅ Pass |
| `#16a34a` (vert entrees) | blanc | ~3.4:1 | ❌ **Fail** |

**Recommandation** :
- Remplacer `#999` et `text-gray-400` par au moins `#767676` (ratio 4.5:1).
- Remplacer `text-gray-500` par `#595959` ou plus fonce.
- Verifier le gold `#b8a77d` sur fond sombre — augmenter la saturation ou passer en `#c9b896`.

---

### 7. Navigation mobile

| Critère | État |
|---|---|
| Menu hamburger / mobile | ❌ **Absent** dans `ap-nav.blade.php`. Le menu est un flex horizontal sans breakpoint mobile dedie |
| Tap target min 44×44px | ⚠️ Les liens de nav sont du texte sans zone de tap explicite (padding 0.5rem vertical ≈ 32px) |
| Dropdowns accessibles | ❌ Pas de `aria-expanded`, pas de gestion clavier (Enter/Escape/Arrow), pas de `role="button"` sur les toggles `href="#"` |
| Panier (icon) | ⚠️ `title="Mon panier"` present mais pas d'`aria-label` explicite |

**Recommandation** :
- Ajouter un bouton hamburger avec `aria-expanded`, `aria-controls` pour mobile.
- Ajouter `aria-expanded` dynamique sur les toggles dropdown.
- S'assurer que les dropdowns se ferment avec `Escape` et se naviguent au clavier.

---

### 8. Formulaires (labels, aria, erreurs)

| Page | Labels `for` | `aria-invalid` / `aria-describedby` | Erreurs liées |
|---|---|---|---|
| Checkout (`orders/create`) | ⚠️ Labels visuels en `<label>` mais **pas d'attribut `for`** associe aux inputs (structure inline style) | ❌ Absent | ❌ Absent |
| Auth (login/register) | ✅ `for` + `id` corrects (Laravel Breeze) | ❌ Absent | ⚠️ Messages affichés mais pas lies via `aria-describedby` |
| Profile edit (`users/edit`) | ✅ `for` + `id` corrects | ❌ Absent | ⚠️ Idem |
| Contact (`contact.blade.php`) | N/A (pas de formulaire) | N/A | N/A |

**Recommandation** :
- Ajouter `for` sur tous les labels du checkout et lier aux `id` des inputs.
- Ajouter `aria-invalid="true"` + `aria-describedby="error-nom"` sur les champs en erreur.
- Utiliser le composant `<x-input-error>` avec un `id` pour chaque message.

---

### 9. Liens vides / `href="#"` sans fonction

| Fichier | Ligne | Probleme |
|---|---|---|
| `vinyles/index.blade.php` | 82-83 | 2 liens `href="#"` sans action (Modifier/Voir) — devraient pointer vers les routes edit/show |
| `vinyles/index.blade.php` | 89 | Lien "Créer le premier" `href="#"` |
| `ap-nav.blade.php` | 51, 71, 108, 138 | 4 toggles dropdown `href="#"` avec `onclick` — manquent `role="button"` et `aria-expanded` |
| `ap-nav.blade.php` | 151 | Logout `href="#"` avec `onclick` — OK car il submit le form, mais devrait etre un `<button>` |
| `marche/index.blade.php` | 53 | Cartes vinyles cliquables via `onclick` sur `<div>` — pas accessibles au clavier (Enter/Space) |

**Recommandation** :
- Remplacer les liens vides par les vraies routes ou des `<button>`.
- Ajouter `role="button"`, `tabindex="0"`, `aria-expanded` sur les toggles dropdown.
- Transformer les `<div onclick>` du mode marche en `<button>` ou `<a>`.

---

### 10. Heading hierarchy

| Page | h1 | Probleme |
|---|---|---|
| Landing | **2× `<h1>`** : "FUN" + "DISC" | ❌ Double h1. Devrait etre un seul h1 contenant les deux lignes, ou FUN DISC en h1 et le reste en h2/p |
| Kiosque | `<h1>` OK | ⚠️ Saut h1 → h3 ("Collection vide") sans h2 |
| Fiche vinyle | `<h1>` OK | ✅ OK |
| Panier | `<h1>` OK | ⚠️ Saut h1 → h3 ("Votre sélection est vide") |
| Checkout | `<h1>` OK | ⚠️ Saut h1 → h2 ("Contact") — OK si structure coherente |
| Admin vinyles index | **Aucun h1** | ❌ Commence par h2 ("Tous les vinyles") |
| Admin dashboard | `<h1>` OK | ✅ OK |
| Stock alerts | `<h1>` OK | ⚠️ Sauts h1 → h3 multiples |

**Recommandation** :
- Fusionner les deux h1 de la landing en un seul.
- Ajouter un h1 sur `vinyles/index.blade.php` (admin).
- Inserer des h2 entre h1 et h3 dans le panier, le kiosque et stock-alerts.

---

## Recommandations prioritaires

### 🔴 Critique (impact SEO / A11y majeur)

1. **Ajouter Schema.org JSON-LD Product/Offer sur la fiche vinyle** — impact direct sur les rich snippets Google.
2. **Corriger les 4 pages auth avec `<title>` vide** — mauvais signal SEO + accessibilite.
3. **Ajouter `meta_description` + `og_*` specifiques sur la fiche vinyle** — le contenu par defaut ne decrit pas le produit.
4. **Ajouter `loading="lazy"` sur les images du kiosque et des miniatures** — gain performance immediat.
5. **Corriger les contrastes admin** : remplacer `text-gray-400` et `#999` par des teintes plus foncees (`#767676` minimum).

### 🟠 Important

6. **Creer la route `/sitemap.xml`** avec toutes les pages produits actives.
7. **Ajouter un menu mobile/hamburger** avec `aria-expanded` et navigation clavier.
8. **Corriger la double h1** sur la landing page.
9. **Ajouter des `for` + `id` sur les labels du checkout** et des `aria-invalid`/`aria-describedby` sur les champs en erreur.
10. **Remplacer les `<div onclick>` du mode marche** par des elements interactifs accessibles.

### 🟡 Amelioration

11. **Ajouter un `og:image` dynamique** sur chaque fiche vinyle (photo du produit).
12. **Corriger les `alt=""` vides** dans `stock-alerts/index.blade.php`.
13. **Ajouter `aria-expanded` sur les dropdowns** de navigation.
14. **Uniformiser les hierarchies de titres** (pas de sauts h1→h3).
15. **Ajouter un `aria-label` explicite** sur l'icone panier.

---

## Fichiers audités (selection)

- `resources/views/layouts/art-print.blade.php`
- `resources/views/layouts/admin-art-print.blade.php`
- `resources/views/landing.blade.php`
- `resources/views/kiosque.blade.php`
- `resources/views/vinyles/show.blade.php`
- `resources/views/vinyles/kiosque.blade.php`
- `resources/views/vinyles/index.blade.php`
- `resources/views/cart/index.blade.php`
- `resources/views/orders/create.blade.php`
- `resources/views/orders/payment.blade.php`
- `resources/views/marche/index.blade.php`
- `resources/views/admin/dashboard.blade.php`
- `resources/views/components/art_print/ap-nav.blade.php`
- `resources/views/components/art_print/ap-footer.blade.php`
- `public/css/art-print-theme.css`
- `public/robots.txt`
