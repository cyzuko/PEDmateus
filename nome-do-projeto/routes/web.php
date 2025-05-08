<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FaturaController;

// Rotas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rotas protegidas
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    
    // Rotas para faturas (CRUD completo)
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/faturas/create', [FaturaController::class, 'create'])->name('faturas.create');
    Route::post('/faturas', [FaturaController::class, 'store'])->name('faturas.store');
    Route::get('/faturas/{id}', [FaturaController::class, 'show'])->name('faturas.show');
    Route::get('/faturas/{id}/edit', [FaturaController::class, 'edit'])->name('faturas.edit');
    Route::put('/faturas/{id}', [FaturaController::class, 'update'])->name('faturas.update');
    Route::delete('/faturas/{id}', [FaturaController::class, 'destroy'])->name('faturas.destroy');
});