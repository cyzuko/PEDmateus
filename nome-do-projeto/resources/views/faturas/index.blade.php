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
                            <i class="fas fa-file-invoice-dollar me-2"></i>
                            Gestão de Faturas
                        </h4>
                        <!-- Botões movidos para o cabeçalho -->
                        <div class="d-flex gap-2">
                            <a href="{{ route('faturas.create') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-plus me-1"></i>
                                Nova Fatura
                            </a>
                            
<a href="{{ route('faturas.create-multiple') }}" class="btn btn-warning btn-sm">
    <i class="fas fa-upload me-1"></i>
    Múltiplas Faturas
</a>
                            <a href="{{ route('faturas.exportPdf') }}" target="_blank" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf me-1"></i>
                                Exportar PDF
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

                    <!-- 🔍 Barra de Pesquisa -->
                    @if($faturas->count() > 0)
                        <div class="row mb-4">
                            <div class="col-md-8">
                                <div class="card border-0 bg-light">
                                    <div class="card-body p-3">
                                        <form method="GET" action="{{ route('faturas.index') }}" id="searchForm">
                                            <div class="row g-2">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white border-end-0">
                                                            <i class="fas fa-search text-muted"></i>
                                                        </span>
                                                        <input type="text" 
                                                               class="form-control border-start-0" 
                                                               name="search" 
                                                               id="searchInput"
                                                               value="{{ request('search') }}" 
                                                               placeholder="Pesquisar por fornecedor ou NIF..."
                                                               autocomplete="off">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="date" 
                                                           class="form-control" 
                                                           name="data_inicio" 
                                                           id="dataInicio"
                                                           value="{{ request('data_inicio') }}" 
                                                           title="Data inicial">
                                                </div>
                                                <div class="col-md-3">
                                                    <input type="date" 
                                                           class="form-control" 
                                                           name="data_fim" 
                                                           id="dataFim"
                                                           value="{{ request('data_fim') }}" 
                                                           title="Data final">
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-md-6">
                                                    <div class="input-group">
                                                        <span class="input-group-text bg-white">
                                                            <i class="fas fa-euro-sign text-muted"></i>
                                                        </span>
                                                        <input type="number" 
                                                               class="form-control" 
                                                               name="valor_min" 
                                                               id="valorMin"
                                                               value="{{ request('valor_min') }}" 
                                                               placeholder="Valor mínimo"
                                                               step="0.01">
                                                        <input type="number" 
                                                               class="form-control" 
                                                               name="valor_max" 
                                                               id="valorMax"
                                                               value="{{ request('valor_max') }}" 
                                                               placeholder="Valor máximo"
                                                               step="0.01">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="d-flex gap-2">
                                                        <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                                            <i class="fas fa-search me-1"></i>
                                                            Pesquisar
                                                        </button>
                                                        <a href="{{ route('faturas.index') }}" class="btn btn-outline-secondary btn-sm">
                                                            <i class="fas fa-times me-1"></i>
                                                            Limpar
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Manter parâmetros de ordenação -->
                                            @if(request('sort'))
                                                <input type="hidden" name="sort" value="{{ request('sort') }}">
                                            @endif
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card border-0 bg-info bg-opacity-10">
                                    <div class="card-body p-3">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1 text-info">
                                                    <i class="fas fa-list-ol me-1"></i>
                                                    Total de Registos
                                                </h6>
                                                <h4 class="mb-0 text-info">{{ $faturas->total() ?? $faturas->count() }}</h4>
                                            </div>
                                            <div class="text-info opacity-75">
                                                <i class="fas fa-file-invoice fa-2x"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- 📊 Resultados da pesquisa -->
                        @if(request()->hasAny(['search', 'data_inicio', 'data_fim', 'valor_min', 'valor_max']))
                            <div class="row mb-3">
                                <div class="col-12">
                                    <div class="alert alert-info border-0 bg-info bg-opacity-10">
                                        <div class="d-flex align-items-center justify-content-between">
                                            <div>
                                                <i class="fas fa-filter me-2"></i>
                                                <strong>Filtros ativos:</strong>
                                                @if(request('search'))
                                                    <span class="badge bg-primary ms-1">Texto: "{{ request('search') }}"</span>
                                                @endif
                                                @if(request('data_inicio'))
                                                    <span class="badge bg-primary ms-1">De: {{ \Carbon\Carbon::parse(request('data_inicio'))->format('d/m/Y') }}</span>
                                                @endif
                                                @if(request('data_fim'))
                                                    <span class="badge bg-primary ms-1">Até: {{ \Carbon\Carbon::parse(request('data_fim'))->format('d/m/Y') }}</span>
                                                @endif
                                                @if(request('valor_min'))
                                                    <span class="badge bg-success ms-1">Min: €{{ number_format(request('valor_min'), 2, ',', '.') }}</span>
                                                @endif
                                                @if(request('valor_max'))
                                                    <span class="badge bg-success ms-1">Max: €{{ number_format(request('valor_max'), 2, ',', '.') }}</span>
                                                @endif
                                            </div>
                                            <small class="text-muted">{{ $faturas->total() ?? $faturas->count() }} resultado(s)</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif

                    <!-- 🧾 Tabela -->
                    @if($faturas->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover table-striped">
                                <thead style="background-color:rgb(42, 143, 225); color: white;">
                                    <tr>
                                        @php
                                            // Colunas com ícones e chaves de ordenação
                                            $columns = [
                                                'fornecedor' => ['label' => 'Fornecedor', 'icon' => 'building'],
                                                'nif' => ['label' => 'NIF', 'icon' => 'id-card'],
                                                'data' => ['label' => 'Data', 'icon' => 'calendar'],
                                                'valor' => ['label' => 'Valor', 'icon' => 'euro-sign'],
                                                'imagem' => ['label' => 'Imagem', 'icon' => 'image'],
                                                'acoes' => ['label' => 'Ações', 'icon' => 'cogs'],
                                            ];
                                        @endphp
                                        @foreach($columns as $key => $col)
                                            @if($key === 'fornecedor' || $key === 'data' || $key === 'valor')
                                                @php
                                                    $currentSort = request('sort');
                                                    $ascKey = $key . '_asc';
                                                    $descKey = $key . '_desc';
                                                    $dir = null;
                                                    if ($currentSort === $ascKey) $dir = 'asc';
                                                    elseif ($currentSort === $descKey) $dir = 'desc';
                                                    $nextDir = $dir === 'asc' ? 'desc' : 'asc';
                                                    
                                                    // Construir URL mantendo parâmetros de pesquisa
                                                    $sortParams = request()->except(['sort', 'page']);
                                                    $sortParams['sort'] = $key . '_' . $nextDir;
                                                @endphp
                                                <th class="text-center" style="cursor:pointer;">
                                                    <a href="{{ request()->fullUrlWithQuery($sortParams) }}"
                                                    class="text-white text-decoration-none d-flex align-items-center justify-content-center gap-1">
                                                        <i class="fas fa-{{ $col['icon'] }}"></i>
                                                        <span>{{ $col['label'] }}</span>
                                                        {{-- Setas sempre visíveis --}}
                                                        <i class="fas fa-sort-up" style="opacity: {{ $dir === 'asc' ? '1' : '0.3' }}; font-size: 0.7em;"></i>
                                                        <i class="fas fa-sort-down" style="opacity: {{ $dir === 'desc' ? '1' : '0.3' }}; font-size: 0.7em;"></i>
                                                    </a>
                                                </th>
                                            @elseif($key === 'acoes')
                                                <th class="text-center">{{ $col['label'] }}</th>
                                            @else
                                                {{-- NIF e Imagem: só texto e ícone, sem link e setas --}}
                                                <th class="text-center" style="cursor: default;">
                                                    <i class="fas fa-{{ $col['icon'] }}"></i>
                                                    <span>{{ $col['label'] }}</span>
                                                </th>
                                            @endif
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($faturas as $fatura)
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
                                                             style="width: 100px; height: 70px; object-fit: cover;">
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
                                                    <form action="{{ route('faturas.destroy', $fatura->id) }}"
                                                          method="POST"
                                                          class="d-inline delete-form"
                                                          data-fornecedor="{{ $fatura->fornecedor }}">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="button"
                                                                class="btn btn-sm btn-outline-danger delete-btn"
                                                                title="Remover">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- 📄 Paginação -->
                        @if(method_exists($faturas, 'links'))
                            <div class="d-flex justify-content-center mt-4">
                                {{ $faturas->appends(request()->except('page'))->links() }}
                            </div>
                        @endif
                    @else
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="fas fa-file-invoice fa-5x text-muted"></i>
                            </div>
                            @if(request()->hasAny(['search', 'data_inicio', 'data_fim', 'valor_min', 'valor_max']))
                                <h4 class="text-muted">Nenhuma fatura encontrada</h4>
                                <p class="text-muted mb-4">Tente ajustar os critérios de pesquisa ou remover os filtros.</p>
                                <a href="{{ route('faturas.index') }}" class="btn btn-primary btn-lg me-2">
                                    <i class="fas fa-times me-2"></i>
                                    Limpar Filtros
                                </a>
                            @else
                                <h4 class="text-muted">Nenhuma fatura encontrada</h4>
                                <p class="text-muted mb-4">Comece adicionando sua primeira fatura ao sistema.</p>
                            @endif
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

<!-- JAVASCRIPT CORRIGIDO - FORA DO LOOP -->
<script>
// Função para mostrar toast
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

// Função para mostrar modal de confirmação
function showDeleteModal(form, fornecedor) {
    // Remover modal anterior se existir
    const existingModal = document.getElementById('deleteModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    const modalHtml = `
        <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content border-0 shadow">
                    <div class="modal-header bg-danger text-white border-0">
                        <h5 class="modal-title">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Confirmar Eliminação
                        </h5>
                    </div>
                    <div class="modal-body p-4">
                        <div class="text-center mb-4">
                            <i class="fas fa-trash-alt text-danger" style="font-size: 3rem;"></i>
                        </div>
                        <p class="text-center mb-3">
                            <strong>Tem certeza que deseja eliminar a fatura de:</strong>
                        </p>
                        <div class="alert alert-light border text-center">
                            <strong>${fornecedor}</strong>
                        </div>
                        <p class="text-muted text-center small mb-0">
                            <i class="fas fa-warning me-1"></i>
                            Esta ação não pode ser desfeita!
                        </p>
                    </div>
                    <div class="modal-footer border-0 justify-content-center gap-3">
                        <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" id="cancelBtn">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger btn-lg px-4" id="confirmBtn">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Adicionar modal ao body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
    
    // Event listener para cancelar
    document.getElementById('cancelBtn').addEventListener('click', function() {
        console.log('Eliminação cancelada pelo usuário');
        showToast('Eliminação cancelada', 'info', 3000);
        modal.hide();
    });
    
    // Event listener para confirmar
    document.getElementById('confirmBtn').addEventListener('click', function() {
        console.log('Eliminação confirmada pelo usuário');
        showToast('A eliminar fatura...', 'warning', 2000);
        modal.hide();
        
        // Submeter formulário após pequeno delay
        setTimeout(() => {
            console.log('Submetendo formulário...');
            form.submit();
        }, 300);
    });
    
    // Limpar modal do DOM quando fechar
    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
}

// Função para pesquisa em tempo real
function setupLiveSearch() {
    const searchInput = document.getElementById('searchInput');
    const searchForm = document.getElementById('searchForm');
    let searchTimeout;
    
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            searchTimeout = setTimeout(() => {
                if (this.value.length >= 2 || this.value.length === 0) {
                    searchForm.submit();
                }
            }, 500); // Pesquisar após 500ms de pausa na digitação
        });
    }
}

