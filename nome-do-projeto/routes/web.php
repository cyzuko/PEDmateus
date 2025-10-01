<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExplicacaoController;

// === ROTAS PÚBLICAS ===

// Página inicial pública (sem autenticação)
Route::get('/', [HomeController::class, 'publicHome'])->name('public.home');

// Rotas de autenticação
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// === ROTAS DE ADMINISTRADOR ===
Route::middleware(['auth'])->group(function () {
    // Rota de redirecionamento para /admin
    Route::get('/admin', function () {
        return redirect()->route('admin.dashboard');
    })->name('admin');
    
    // Dashboard principal do admin
    Route::get('/admin/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Gestão de explicações
    Route::get('/admin/explicacoes', [AdminController::class, 'explicacoes'])->name('admin.explicacoes.index');
    Route::get('/admin/explicacoes/{id}', [AdminController::class, 'explicacaoShow'])->name('admin.explicacoes.show');
    
    // Aprovações individuais
    Route::patch('/admin/explicacoes/{id}/aprovar', [AdminController::class, 'aprovarExplicacao'])->name('admin.explicacoes.aprovar');
    Route::patch('/admin/explicacoes/{id}/rejeitar', [AdminController::class, 'rejeitarExplicacao'])->name('admin.explicacoes.rejeitar');
    Route::patch('/admin/explicacoes/{id}/reverter', [AdminController::class, 'reverterAprovacao'])->name('admin.explicacoes.reverter');
    
    // Aprovação/rejeição múltipla
    Route::post('/admin/explicacoes/aprovar-multiplas', [AdminController::class, 'aprovarMultiplas'])->name('admin.explicacoes.aprovar-multiplas');
    Route::post('/admin/explicacoes/rejeitar-multiplas', [AdminController::class, 'rejeitarMultiplas'])->name('admin.explicacoes.rejeitar-multiplas');
    
    // Relatórios e estatísticas
    Route::get('/admin/relatorio-aprovacoes', [AdminController::class, 'relatorioAprovacoes'])->name('admin.relatorio-aprovacoes');
    Route::get('/admin/exportar-relatorio', [AdminController::class, 'exportarRelatorio'])->name('admin.exportar-relatorio');
    Route::get('/admin/historico-acoes', [AdminController::class, 'historicoAcoes'])->name('admin.historico-acoes');
    
    // APIs para funcionalidades em tempo real
    Route::get('/admin/api/explicacoes-pendentes', [AdminController::class, 'explicacoesPendentesCount'])->name('admin.api.explicacoes-pendentes');
    Route::get('/admin/api/estatisticas-ao-vivo', [AdminController::class, 'estatisticasAoVivo'])->name('admin.api.estatisticas-ao-vivo');
    Route::post('/admin/api/buscar-explicacoes', [AdminController::class, 'buscarExplicacoes'])->name('admin.api.buscar-explicacoes');
});

// === ROTAS AUTENTICADAS ===
Route::middleware(['auth'])->group(function () {
    // Dashboard/Home autenticado (diferente da página inicial pública)
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Autenticação
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    
    // === ROTAS PARA FATURAS (DESATIVADAS - 01/10/2025) ===
    /*
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
    */
    
    // === ROTAS PARA EXPLICAÇÕES ===
    
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