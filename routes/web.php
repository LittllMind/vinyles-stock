<?php

use App\Http\Controllers\VinyleController;
use App\Http\Controllers\StatsController;
use App\Http\Controllers\VenteController;
use App\Http\Controllers\FondController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StockAlertController;
use App\Http\Controllers\StockMovementController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ModeMarcheController;
use App\Http\Controllers\ContactController;

// ============================================
// ROUTES PUBLIQUES (Accès sans authentification)
// ============================================
Route::get('/', [HomeController::class, 'landing'])->name('landing');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Pages légales
Route::get('/conditions-generales-de-vente', [HomeController::class, 'cgv'])->name('cgv');
Route::get('/mentions-legales', [HomeController::class, 'mentionsLegales'])->name('mentions-legales');
Route::get('/politique-de-confidentialite', [HomeController::class, 'confidentialite'])->name('confidentialite');

Route::post('/contact', [ContactController::class, 'store'])->name('contact.store');
Route::get('/articles/hermes-vs-openclaw', [HomeController::class, 'articleHermesVsOpenclaw'])->name('articles.hermes-vs-openclaw');

// Routes conversations clients (authentifiés)
Route::middleware('auth')->group(function () {
    Route::get('/conversations', [App\Http\Controllers\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [App\Http\Controllers\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations', [App\Http\Controllers\ConversationController::class, 'store'])->name('conversations.store');
    Route::post('/conversations/{conversation}/reply', [App\Http\Controllers\ConversationController::class, 'reply'])->name('conversations.reply');
    Route::post('/orders/{order}/contact', [App\Http\Controllers\ConversationController::class, 'storeFromOrder'])->name('orders.contact');
});

    // Routes reviews (authentifiés)
    Route::middleware('auth')->group(function () {
        Route::post('/vinyles/{vinyle}/reviews', [App\Http\Controllers\ReviewController::class, 'store'])->name('reviews.store');
    });
// Switcher de thème désactivé - ART PRINT est le thème unique
// Route::get('/theme/{theme}', function ($theme) { ... })->name('theme.switch');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// ============================================
// ROUTES ADMIN ORDERS (Admin et Employé)
// ============================================
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/orders', [\App\Http\Controllers\Admin\OrderAdminController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [\App\Http\Controllers\Admin\OrderAdminController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [\App\Http\Controllers\Admin\OrderAdminController::class, 'updateStatus'])->name('orders.status');
    Route::post('/orders/{order}/cancel', [\App\Http\Controllers\Admin\OrderAdminController::class, 'cancel'])->name('orders.cancel');
});
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

// ============================================
// ROUTES ADMIN REPORTS (Admin et Employé)
// ============================================
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports/monthly', [\App\Http\Controllers\Admin\ReportController::class, 'monthlyReportForm'])->name('reports.monthly');
    Route::post('/reports/monthly', [\App\Http\Controllers\Admin\ReportController::class, 'generateMonthlyReport'])->name('reports.monthly.generate');
    
    // Rapports T13.1
    Route::get('/reports/stock', [\App\Http\Controllers\Admin\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('/reports/artists', [\App\Http\Controllers\Admin\ReportController::class, 'artists'])->name('reports.artists');
});

// ============================================
// ROUTES ADMIN REPORTS INVENTORY (Admin uniquement)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/reports/inventory/vinyls/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportVinylesInventory'])->name('reports.inventory.vinyls');
    Route::get('/reports/inventory/fonds/pdf', [\App\Http\Controllers\Admin\ReportController::class, 'exportFondsInventory'])->name('reports.inventory.fonds');
    // Routes reviews admin
    Route::get('/reviews', [\App\Http\Controllers\Admin\ReviewController::class, 'index'])->name('reviews.index');
    Route::get('/reviews/pending', [\App\Http\Controllers\Admin\ReviewController::class, 'pending'])->name('reviews.pending');
    Route::patch('/reviews/{review}/approve', [\App\Http\Controllers\Admin\ReviewController::class, 'approve'])->name('reviews.approve');
    Route::patch('/reviews/{review}/reject', [\App\Http\Controllers\Admin\ReviewController::class, 'reject'])->name('reviews.reject');
});

// ============================================
// ROUTES ADMIN DASHBOARD (Admin et Employé)
// ============================================
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'statsApi'])->name('stats.json');
    Route::get('/stats/charts', [\App\Http\Controllers\Admin\DashboardController::class, 'chartsApi'])->name('stats.charts');
});

