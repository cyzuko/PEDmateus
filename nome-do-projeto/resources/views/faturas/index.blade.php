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
                                            @endphp
                                            <th class="text-center" style="cursor:pointer;">
                                                <a href="{{ request()->fullUrlWithQuery(['sort' => $key . '_' . $nextDir]) }}"
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
      class="d-inline"
      onsubmit="return confirmDelete(this, '{{ addslashes($fatura->fornecedor) }}');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit"
                                                                class="btn btn-sm btn-outline-danger"
                                                                title="Remover">
                                                            <i class="fas fa-trash"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                        <!-- Adicione este JavaScript no final da sua página, antes de fechar </body> -->
<script>
// Função para mostrar toast
function showToast(message, type = 'info', duration = 4000) {
    // Tipos: success, error, warning, info
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
    
    // Criar ou encontrar container de toasts
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    // Adicionar toast ao container
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    // Mostrar toast
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: duration });
    toast.show();
    
    // Remover do DOM após esconder
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}

// Função melhorada para confirmação de eliminação
function confirmDelete(form, fornecedor) {
    // Modal de confirmação personalizado
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
                        <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times me-2"></i>Cancelar
                        </button>
                        <button type="button" class="btn btn-danger btn-lg px-4" id="confirmDeleteBtn">
                            <i class="fas fa-trash me-2"></i>Eliminar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remover modal anterior se existir
    const existingModal = document.getElementById('deleteModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Adicionar modal ao body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Mostrar modal
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
    
    // Confirmar eliminação
    document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
        modal.hide();
        
        // Toast de processamento
        showToast('A eliminar fatura...', 'warning', 2000);
        
        // Submeter formulário após pequeno delay
        setTimeout(() => {
            form.submit();
        }, 500);
    });
    
    // Limpar modal do DOM quando fechar
    document.getElementById('deleteModal').addEventListener('hidden.bs.modal', function() {
        this.remove();
    });
    
    return false; // Prevenir submissão imediata
}

// Mostrar toast de sucesso se houver mensagem de sessão
@if(session('success'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('success') }}', 'success', 5000);
    });
@endif

// Mostrar toast de erro se houver mensagem de erro
@if(session('error'))
    document.addEventListener('DOMContentLoaded', function() {
        showToast('{{ session('error') }}', 'error', 5000);
    });
@endif
</script>

<!-- SUBSTITUA o formulário de eliminação no seu código por este: -->
<!-- 
<form action="{{ route('faturas.destroy', $fatura->id) }}"
      method="POST"
      class="d-inline"
      onsubmit="return confirmDelete(this, '{{ addslashes($fatura->fornecedor) }}');">
    @csrf
    @method('DELETE')
    <button type="submit"
            class="btn btn-sm btn-outline-danger"
            title="Remover">
        <i class="fas fa-trash"></i>
    </button>
</form>
-->

<style>
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
</style>
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
                            <h4 class="text-muted">Nenhuma fatura encontrada</h4>
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

<style>
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
    gap: 6px; /* espaço entre texto e ícone */
    justify-content: center;
}

.table thead th a .fa-sort {
    margin-left: 6px; /* separa mais a seta da palavra */
}

/* Cabeçalho - cursor e setas */
thead th a {
    cursor: pointer;
}

thead th a i {
    transition: opacity 0.3s ease;
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

/* Botões no cabeçalho com contraste adequado */
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
</style>
@endsection