// Event listeners quando o DOM estiver carregado
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM carregado, configurando event listeners...');
    
    // Event listener para todos os botões de eliminar
    const deleteButtons = document.querySelectorAll('.delete-btn');
    deleteButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Botão eliminar clicado');
            
            const form = this.closest('.delete-form');
            const fornecedor = form.getAttribute('data-fornecedor');
            
            showDeleteModal(form, fornecedor);
        });
    });
    
    // Configurar pesquisa em tempo real
    setupLiveSearch();
    
    // Toasts de sessão com delay
    setTimeout(() => {
        @if(session('success'))
            showToast('{{ addslashes(session('success')) }}', 'success', 5000);
        @endif

        @if(session('error'))
            showToast('{{ addslashes(session('error')) }}', 'error', 5000);
        @endif
    }, 100);
    
    console.log('Event listeners configurados com sucesso');
});

// Função de teste
function testToast() {
    showToast('Toast de teste funcionando!', 'info', 3000);
}

console.log('Script de faturas carregado com sucesso');
</script>

<!-- CSS COMPLETO -->
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

.table thead th a {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    justify-content: center;
}

.table thead th a .fa-sort {
    margin-left: 6px;
}

thead th a {
    cursor: pointer;
}

thead th a i {
    transition: opacity 0.3s ease;
}

