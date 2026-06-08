# Audit Performance — Vinyles Stock

> Date : 2026-06-08 | Stack : Laravel 11+, SQLite, Spatie MediaLibrary

---

## 1. Résumé exécutif

| Catégorie | Score | Remarque |
|-----------|-------|----------|
| N+1 / Requêtes | 🟡 Moyen | 3 N+1 identifiés, 2 chargements complets sans pagination |
| Indexes SQLite | 🟡 Moyen | 6 indexes manquants sur FK et champs de filtrage fréquents |
| Pagination | 🟢 Bon | Pagination présente sur les listes publiques |
| Cache | 🟡 Moyen | Uniquement sur StatsController, absent du dashboard/reports |
| MediaLibrary | 🟢 Bon | WebP + resize 800x800, nonQueued |
| Assets (CSS/JS) | 🟢 Bon | Vite + build minifié en prod |

---

## 2. Routes / Controllers audités

| Route principale | Controller::method | Impact |
|------------------|-------------------|--------|
| `/` | `HomeController::landing` | Landing publique |
| `/kiosque` | `VinyleController::kiosque` | Catalogue public |
| `/cart/*` | `CartController::*` | Panier |
| `/orders/*` | `OrderController::*` | Commande |
| `/admin/marche/*` | `ModeMarcheController::*` | Mode marché |
| `/admin/vinyles/*` | `VinyleController::*` | Admin CRUD |
| `/admin/dashboard` | `DashboardController::*` | Dashboard |

---

## 3. N+1 / Requêtes lentes identifiées

### 🔴 N+1 confirmé — Panier (`CartController::index`)

```php
// CartService::getCart() ne charge PAS les relations
$cart = $this->cartService->getCart();

// Blade : cart/index.blade.php — boucle @foreach($cart->items as $item)
//   → $item->vinyle  (requête par item)
//   → $item->fond    (requête par item)
```

**Impact** : N items = N+2 requêtes.
**Fix** : `Cart::with('items.vinyle', 'items.fond')->firstOrCreate(...)` dans `CartService::getCart()`.

---

### 🔴 N+1 confirmé — Commande create (`OrderController::create`)

Même pattern que le panier : `$cart->items->count()` puis accès aux relations vinyle/fond dans la vue.

**Fix** : Idem — eager load dans `getCart()` ou dans le controller.

---

### 🔴 N+1 confirmé — Rapport par Artiste (`ReportController::artists`)

```php
$artists = $query->orderByDesc('stock_value')->get();

// map() — 1 requête PAR artiste :
$vinyles = Vinyle::where('artiste', $artist->artiste)->get();  // ← N+1
```

**Fix** : Remplacer par une sous-requête agrégée ou un `with(['ventes'])` préchargé puis grouper en mémoire.

---

### 🟡 Chargement complet — Mode Marché (`ModeMarcheController::index`)

```php
$vinyles = Vinyle::where('quantite', '>', 0)
    ->orderBy('modele')
    ->get()          // ← pas de pagination, charge TOUT
```

**Impact** : Si 500+ vinyles en stock, réponse lente.
**Fix** : Ajouter `->paginate(50)` ou un lazy-load/infinite scroll.

---

### 🟡 Chargement complet — Rapport Stock (`ReportController::stock`)

```php
$vinyles = Vinyle::all();   // ← tous
$fonds   = Fond::all();     // ← tous
```

**Fix** : Pagination ou chunk(100). Les stats agrégées (`sum`, `count`) peuvent être faites en SQL pur.

---

### 🟡 Requêtes répétées — Dashboard (`DashboardController::index` + `statsApi` + `chartsApi`)

```php
$ventesMensuelles = collect(range(5, 0))->map(function ($monthsAgo) {
    // 6 requêtes identiques avec WHERE BETWEEN
    return Order::where('statut', 'livree')->whereBetween(...)->sum('total');
});
```

**Fix** : `Cache::remember('dashboard.ventes_mensuelles', 300, fn() => ...)`.

---

### 🟡 Attribut manquant — `image_urls` (`ModeMarcheController::index`)

```php
'image_url' => $vinyle->image_urls[0] ?? null,   // ← propriété inexistante dans Vinyle.php
```

**Impact** : Retourne `null` systématiquement (pas de N+1 car null direct, mais fonctionnellement cassé).
**Fix** : Utiliser `$vinyle->getFirstMediaUrl('photo', 'thumb')` ou ajouter un accessor `image_urls`.

---

## 4. Indexes SQLite — Inventaire

### Tables avec indexes ✅

| Table | Index | Type |
|-------|-------|------|
| vinyles | `vinyles_reference_unique` | UNIQUE |
| vinyles | `vinyles_artiste_index` | INDEX |
| vinyles | `vinyles_genre_index` | INDEX |
| vinyles | `vinyles_reference_index` | INDEX |
| orders | `orders_numero_commande_unique` | UNIQUE |
| orders | `orders_statut_index` | INDEX |
| orders | `orders_created_at_index` | INDEX |
| carts | `unique_user_cart` | UNIQUE (user_id) |
| carts | `carts_session_id_index` | INDEX |
| media | `media_model_type_model_id_index` | INDEX |
| reviews | `reviews_vinyle_id_user_id_unique` | UNIQUE |
| reviews | `reviews_vinyle_id_status_index` | INDEX |
| mouvements_stock | `produit_type_produit_id_index` | INDEX |
| addresses | `addresses_user_id_index` | INDEX |

