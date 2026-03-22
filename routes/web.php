<?php

use App\Http\Controllers\BougieController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Routes admin pour les bougies (T2.3)
Route::get('/bougies', [BougieController::class, 'index'])->name('admin.bougies.index');
Route::get('/bougies/create', [BougieController::class, 'create'])->name('admin.bougies.create');
Route::post('/bougies', [BougieController::class, 'store'])->name('admin.bougies.store');
Route::get('/bougies/{bougie}', [BougieController::class, 'show'])->name('admin.bougies.show');
Route::get('/bougies/{bougie}/edit', [BougieController::class, 'edit'])->name('admin.bougies.edit');
Route::put('/bougies/{bougie}', [BougieController::class, 'update'])->name('admin.bougies.update');
Route::delete('/bougies/{bougie}', [BougieController::class, 'destroy'])->name('admin.bougies.destroy');
