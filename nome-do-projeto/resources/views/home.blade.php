@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card shadow-sm">

                <!-- 🔲 Cabeçalho -->
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-tachometer-alt me-2"></i>
                            Dashboard - Bem-vindo, {{ Auth::user()->name }}!
                        </h4>
                        <!-- Botões de ação rápida no cabeçalho -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('faturas.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Nova Fatura
                            </a>
                            <a href="{{ route('faturas.index') }}" class="btn btn-info btn-sm">
                                <i class="fas fa-list me-1"></i>
                                Ver Todas
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body">

                    <!-- ✅ Mensagens -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <!-- 📊 Cards de Estatísticas -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card border-0 bg-primary bg-opacity-10">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 text-primary">
                                                <i class="fas fa-file-invoice me-1"></i>
                                                Total de Faturas
                                            </h6>
                                            <h4 class="mb-0 text-primary">{{ $faturas->count() }}</h4>
                                        </div>
                                        <div class="text-primary opacity-75">
                                            <i class="fas fa-file-invoice fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-success bg-opacity-10">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 text-success">
                                                <i class="fas fa-euro-sign me-1"></i>
                                                Valor Total
                                            </h6>
                                            <h4 class="mb-0 text-success">€{{ number_format($faturas->sum('valor'), 2, ',', '.') }}</h4>
                                        </div>
                                        <div class="text-success opacity-75">
                                            <i class="fas fa-chart-line fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-info bg-opacity-10">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 text-info">
                                                <i class="fas fa-calendar me-1"></i>
                                                Este Mês
                                            </h6>
                                            <h4 class="mb-0 text-info">{{ $faturas->where('data', '>=', now()->startOfMonth())->count() }}</h4>
                                        </div>
                                        <div class="text-info opacity-75">
                                            <i class="fas fa-calendar-alt fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-0 bg-warning bg-opacity-10">
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <div>
                                            <h6 class="mb-1 text-warning">
                                                <i class="fas fa-building me-1"></i>
                                                Fornecedores
                                            </h6>
                                            <h4 class="mb-0 text-warning">{{ $faturas->pluck('fornecedor')->unique()->count() }}</h4>
                                        </div>
                                        <div class="text-warning opacity-75">
                                            <i class="fas fa-handshake fa-2x"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- 🧾 Seção de Últimas Faturas -->
                    @if($faturas->count() > 0)
                        <div class="row">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-header bg-transparent border-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">
                                                <i class="fas fa-history me-2"></i>
                                                As suas últimas faturas
                                            </h5>
                                            <a href="{{ route('faturas.index') }}" class="btn btn-outline-primary btn-sm">
                                                <i class="fas fa-arrow-right me-1"></i>
                                                Ver todas
                                            </a>
                                        </div>
                                    </div>
                                    <div class="card-body p-0">
                                        <div class="table-responsive">
                                            <table class="table table-hover table-striped mb-0">
                                                <thead style="background-color:rgb(42, 143, 225); color: white;">
                                                    <tr>
                                                        <th class="text-center">
                                                            <i class="fas fa-building"></i>
                                                            <span>Fornecedor</span>
                                                        </th>
                                                        <th class="text-center">
                                                            <i class="fas fa-id-card"></i>
                                                            <span>NIF</span>
                                                        </th>
                                                        <th class="text-center">
                                                            <i class="fas fa-calendar"></i>
                                                            <span>Data</span>
                                                        </th>
                                                        <th class="text-center">
                                                            <i class="fas fa-euro-sign"></i>
                                                            <span>Valor</span>
                                                        </th>
                                                        <th class="text-center">
                                                            <i class="fas fa-image"></i>
                                                            <span>Imagem</span>
                                                        </th>
                                                        <th class="text-center">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($faturas->take(5) as $fatura)
                                                        <tr>
                                                            <td class="align-middle">
                                                                <div class="fw-bold">{{ $fatura->fornecedor }}</div>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                @if($fatura->nif)
                                                                    <span class="badge bg-info">{{ $fatura->nif }}</span>
                                                                @else
                                                                    <span class="text-muted"><i class="fas fa-minus"></i></span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <span class="badge bg-secondary">
                                                                    {{ \Carbon\Carbon::parse($fatura->data)->format('d/m/Y') }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <span class="fw-bold text-success">
                                                                    €{{ number_format($fatura->valor, 2, ',', '.') }}
                                                                </span>
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                @if($fatura->imagem)
                                                                    <a href="{{ asset('storage/' . $fatura->imagem) }}" target="_blank" class="d-inline-block">
                                                                        <img src="{{ asset('storage/' . $fatura->imagem) }}"
                                                                             alt="Imagem da Fatura"
                                                                             class="img-thumbnail shadow-sm"
                                                                             style="width: 80px; height: 50px; object-fit: cover;">
                                                                    </a>
                                                                @else
                                                                    <span class="text-muted"><i class="fas fa-image-slash"></i></span>
                                                                @endif
                                                            </td>
                                                            <td class="text-center align-middle">
                                                                <div class="btn-group" role="group">
                                                                    <a href="{{ route('faturas.show', $fatura->id) }}"
                                                                       class="btn btn-sm btn-outline-info"
                                                                       title="Ver Detalhes">
                                                                        <i class="fas fa-eye"></i>
                                                                    </a>
                                                                    <a href="{{ route('faturas.edit', $fatura->id) }}"
                                                                       class="btn btn-sm btn-outline-primary"
                                                                       title="Editar">
                                                                        <i class="fas fa-edit"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Ações Rápidas -->
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="card border-0 bg-light">
                                    <div class="card-body text-center">
                                        <h5 class="mb-3">
                                            <i class="fas fa-bolt me-2"></i>
                                            Ações Rápidas
                                        </h5>
                                        <div class="d-flex justify-content-center gap-3 flex-wrap">
                                            <a href="{{ route('faturas.create') }}" class="btn btn-success btn-lg">
                                                <i class="fas fa-plus me-2"></i>
                                                Adicionar Fatura
                                            </a>
                                            <a href="{{ route('faturas.index') }}" class="btn btn-primary btn-lg">
                                                <i class="fas fa-list me-2"></i>
                                                Listar Faturas
                                            </a>
                                            @if(Route::has('estatisticas'))
                                            <a href="{{ route('estatisticas') }}" class="btn btn-info btn-lg">
                                                <i class="fas fa-chart-bar me-2"></i>
                                                Estatísticas
                                            @endif
                                            @if($faturas->count() > 0)
                                            <a href="{{ route('faturas.exportPdf') }}" target="_blank" class="btn btn-danger btn-lg">
                                                <i class="fas fa-file-pdf me-2"></i>
                                                Exportar PDF
                                            </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <!-- Estado vazio com design consistente -->
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-invoice fa-5x text-muted"></i>
                            </div>
                            <h4 class="text-muted">Bem-vindo ao Sistema de Faturas!</h4>
                            <p class="text-muted mb-4">Comece adicionando sua primeira fatura ao sistema.</p>
                            <a href="{{ route('faturas.create') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-plus me-2"></i>
                                Adicionar Primeira Fatura
                            </a>
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
// Função para mostrar toast (mesma da página de faturas)
function showToast(message, type = 'info', duration = 4000) {
    console.log(`Mostrando toast: ${message} (${type})`);
    
    const iconMap = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    
    const colorMap = {
        success: 'success',
        error: 'danger',
        warning: 'warning',
        info: 'primary'
    };
    
    const toastHtml = `
        <div class="toast align-items-center text-bg-${colorMap[type]} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${iconMap[type]} me-2"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    // Garantir que o container existe
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    // Adicionar toast
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    const toastElement = toastContainer.lastElementChild;
    
    // Mostrar toast
    try {
        const toast = new bootstrap.Toast(toastElement, { 
            delay: duration,
            autohide: true 
        });
        toast.show();
        
        // Remover elemento após esconder
        toastElement.addEventListener('hidden.bs.toast', () => {
            toastElement.remove();
        });
        
        console.log('Toast mostrado com sucesso');
    } catch (error) {
        console.error('Erro ao mostrar toast:', error);
    }
}

// Event listeners quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    console.log('Dashboard carregado, configurando event listeners...');
    
    // Toasts de sessão com delay
    setTimeout(() => {
        @if(session('success'))
            showToast('{{ addslashes(session('success')) }}', 'success', 5000);
        @endif

        @if(session('error'))
            showToast('{{ addslashes(session('error')) }}', 'error', 5000);
        @endif
    }, 100);
    
    console.log('Dashboard configurado com sucesso');
});

console.log('Script do dashboard carregado com sucesso');
</script>

<!-- CSS (mesmo estilo da página de faturas) -->
<style>
/* Estilos gerais da tabela */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
}

.btn-group .btn {
    margin: 0 1px;
}

.img-thumbnail {
    transition: transform 0.2s;
    border-radius: 5px;
}

.img-thumbnail:hover {
    transform: scale(1.1);
}

.card {
    border: none;
    border-radius: 10px;
}

.alert {
    border-radius: 8px;
}

.badge {
    font-size: 0.85em;
}

/* Estilos para os cards de estatísticas */
.bg-primary.bg-opacity-10 {
    background-color: rgba(13, 110, 253, 0.1) !important;
    border: 1px solid rgba(13, 110, 253, 0.2);
    border-radius: 10px;
}

.bg-success.bg-opacity-10 {
    background-color: rgba(25, 135, 84, 0.1) !important;
    border: 1px solid rgba(25, 135, 84, 0.2);
    border-radius: 10px;
}

.bg-info.bg-opacity-10 {
    background-color: rgba(13, 202, 240, 0.1) !important;
    border: 1px solid rgba(13, 202, 240, 0.2);
    border-radius: 10px;
}

.bg-warning.bg-opacity-10 {
    background-color: rgba(255, 193, 7, 0.1) !important;
    border: 1px solid rgba(255, 193, 7, 0.2);
    border-radius: 10px;
}

/* Estilos para os toasts */
.toast-container {
    z-index: 1055;
}

.toast {
    border-radius: 10px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
    min-width: 300px;
}

.toast-body {
    font-weight: 500;
    padding: 1rem;
}

/* Botões no cabeçalho */
.card-header .btn {
    border-width: 2px;
    font-weight: 600;
}

.card-header .btn-success {
    background-color: #198754;
    border-color: #ffffff;
}

.card-header .btn-info {
    background-color: #0dcaf0;
    border-color: #ffffff;
}

.card-header .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Hover effects para os cards de estatísticas */
.card.border-0[class*="bg-"]:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
}

/* Estilos para as ações rápidas */
.btn-lg {
    padding: 0.75rem 1.5rem;
    font-size: 1.1rem;
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn-lg:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 15px rgba(0,0,0,0.2);
}

/* Cores dos textos nos cards */
.text-primary {
    color: #0d6efd !important;
}

.text-success {
    color: #198754 !important;
}

.text-info {
    color: #0dcaf0 !important;
}

.text-warning {
    color: #ffc107 !important;
}

/* Animações */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeIn 0.6s ease-out;
}

/* Responsividade */
@media (max-width: 768px) {
    .d-flex.gap-3.flex-wrap {
        flex-direction: column;
        gap: 1rem !important;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .col-md-3 {
        margin-bottom: 1rem;
    }
}

/* Estilos para o card de últimas faturas */
.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    border-radius: 10px;
}

.card-header.bg-transparent {
    background-color: transparent !important;
    border-bottom: 1px solid #dee2e6;
}

/* Melhorar o espaçamento dos cards de estatísticas */
.row > [class*="col-"] {
    margin-bottom: 1rem;
}

/* Estilos para os ícones grandes */
.fa-2x {
    font-size: 2em !important;
}

.fa-5x {
    font-size: 5em !important;
}

/* Ajustes para o estado vazio */
.text-center.py-5 {
    padding: 3rem 0 !important;
}

/* Estilos específicos para as imagens menores na dashboard */
.img-thumbnail[style*="width: 80px"] {
    border: 2px solid #dee2e6;
}
/* Espaçamento adicional para hover nos botões */
.btn:hover {
    margin: 0.25rem;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

/* Garantir espaçamento consistente em todos os containers de botões */
.d-flex:has(.btn) {
    gap: 1rem;
}

.d-flex:has(.btn) > * {
    margin: 0.25rem;
}
</style>
@endsection