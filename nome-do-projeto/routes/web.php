<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExplicacaoController;

// Rotas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Rotas de administrador
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
});

// Rotas autenticadas
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    
    // Rotas para faturas (CRUD completo)
    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::get('/faturas/create', [FaturaController::class, 'create'])->name('faturas.create');
    Route::post('/faturas', [FaturaController::class, 'store'])->name('faturas.store');
    
    // Rotas para upload múltiplo de faturas
    Route::get('/faturas/create-multiple', [FaturaController::class, 'createMultiple'])->name('faturas.create-multiple');
    Route::post('/faturas/store-multiple', [FaturaController::class, 'storeMultiple'])->name('faturas.store-multiple');
    
    // Coloque a rota estática primeiro para evitar conflito
    Route::get('/faturas/export-pdf', [FaturaController::class, 'exportPdf'])->name('faturas.exportPdf');
    
    // Rotas que usam parâmetro id ficam depois
    Route::get('/faturas/{id}', [FaturaController::class, 'show'])->name('faturas.show');
    Route::get('/faturas/{id}/edit', [FaturaController::class, 'edit'])->name('faturas.edit');
    Route::put('/faturas/{id}', [FaturaController::class, 'update'])->name('faturas.update');
    Route::delete('/faturas/{id}', [FaturaController::class, 'destroy'])->name('faturas.destroy');
    
    // === NOVAS ROTAS PARA EXPLICAÇÕES ===
    
    // Listar todos os horários de explicações
    Route::get('/explicacoes', [ExplicacaoController::class, 'index'])->name('explicacoes.index');
    
    // Formulário para criar novo horário
    Route::get('/explicacoes/create', [ExplicacaoController::class, 'create'])->name('explicacoes.create');
    
    // Salvar novo horário
    Route::post('/explicacoes', [ExplicacaoController::class, 'store'])->name('explicacoes.store');
    
    // Rotas estáticas primeiro (para evitar conflitos)
    Route::get('/explicacoes/calendario', [ExplicacaoController::class, 'calendario'])->name('explicacoes.calendario');
    Route::get('/explicacoes/disponibilidade', [ExplicacaoController::class, 'disponibilidade'])->name('explicacoes.disponibilidade');
    Route::post('/explicacoes/definir-disponibilidade', [ExplicacaoController::class, 'definirDisponibilidade'])->name('explicacoes.definir-disponibilidade');
    
    // Visualizar detalhes de um horário específico
    Route::get('/explicacoes/{id}', [ExplicacaoController::class, 'show'])->name('explicacoes.show');
    
    // Formulário para editar horário
    Route::get('/explicacoes/{id}/edit', [ExplicacaoController::class, 'edit'])->name('explicacoes.edit');
    
    // Atualizar horário
    Route::put('/explicacoes/{id}', [ExplicacaoController::class, 'update'])->name('explicacoes.update');
    
    // Eliminar horário
    Route::delete('/explicacoes/{id}', [ExplicacaoController::class, 'destroy'])->name('explicacoes.destroy');
    
    // Confirmar/cancelar explicação
    Route::patch('/explicacoes/{id}/confirmar', [ExplicacaoController::class, 'confirmar'])->name('explicacoes.confirmar');
    Route::patch('/explicacoes/{id}/cancelar', [ExplicacaoController::class, 'cancelar'])->name('explicacoes.cancelar');
    
    // Marcar como concluída
    Route::patch('/explicacoes/{id}/concluir', [ExplicacaoController::class, 'concluir'])->name('explicacoes.concluir');
    
    // === FIM DAS ROTAS DE EXPLICAÇÕES ===
    
    // Estatísticas
    Route::get('/estatisticas', [App\Http\Controllers\EstatisticasController::class, 'index'])
        ->name('estatisticas')
        ->middleware('auth');
});