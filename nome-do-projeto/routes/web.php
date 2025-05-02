<?php

require __DIR__.'/auth.php';

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaturaController;

// Página inicial redireciona para login
Route::get('/', function () {
    return redirect('/login');
});

// Rotas protegidas por autenticação
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD de Faturas
    Route::resource('faturas', FaturaController::class);
});

// Autenticação (Laravel Breeze, Jetstream, Fortify, etc.)
require __DIR__.'/auth.php';