### 🔴 Indexes MANQUANTS (migrations à créer)

| Table | Champ | Justification |
|-------|-------|---------------|
| **vinyles** | `quantite` | Filtrage `where('quantite', '>', 0)` très fréquent |
| **vinyles** | `created_at` | `orderBy('created_at', 'desc')` sur landing + kiosque |
| **vinyles** | `modele` | `orderBy('modele')` sur mode marché |
| **orders** | `user_id` | FK — `where('user_id', Auth::id())` dans myOrders |
| **orders** | `source` | `where('source', 'marche')` très fréquent (mode marché) |
| **order_items** | `order_id` | FK — `hasMany` sans index = full scan |
| **order_items** | `vinyle_id` | FK — utilisé dans annulation/restock |
| **ligne_ventes** | `vente_id` | FK — join fréquent avec `ventes` |
| **ligne_ventes** | `vinyle_id` | FK — jointure dans top produits |
| **ventes** | `created_at` | `whereBetween` dans reports |
| **ventes** | `user_id` | FK |

---

## 5. Pagination

| Endpoint | État | Remarque |
|----------|------|----------|
| `/admin/vinyles` | ✅ `paginate(25)` | OK |
| `/kiosque` | ✅ `paginate(24)` | OK |
| `/mes-commandes` | ✅ `paginate(10)` | OK |
| `/admin/orders` | ✅ `paginate(20)` | OK |
| `/admin/marche` | ❌ `get()` | **Ajouter pagination** |
| `/admin/reports/stock` | ❌ `all()` | **Chunker ou paginer** |
| `/admin/reports/artists` | ❌ `get()` | **Ajouter pagination** |

---

## 6. Cache Laravel

| Zone | État | Remarque |
|------|------|----------|
| `StatsController` | ✅ `Cache::remember(300s)` | OK |
| Dashboard stats | ❌ Aucun cache | 6+ requêtes SQL par chargement |
| Dashboard charts API | ❌ Aucun cache | 12+ requêtes par appel |
| Genres / Fonds quasi-statiques | ❌ Aucun cache | Peuvent être cachés 1h+ |
| Rapport stock | ❌ Aucun cache | Recalcul complet à chaque clic |

---

## 7. Spatie MediaLibrary

| Aspect | État | Détail |
|--------|------|--------|
| Conversions | ✅ `thumb` 200x200 WebP | `nonQueued()` — synchrone |
| Conversions | ✅ `medium` 800x800 WebP | `nonQueued()` — synchrone |
| Collection | ✅ `photo` sur disk `public` | 3 images max (validé controller) |
| Disk | ✅ `public` | OK |

**Note** : `getFirstMediaUrl('photo', 'medium')` est utilisé dans le kiosque. Avec `with(['media'])`, les médias sont en mémoire — pas de requête supplémentaire.

---

## 8. Assets (CSS/JS)

| Aspect | État | Détail |
|--------|------|--------|
| Bundler | ✅ Vite | `vite.config.js` configuré |
| Build prod | ✅ `vite build` | Minifié automatiquement |
| CSS | ✅ Tailwind + app.css | Single entry point |
| JS | ✅ app.js + Alpine/Vue | Single entry point |

---

## 9. Recommandations prioritaires

### P1 — N+1 (impact immédiat)

1. **CartService::getCart()** : ajouter `with('items.vinyle', 'items.fond')`.
2. **ReportController::artists** : refactorer le `map()` pour éviter `Vinyle::where('artiste', ...)->get()`.
3. **OrderController::create** : eager load le panier + items avant la vue.

### P2 — Indexes (migration rapide)

4. Créer une migration d'ajout d'indexes sur :
   - `vinyles(quantite)`, `vinyles(created_at)`, `vinyles(modele)`
   - `orders(user_id)`, `orders(source)`
   - `order_items(order_id)`, `order_items(vinyle_id)`
   - `ligne_ventes(vente_id)`, `ligne_ventes(vinyle_id)`
   - `ventes(created_at)`, `ventes(user_id)`

### P3 — Pagination / Cache

5. **ModeMarcheController::index** : remplacer `->get()` par `->paginate(50)`.
6. **ReportController::stock** : utiliser `chunk(100)` ou requêtes SQL agrégées.
7. **DashboardController** : wrapper les stats dans `Cache::remember('dashboard.*', 300, fn() => ...)`.
8. **Données quasi-statiques** : cacher genres, fonds, config pendant 1h.

### P4 — Corrections fonctionnelles

9. **ModeMarcheController::index** : remplacer `$vinyle->image_urls[0]` par un accessor ou `getFirstMediaUrl`.

---

## 10. Migrations d'indexes suggérées

```php
// Exemple de migration à créer : database/migrations/2026_06_08_xxxx_add_performance_indexes.php

Schema::table('vinyles', function (Blueprint $table) {
    $table->index('quantite');
    $table->index('created_at');
    $table->index('modele');
});

Schema::table('orders', function (Blueprint $table) {
    $table->index('user_id');
    $table->index('source');
});

Schema::table('order_items', function (Blueprint $table) {
    $table->index('order_id');
    $table->index('vinyle_id');
});

Schema::table('ligne_ventes', function (Blueprint $table) {
    $table->index('vente_id');
    $table->index('vinyle_id');
});

Schema::table('ventes', function (Blueprint $table) {
    $table->index('created_at');
    $table->index('user_id');
});
```