/* Estilos para a barra de pesquisa */
.input-group-text {
    border-right: none;
    background-color: #f8f9fa;
}

.input-group .form-control {
    border-left: none;
}

.input-group .form-control:focus {
    border-color: #86b7fe;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    border-left: none;
}

.input-group-text + .form-control:focus {
    border-left: 1px solid #86b7fe;
}

/* Card de pesquisa */
.bg-light {
    background-color: #f8f9fa !important;
    border: 1px solid #e9ecef;
    border-radius: 10px;
}

/* Card de estatísticas */
.bg-info.bg-opacity-10 {
    background-color: rgba(13, 202, 240, 0.1) !important;
    border: 1px solid rgba(13, 202, 240, 0.2);
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

/* Estilos para o modal de confirmação */
.modal-content {
    border-radius: 15px;
    overflow: hidden;
}

.modal-header.bg-danger {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%) !important;
}

.modal-body {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
}

.modal-footer {
    background: #f8f9fa;
    padding: 1.5rem;
}

/* Animação para o ícone de lixo no modal */
.modal-body .fa-trash-alt {
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    25% { transform: translateX(-5px); }
    75% { transform: translateX(5px); }
}

/* Hover effects para os botões do modal */
.modal-footer .btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
}

/* Estilos dos cards de informação */
.bg-light.rounded-3 {
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.bg-light.rounded-3:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

.text-success {
    color: #28a745 !important;
}

.btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
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

.card-header .btn-danger {
    background-color: #dc3545;
    border-color: #ffffff;
}

.card-header .btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}