// ============================================
// ROUTES ADMIN CONTACT MESSAGES (Admin et Employé)
// ============================================
use App\Http\Controllers\Admin\ContactMessageController;
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/contact-messages', [ContactMessageController::class, 'index'])->name('contact-messages.index');
    Route::get('/contact-messages/{message}', [ContactMessageController::class, 'show'])->name('contact-messages.show');
    Route::patch('/contact-messages/{message}/read', [ContactMessageController::class, 'markAsRead'])->name('contact-messages.read');
    Route::post('/contact-messages/{message}/reply', [ContactMessageController::class, 'reply'])->name('contact-messages.reply');
    Route::patch('/contact-messages/{message}/archive', [ContactMessageController::class, 'archive'])->name('contact-messages.archive');

    // Conversations admin (nouveau)
    Route::get('/conversations', [\App\Http\Controllers\Admin\ConversationController::class, 'index'])->name('conversations.index');
    Route::get('/conversations/{conversation}', [\App\Http\Controllers\Admin\ConversationController::class, 'show'])->name('conversations.show');
    Route::post('/conversations/{conversation}/reply', [\App\Http\Controllers\Admin\ConversationController::class, 'reply'])->name('conversations.reply');
    Route::patch('/conversations/{conversation}/close', [\App\Http\Controllers\Admin\ConversationController::class, 'close'])->name('conversations.close');
    Route::patch('/conversations/{conversation}/messages/{message}/read', [\App\Http\Controllers\Admin\ConversationController::class, 'markMessageRead'])->name('conversations.messages.read');
});

// ============================================
// ROUTES FONDS - LECTURE (Admin et Employé)
// ============================================
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin')->group(function () {
    // Liste et affichage des fonds
    Route::get('/fonds', [FondController::class, 'index'])->name('fonds.index');
    Route::get('/fonds/{fond}', [FondController::class, 'show'])->name('fonds.show');
});

// ============================================
// ROUTES FONDS - MODIFICATION (Admin uniquement)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {
    // Modification du stock fonds (Admin uniquement)
    Route::patch('/fonds/{fond}/stock', [FondController::class, 'updateStock'])->name('fonds.updateStock');
    
    // Modification des prix fonds (Admin uniquement)
    Route::patch('/fonds/{fond}/prix', [FondController::class, 'updatePrix'])->name('fonds.updatePrix');
});

// ============================================
// ROUTES ADMIN (Accès restreint: admin ET employe)
// ============================================
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin')->group(function () {
    // Recherche avancée des vinyles - AVANT le resource pour éviter conflit avec {vinyle}
    Route::get('/vinyles/recherche', [VinyleController::class, 'search'])->name('vinyles.search');
    
    // Gestion complète des vinyles (CRUD)
    Route::resource('vinyles', VinyleController::class);

    // Statistiques
    Route::get('/stats', [StatsController::class, 'index'])->name('stats');

    // Note: Les routes fonds sont déjà définies plus haut (lignes ~78-81)
    // Pas de duplication ici

    // Historique des mouvements de stock
    Route::get('/mouvements', [StockMovementController::class, 'index'])->name('mouvements.index');
    Route::get('/mouvements/export', [StockMovementController::class, 'export'])->name('mouvements.export');

    // Gestion des ventes (admin)
    Route::resource('ventes', VenteController::class);

    // Note: Les routes Mode Marché sont définies en dehors de ce groupe
    // pour avoir les noms 'marche.xxx' sans préfixe 'admin.'
});

// ============================================
// ROUTES MODE MARCHÉ (Admin et Employé)
// ============================================
// Définies en dehors du groupe admin pour garder les noms 'marche.xxx'
Route::middleware(['auth', 'role:admin,employe'])->prefix('admin/marche')->name('marche.')->group(function () {
    Route::get('/', [ModeMarcheController::class, 'index'])->name('index');
    Route::post('/store', [ModeMarcheController::class, 'store'])->name('store');
    Route::get('/ventes-jour', [ModeMarcheController::class, 'ventesJour'])->name('ventes-jour');
    Route::get('/check-stock/{vinyle}', [ModeMarcheController::class, 'checkStock'])->name('check-stock');
    Route::post('/{order}/cancel', [ModeMarcheController::class, 'cancel'])->name('cancel');
    Route::get('/export', [ModeMarcheController::class, 'export'])->name('export');
});

// ============================================
// ROUTES ALERTES STOCK (Admin et Employé)
// ============================================
Route::middleware(['auth', 'role:admin,employe'])->group(function () {
    Route::get('/stock-alerts', [StockAlertController::class, 'index'])->name('stock-alerts.index');
    Route::get('/stock-alerts/history', [StockAlertController::class, 'history'])->name('stock-alerts.history');
    Route::get('/stock-alerts/export', [StockAlertController::class, 'export'])->name('stock-alerts.export');
    Route::patch('/stock-alerts/{alert}/resolve', [StockAlertController::class, 'resolve'])->name('stock-alerts.resolve');
    Route::post('/stock-alerts', [StockAlertController::class, 'store'])->name('stock-alerts.store');
});

