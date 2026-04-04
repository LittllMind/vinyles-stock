<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ModeMarcheApiController;
use App\Http\Controllers\Api\NewsletterController;
use App\Http\Controllers\Admin\NewsletterAdminController;

/*
|--------------------------------------------------------------------------
| API Routes pour Mode Marché
|--------------------------------------------------------------------------
|
| Ces routes retournent TOUJOURS du JSON. Elles sont séparées des routes
| web (Blade) pour éviter la confusion API/View.
|
| Middleware: 'auth:sanctum' + 'role:admin,employe'
|
*/

Route::middleware(['auth', 'role:admin,employe'])->prefix('marche')->name('api.marche.')->group(function () {
    // Liste des ventes du jour
    Route::get('/ventes-jour', [ModeMarcheApiController::class, 'ventesJour'])->name('ventes-jour');
    
    // Annuler une vente
    Route::post('/{order}/cancel', [ModeMarcheApiController::class, 'cancel'])->name('cancel');
    
    // Export CSV
    Route::get('/export', [ModeMarcheApiController::class, 'export'])->name('export');
});

// ============================================
// ROUTES API NEWSLETTER (Public)
// ============================================
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])->name('api.newsletter.subscribe');

// ============================================
// ROUTES API NEWSLETTER (Admin)
// ============================================
Route::middleware(['auth', 'role:admin'])->prefix('admin/newsletter')->group(function () {
    Route::get('/subscribers', [NewsletterAdminController::class, 'index']);
    Route::delete('/subscribers/{subscriber}', [NewsletterAdminController::class, 'destroy']);
    Route::get('/export', [NewsletterAdminController::class, 'export']);
});