/* Espaçamento entre seções */
.row.g-3 > * {
    padding: 0.75rem;
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

/* Estilos para os cards de informação */
.bg-light.rounded-3 {
    border: 1px solid rgba(0,0,0,0.05);
    transition: all 0.2s ease;
}

.bg-light.rounded-3:hover {
    border-color: rgba(13, 110, 253, 0.2);
    box-shadow: 0 2px 8px rgba(13, 110, 253, 0.1);
}

/* Estilo especial para o valor */
.text-success {
    color: #28a745 !important;
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

/* Estilos específicos para a pesquisa */
.form-control:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}

/* Badges nos filtros ativos */
.badge {
    font-size: 0.75em;
    padding: 0.375em 0.75em;
}

/* Responsividade para a barra de pesquisa */
@media (max-width: 768px) {
    .col-md-8 {
        margin-bottom: 1rem;
    }
    
    .input-group {
        margin-bottom: 0.5rem;
    }
    
    .d-flex.gap-2 {
        flex-direction: column;
        gap: 0.5rem !important;
    }
}

/* Melhorias visuais nos campos de pesquisa */
.input-group .form-control::placeholder {
    color: #6c757d;
    font-style: italic;
}

.card.border-0.bg-light {
    transition: all 0.3s ease;
}

.card.border-0.bg-light:hover {
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}

/* Estilo do contador de resultados */
.text-info {
    color: #0dcaf0 !important;
}

.fa-2x {
    font-size: 2em !important;
}

/* Animação sutil para os badges */
.badge {
    transition: all 0.2s ease;
}

.badge:hover {
    transform: scale(1.05);
}

/* Melhorar o visual do alerta de filtros ativos */
.alert-info.border-0.bg-info.bg-opacity-10 {
    border: 1px solid rgba(13, 202, 240, 0.3) !important;
    background-color: rgba(13, 202, 240, 0.05) !important;
}
</style>
@endsection