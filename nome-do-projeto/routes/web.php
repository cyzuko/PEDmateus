<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ExplicacaoController;

// === ROTAS PÚBLICAS ===
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
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }
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
    
    // Gestão de Disciplinas (Admin)
    Route::get('/disciplinas', [App\Http\Controllers\DisciplinaController::class, 'index'])->name('disciplinas.index');
    Route::post('/disciplinas', [App\Http\Controllers\DisciplinaController::class, 'store'])->name('disciplinas.store');
    Route::put('/disciplinas/{disciplina}', [App\Http\Controllers\DisciplinaController::class, 'update'])->name('disciplinas.update');
    Route::patch('/disciplinas/{disciplina}/toggle', [App\Http\Controllers\DisciplinaController::class, 'toggleAtiva'])->name('disciplinas.toggle');
    Route::delete('/disciplinas/{disciplina}', [App\Http\Controllers\DisciplinaController::class, 'destroy'])->name('disciplinas.destroy');
    
    // APIs para funcionalidades em tempo real
    Route::get('/admin/api/explicacoes-pendentes', [AdminController::class, 'explicacoesPendentesCount'])->name('admin.api.explicacoes-pendentes');
    Route::get('/admin/api/estatisticas-ao-vivo', [AdminController::class, 'estatisticasAoVivo'])->name('admin.api.estatisticas-ao-vivo');
    Route::post('/admin/api/buscar-explicacoes', [AdminController::class, 'buscarExplicacoes'])->name('admin.api.buscar-explicacoes');
    
    // === ROTAS DE GRUPOS E MENSAGENS (ADMIN) - SEM MIDDLEWARE SEPARADO ===
    Route::prefix('admin')->group(function () {
        Route::get('/grupos', [App\Http\Controllers\Admin\GrupoController::class, 'index'])->name('admin.grupos.index');
        Route::get('/grupos/create', [App\Http\Controllers\Admin\GrupoController::class, 'create'])->name('admin.grupos.create');
        Route::post('/grupos', [App\Http\Controllers\Admin\GrupoController::class, 'store'])->name('admin.grupos.store');
        Route::get('/grupos/{grupo}/edit', [App\Http\Controllers\Admin\GrupoController::class, 'edit'])->name('admin.grupos.edit');
        Route::put('/grupos/{grupo}', [App\Http\Controllers\Admin\GrupoController::class, 'update'])->name('admin.grupos.update');
        Route::delete('/grupos/{grupo}', [App\Http\Controllers\Admin\GrupoController::class, 'destroy'])->name('admin.grupos.destroy');
        Route::patch('/grupos/{grupo}/toggle-ativo', [App\Http\Controllers\Admin\GrupoController::class, 'toggleAtivo'])->name('admin.grupos.toggle-ativo');
    });
    
    // === ROTAS DE MENSAGENS (TODOS OS UTILIZADORES) ===
    Route::prefix('mensagens')->group(function () {
        Route::get('/', [App\Http\Controllers\MensagemController::class, 'index'])->name('mensagens.index');
        Route::get('/{grupo}', [App\Http\Controllers\MensagemController::class, 'show'])->name('mensagens.show');
        Route::post('/{grupo}', [App\Http\Controllers\MensagemController::class, 'store'])->name('mensagens.store');
        Route::put('/{mensagem}', [App\Http\Controllers\MensagemController::class, 'update'])->name('mensagens.update');
        Route::delete('/{mensagem}', [App\Http\Controllers\MensagemController::class, 'destroy'])->name('mensagens.destroy');
        Route::get('/{grupo}/carregar-novas', [App\Http\Controllers\MensagemController::class, 'carregarNovas'])->name('mensagens.carregar-novas');
    });
});

// === ROTAS AUTENTICADAS ===
Route::middleware(['auth'])->group(function () {
    // Dashboard/Home autenticado
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Autenticação
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/change-password', [AuthController::class, 'showChangePasswordForm'])->name('password.change');
    Route::post('/change-password', [AuthController::class, 'changePassword'])->name('password.update');
    
    // === ROTAS PARA EXPLICAÇÕES ===
    Route::get('/explicacoes', [ExplicacaoController::class, 'index'])->name('explicacoes.index');
    Route::get('/explicacoes/create', [ExplicacaoController::class, 'create'])->name('explicacoes.create');
    Route::post('/explicacoes', [ExplicacaoController::class, 'store'])->name('explicacoes.store');
    Route::get('/explicacoes/calendario', [ExplicacaoController::class, 'calendario'])->name('explicacoes.calendario');
    Route::get('/explicacoes/disponibilidade', [ExplicacaoController::class, 'disponibilidade'])->name('explicacoes.disponibilidade');
    Route::post('/explicacoes/definir-disponibilidade', [ExplicacaoController::class, 'definirDisponibilidade'])->name('explicacoes.definir-disponibilidade');
    Route::get('/explicacoes/{id}', [ExplicacaoController::class, 'show'])->name('explicacoes.show');
    Route::get('/explicacoes/{id}/edit', [ExplicacaoController::class, 'edit'])->name('explicacoes.edit');
    Route::put('/explicacoes/{id}', [ExplicacaoController::class, 'update'])->name('explicacoes.update');
    Route::delete('/explicacoes/{id}', [ExplicacaoController::class, 'destroy'])->name('explicacoes.destroy');
    Route::patch('/explicacoes/{id}/confirmar', [ExplicacaoController::class, 'confirmar'])->name('explicacoes.confirmar');
    Route::patch('/explicacoes/{id}/cancelar', [ExplicacaoController::class, 'cancelar'])->name('explicacoes.cancelar');
    Route::patch('/explicacoes/{id}/concluir', [ExplicacaoController::class, 'concluir'])->name('explicacoes.concluir');
    
    // Estatísticas
    Route::get('/estatisticas', [App\Http\Controllers\EstatisticasController::class, 'index'])->name('estatisticas');
});