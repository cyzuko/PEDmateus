@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10 col-xl-8">
            <!-- Header Card -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h2 class="mb-1 fw-bold text-primary">
                                <i class="fas fa-file-invoice me-3"></i>Detalhes da Fatura
                            </h2>
                            <p class="text-muted mb-0">Detalhes completos da fatura</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="fas fa-eye text-primary" style="font-size: 3rem; opacity: 0.1;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensagem de sucesso -->
            @if(session('success'))
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="alert alert-success border-0 shadow-sm mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-check-circle text-success me-4" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Sucesso!</h6>
                                    <p class="mb-0">{{ session('success') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Informações Básicas -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-info-circle text-primary me-4"></i>Informações Básicas
                    </h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-4">
                        <!-- Fornecedor -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-building text-primary me-4"></i>
                                    <small class="text-muted fw-semibold text-uppercase">Fornecedor</small>
                                </div>
                                <div class="fs-5 fw-bold text-dark">{{ $fatura->fornecedor }}</div>
                            </div>
                        </div>

                        <!-- NIF -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-id-card text-primary me-4"></i>
                                    <small class="text-muted fw-semibold text-uppercase">NIF</small>
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    {{ $fatura->nif ?? 'Não informado' }}
                                </div>
                            </div>
                        </div>

                        <!-- Data -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-calendar text-primary me-4"></i>
                                    <small class="text-muted fw-semibold text-uppercase">Data da Fatura</small>
                                </div>
                                <div class="fs-5 fw-bold text-dark">
                                    {{ \Carbon\Carbon::parse($fatura->data)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>

                        <!-- Valor -->
                        <div class="col-md-6">
                            <div class="p-3 bg-light rounded-3 h-100">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-euro-sign text-success me-4"></i>
                                    <small class="text-muted fw-semibold text-uppercase">Valor</small>
                                </div>
                                <div class="fs-4 fw-bold text-success">
                                    €{{ number_format($fatura->valor, 2, ',', '.') }}
                                </div>
                            </div>
                        </div>

                        <!-- Data de Criação -->
                        <div class="col-12">
                            <div class="p-3 bg-light rounded-3">
                                <div class="d-flex align-items-center mb-2">
                                    <i class="fas fa-clock text-primary me-4"></i>
                                    <small class="text-muted fw-semibold text-uppercase">Registrada em</small>
                                </div>
                                <div class="fs-6 fw-semibold text-dark">
                                    {{ isset($fatura->criado_em) 
                                        ? \Carbon\Carbon::parse($fatura->criado_em)->format('d/m/Y \à\s H:i') 
                                        : 'Data não disponível' }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Imagem da Fatura -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-header bg-light border-0 py-3">
                    <h5 class="mb-0 fw-semibold">
                        <i class="fas fa-image text-primary me-4"></i>Imagem da Fatura
                    </h5>
                </div>
                <div class="card-body p-4">
                    @if($fatura->imagem)
                        <div class="text-center">
                            <div class="position-relative d-inline-block">
                                <img src="{{ asset('storage/' . $fatura->imagem) }}" 
                                     alt="Imagem da Fatura" 
                                     class="img-fluid rounded-3 shadow-sm border"
                                     style="max-height: 500px; object-fit: contain; cursor: pointer;"
                                     onclick="openImageModal(this.src)">
                            </div>
                            
                            <!-- Botões com espaçamento melhorado -->
                            <div class="mt-4 d-flex gap-3 justify-content-center flex-wrap">
                                <a href="{{ asset('storage/' . $fatura->imagem) }}" 
                                   target="_blank" 
                                   class="btn btn-outline-primary btn-spaced">
                                    <i class="fas fa-external-link-alt me-3"></i>Abrir numa nova janela
                                </a>
                                <button class="btn btn-outline-info btn-spaced" 
                                        onclick="downloadImage('{{ asset('storage/' . $fatura->imagem) }}', 'fatura_{{ $fatura->id }}.jpg')">
                                    <i class="fas fa-download me-3"></i>Descarregar
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-image text-muted" style="font-size: 4rem; opacity: 0.3;"></i>
                            </div>
                            <h6 class="text-muted">Nenhuma imagem disponível</h6>
                            <p class="text-muted mb-0">Esta fatura não possui uma imagem associada.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Ações -->
            <div class="card shadow-sm border-0 mb-4">
                <div class="card-body p-4">
                    <!-- Layout responsivo com espaçamento melhorado -->
                    <div class="row g-3">
                        <!-- Ações principais -->
                        <div class="col-12 col-lg-8">
                            <div class="d-flex gap-3 flex-wrap">
                                <a href="{{ route('faturas.index') }}" class="btn btn-outline-secondary btn-lg btn-spaced px-4">
                                    <i class="fas fa-arrow-left me-3"></i>Voltar à Lista
                                </a>
                                <a href="{{ route('faturas.edit', $fatura->id) }}" class="btn btn-primary btn-lg btn-spaced px-4">
                                    <i class="fas fa-edit me-3"></i>Editar Fatura
                                </a>
                            </div>
                        </div>

                        <!-- Ação de exclusão -->
                        <div class="col-12 col-lg-4">
                            <div class="d-flex justify-content-lg-end">
                                <button type="button" class="btn btn-outline-danger btn-lg btn-spaced px-4" onclick="confirmDelete()">
                                    <i class="fas fa-trash-alt me-3"></i>Excluir
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para visualização da imagem -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="imageModalLabel">
                    <i class="fas fa-image text-primary me-4"></i>Imagem da Fatura
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <img id="modalImage" src="" alt="Imagem da Fatura" class="img-fluid rounded-3 shadow-sm">
            </div>
            <div class="modal-footer border-0 pt-0">
                <!-- Botões do modal com espaçamento -->
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-spaced" data-bs-dismiss="modal">
                        <i class="fas fa-times me-3"></i>Fechar
                    </button>
                    <a id="modalDownloadBtn" href="#" class="btn btn-primary btn-spaced" download>
                        <i class="fas fa-download me-3"></i>Baixar Imagem
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de confirmação de exclusão -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-2">
                <h5 class="modal-title fw-bold text-danger" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle me-4"></i>Confirmar Exclusão
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body px-4 py-3">
                <div class="text-center mb-4">
                    <i class="fas fa-trash-alt text-danger" style="font-size: 3rem; opacity: 0.7;"></i>
                </div>
                <h6 class="text-center mb-3">Tem certeza que deseja excluir esta fatura?</h6>
                <div class="alert alert-warning border-0 bg-warning bg-opacity-10">
                    <div class="d-flex align-items-start">
                        <i class="fas fa-exclamation-triangle text-warning me-4 mt-1"></i>
                        <div>
                            <small class="fw-semibold text-warning">Atenção:</small>
                            <small class="d-block text-muted">Esta ação não pode ser desfeita. Todos os dados da fatura serão permanentemente removidos.</small>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0 pt-0">
                <!-- Botões do modal com espaçamento -->
                <div class="d-flex gap-3 w-100 justify-content-end">
                    <button type="button" class="btn btn-outline-secondary btn-spaced" data-bs-dismiss="modal">
                        <i class="fas fa-times me-3"></i>Cancelar
                    </button>
                    <form action="{{ route('faturas.destroy', $fatura->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger btn-spaced">
                            <i class="fas fa-trash-alt me-3"></i>Sim, Excluir
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Função para abrir modal da imagem
function openImageModal(imageSrc) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');
    const downloadBtn = document.getElementById('modalDownloadBtn');
    
    modalImage.src = imageSrc;
    downloadBtn.href = imageSrc;
    downloadBtn.download = 'fatura_{{ $fatura->id }}.jpg';
    
    modal.show();
}

// Função para confirmar exclusão
function confirmDelete() {
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}

// Função para baixar imagem
function downloadImage(url, filename) {
    const link = document.createElement('a');
    link.href = url;
    link.download = filename;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Toast notification function (mesma do create)
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} me-3"></i>
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
            </div>
        </div>
    `;
    
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = '1055';
        document.body.appendChild(toastContainer);
    }
    
    toastContainer.insertAdjacentHTML('beforeend', toastHtml);
    
    const toastElement = toastContainer.lastElementChild;
    const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
    toast.show();
    
    toastElement.addEventListener('hidden.bs.toast', () => {
        toastElement.remove();
    });
}
</script>

<style>
/* Estilos customizados */
.form-control:focus, .form-select:focus {
    border-color: #0d6efd;
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.btn {
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

.toast-container {
    z-index: 1055;
}

/* Estilos específicos para o show */
.bg-light {
    transition: all 0.2s ease;
}

.bg-light:hover {
    background-color: #f1f3f5 !important;
}

/* Estilo para a imagem com hover */
.card-body img {
    transition: all 0.3s ease;
}

.card-body img:hover {
    transform: scale(1.02);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

/* Modal customizado */
.modal-content {
    border-radius: 15px;
}

.modal-xl {
    max-width: 90vw;
}

/* MELHORIAS DE ESPAÇAMENTO ENTRE ÍCONES E TEXTO */

/* Espaçamento consistente entre ícones e texto */
.fas, .far, .fab {
    margin-right: 0.5rem !important;
}

/* Espaçamento específico para diferentes contextos */
.btn i {
    margin-right: 0.5rem !important;
}

.card-header i {
    margin-right: 0.75rem !important;
}

.modal-title i {
    margin-right: 0.75rem !important;
}

.alert i {
    margin-right: 0.75rem !important;
}

/* Espaçamento para ícones em textos pequenos */
small i {
    margin-right: 0.4rem !important;
}

/* Espaçamento para ícones grandes */
.text-primary[style*="font-size: 3rem"] {
    margin-right: 1rem !important;
}

/* MELHORIAS DE ESPAÇAMENTO ENTRE BOTÕES */

/* Classe personalizada para botões com espaçamento */
.btn-spaced {
    margin: 0.25rem;
    min-width: 120px;
}

/* Espaçamento para grupos de botões */
.d-flex.gap-3 {
    gap: 1rem !important;
}

.d-flex.gap-3 .btn {
    margin: 0.25rem;
}

/* Espaçamento específico para modal footers */
.modal-footer .d-flex.gap-3 {
    gap: 1rem !important;
}

.modal-footer .btn {
    margin: 0.25rem;
    min-width: 100px;
}

/* Espaçamento para botões em linha */
.d-inline .btn {
    margin-left: 0.5rem;
}

/* Responsividade para dispositivos móveis */
@media (max-width: 768px) {
    .container-fluid {
        padding-left: 15px;
        padding-right: 15px;
    }
    
    .card-body {
        padding: 20px !important;
    }
    
    .btn-lg {
        padding: 12px 20px;
        font-size: 1rem;
    }
    
    .fs-5 {
        font-size: 1.1rem !important;
    }
    
    .fs-4 {
        font-size: 1.3rem !important;
    }
    
    /* Espaçamento móvel melhorado */
    .d-flex.gap-3 {
        gap: 0.75rem !important;
    }
    
    .btn-spaced {
        margin: 0.375rem;
        min-width: 100px;
    }
    
    /* Botões em tela cheia no mobile */
    .d-flex.gap-3 > .btn {
        flex: 1;
        min-width: 0;
        margin: 0.25rem;
    }
    
    /* Layout em coluna para botões principais no mobile */
    @media (max-width: 576px) {
        .d-flex.gap-3 {
            flex-direction: column;
            gap: 0.75rem !important;
        }
        
        .d-flex.gap-3 > .btn {
            width: 100%;
            margin: 0;
        }
        
        /* Modal buttons em linha no mobile */
        .modal-footer .d-flex.gap-3 {
            flex-direction: row;
            justify-content: space-between;
        }
        
        .modal-footer .btn {
            flex: 1;
            margin: 0 0.25rem;
        }
    }
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
</style>
@endsection