// ============================================
// ROUTES KIOSQUE (Accès public pour consultation)
// ============================================
Route::prefix('kiosque')->name('kiosque.')->group(function () {
    // Consultation du catalogue - accessible à tous (visiteurs inclus)
    Route::get('/', [VinyleController::class, 'kiosque'])->name('index');
    Route::get('/catalogue', [VinyleController::class, 'kiosque'])->name('catalogue');

    // Affichage public d'un vinyle (style galerie)
    Route::get('/vinyle/{vinyle}', [VinyleController::class, 'showPublic'])->name('show');

    // Achat - nécessite d'être connecté
    Route::post('/vendre', [VenteController::class, 'storeFromKiosque'])
        ->middleware('auth')
        ->name('vendre');
});

// ============================================
// ROUTES CLIENT (Accès public ou authentifié)
// ============================================
// Panier public (accessible sans connexion)
Route::prefix('cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index');
    Route::post('/add', [CartController::class, 'add'])->name('add');
    Route::patch('/{item}', [CartController::class, 'update'])->name('update');
    Route::delete('/{item}', [CartController::class, 'remove'])->name('remove');
    Route::post('/clear', [CartController::class, 'clear'])->name('clear');
    Route::get('/count', [CartController::class, 'count'])->name('count');
});

// Création de commande (authentifié)
Route::middleware('auth')->group(function () {
    // Adresses
    Route::resource('addresses', AddressController::class);
    Route::post('/addresses/{id}/set-default', [AddressController::class, 'setDefault'])->name('addresses.setDefault');

    // Commandes
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/payment', [OrderController::class, 'payment'])->name('orders.payment');

    // Routes de succès/annulation de commande
    Route::get('/orders/success', [OrderController::class, 'success'])->name('orders.success');
    Route::get('/orders/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');

    // Mes commandes (historique client)
    Route::get('/mes-commandes', [OrderController::class, 'myOrders'])->name('orders.my');

    // Profil utilisateur
    Route::get('/profil', [UserController::class, 'profile'])->name('profile');
    Route::get('/profil/edit', [UserController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profil', [UserController::class, 'updateProfile'])->name('profile.update');
    Route::patch('/profil/password', [UserController::class, 'updatePassword'])->name('profile.password');
});

// Cookies
Route::post('/cookies/accept', function () {
    session(['cookies_accepted' => true]);
    return response()->json(['success' => true]);
})->name('cookies.accept');


// ===========================================
// ROUTES STRIPE
//============================================

// Routes de paiement Stripe
Route::middleware(['auth'])->group(function () {
    Route::post('/payment/checkout', [PaymentController::class, 'checkout'])->name('payment.checkout');
    Route::get('/payment/success', [PaymentController::class, 'success'])->name('payment.success');
    Route::get('/payment/cancel', [PaymentController::class, 'cancel'])->name('payment.cancel');
});

// Webhook Stripe (doit être public)
Route::post('/stripe/webhook', [PaymentController::class, 'webhook'])->name('stripe.webhook');



// Temporary debug route for local testing of cart merge (remove after use)
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;
use App\Models\Vinyle;
use App\Models\User;

Route::get('/_debug/merge-cart-test', function () {
    if (!app()->environment('local')) {
        abort(404);
    }

    $source = request()->query('source', 'tst-session-xyz');

    // Create anonymous cart placeholder
    Cart::where('session_id', $source)->whereNull('user_id')->delete();
    $anon = Cart::create(['session_id' => $source, 'expires_at' => now()->addHours(2)]);

    $vin = Vinyle::where('quantite', '>', 0)->first();
    if (!$vin) {
        return response('NO_VIN', 500);
    }

    $anon->items()->create(['vinyle_id' => $vin->id, 'fond_id' => null, 'quantite' => 1, 'prix_unitaire' => $vin->prix]);

    $user = User::first();
    if (!$user) {
        return response('NO_USER', 500);
    }

    Auth::loginUsingId($user->id);

    $before = app(App\Services\CartService::class)->count();
    $merged = app(App\Services\CartService::class)->mergeAnonymousCart($source, $anon->id);
    $after = app(App\Services\CartService::class)->count();

    return response()->json([ 'source' => $source, 'anon_cart_id' => $anon->id, 'user_id' => $user->id, 'before' => $before, 'after' => $after, 'merged' => $merged ]);
});

require __DIR__ . '/auth.php';