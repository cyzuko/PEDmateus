<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\FaturaController;

// Página inicial
Route::get('/', function () {
    return view('welcome');
});

// Rotas de Login
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login'); // Exibe o formulário de login
Route::post('login', [LoginController::class, 'login']); // Processa o login

// Rotas de Registro
Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register'); // Exibe o formulário de registro
Route::post('register', [RegisterController::class, 'register']); // Processa o registro

// Logout
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

// Rotas protegidas que necessitam de autenticação
Route::middleware(['auth'])->group(function () {
    // Rota de Dashboard ou página protegida
    Route::get('/dashboard', function () {
        return view('dashboard'); // Cria a view "dashboard.blade.php"
    })->name('dashboard');  // Defina o nome da rota "dashboard"

    // Outras rotas protegidas
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/faturas/create', [FaturaController::class, 'create'])->name('faturas.create');
    Route::post('/faturas', [FaturaController::class, 'store'])->name('faturas.store');
});
