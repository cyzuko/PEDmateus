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
                                <i class="fas fa-edit me-3"></i>Editar Fatura
                            </h2>
                            <p class="text-muted mb-0">Atualize as informações da fatura</p>
                        </div>
                        <div class="d-none d-md-block">
                            <i class="fas fa-file-edit text-primary" style="font-size: 3rem; opacity: 0.1;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Mensagem de erro -->
            @if(session('error'))
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="alert alert-danger border-0 shadow-sm mb-0">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-exclamation-circle text-danger me-3" style="font-size: 1.5rem;"></i>
                                <div>
                                    <h6 class="mb-1 fw-semibold">Erro!</h6>
                                    <p class="mb-0">{{ session('error') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Formulário Principal -->
            <form method="POST" action="{{ route('faturas.update', $fatura->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <!-- Informações Básicas -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-header bg-light border-0 py-3">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-info-circle text-primary me-3"></i>Informações Básicas
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-4">
                            <!-- Fornecedor -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fornecedor" class="form-label fw-semibold">
                                        <i class="fas fa-building text-primary me-2"></i>Fornecedor
                                    </label>
                                    <input id="fornecedor" 
                                           type="text" 
                                           class="form-control form-control-lg @error('fornecedor') is-invalid @enderror" 
                                           name="fornecedor" 
                                           value="{{ old('fornecedor', $fatura->fornecedor) }}" 
                                           required
                                           placeholder="Nome do fornecedor">
                                    @error('fornecedor')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- NIF -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nif" class="form-label fw-semibold">
                                        <i class="fas fa-id-card text-primary me-2"></i>NIF
                                    </label>
                                    <input id="nif" 
                                           type="text" 
                                           class="form-control form-control-lg @error('nif') is-invalid @enderror" 
                                           name="nif" 
                                           value="{{ old('nif', $fatura->nif) }}" 
                                           maxlength="9" 
                                           pattern="[0-9]{9}"
                                           placeholder="123456789">
                                    @error('nif')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Digite apenas números (9 dígitos)
                                    </div>
                                </div>
                            </div>

                            <!-- Data -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="data" class="form-label fw-semibold">
                                        <i class="fas fa-calendar text-primary me-2"></i>Data da Fatura
                                    </label>
                                    <input id="data" 
                                           type="date" 
                                           class="form-control form-control-lg @error('data') is-invalid @enderror" 
                                           name="data" 
                                           value="{{ old('data', $fatura->data->format('Y-m-d')) }}" 
                                           required>
                                    @error('data')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Valor -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="valor" class="form-label fw-semibold">
                                        <i class="fas fa-euro-sign text-success me-2"></i>Valor
                                    </label>
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-light">
                                            <i class="fas fa-euro-sign text-success"></i>
                                        </span>
                                        <input id="valor" 
                                               type="number" 
                                               step="0.01" 
                                               class="form-control @error('valor') is-invalid @enderror" 
                                               name="valor" 
                                               value="{{ old('valor', $fatura->valor) }}" 
                                               required
                                               placeholder="0,00">
                                        @error('valor')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Use ponto (.) como separador decimal
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
                            <i class="fas fa-image text-primary me-3"></i>Imagem da Fatura
                        </h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row">
                            <!-- Upload de nova imagem -->
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="imagem" class="form-label fw-semibold">
                                        <i class="fas fa-upload text-primary me-2"></i>Nova Imagem
                                    </label>
                                    <input id="imagem" 
                                           type="file" 
                                           class="form-control form-control-lg @error('imagem') is-invalid @enderror" 
                                           name="imagem"
                                           accept="image/*">
                                    @error('imagem')
                                        <div class="invalid-feedback">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            <strong>{{ $message }}</strong>
                                        </div>
                                    @enderror
                                    <div class="form-text">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Faça upload de uma nova imagem para substituir a atual (JPG, PNG, GIF)
                                    </div>
                                </div>
                            </div>

                            <!-- Imagem atual -->
                            <div class="col-md-6">
                                @if($fatura->imagem)
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-eye text-info me-2"></i>Imagem Atual
                                        </label>
                                        <div class="border rounded-3 p-3 bg-light text-center">
                                            <img src="{{ asset('storage/' . $fatura->imagem) }}" 
                                                 alt="Imagem atual" 
                                                 class="img-thumbnail shadow-sm" 
                                                 style="max-height: 120px; cursor: pointer;"
                                                 onclick="openImageModal(this.src)">
                                            <div class="mt-2">
                                                <small class="text-muted">
                                                    <i class="fas fa-mouse-pointer me-1"></i>
                                                    Clique para ampliar
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-image text-muted me-2"></i>Imagem Atual
                                        </label>
                                        <div class="border rounded-3 p-4 bg-light text-center">
                                            <i class="fas fa-image text-muted mb-2" style="font-size: 2rem; opacity: 0.3;"></i>
                                            <div class="text-muted">Nenhuma imagem atual</div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Ações -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex gap-4 justify-content-between flex-wrap">
                            <!-- Ações principais -->
                            <div class="d-flex flex-wrap">
                                <a href="{{ route('faturas.show', $fatura->id) }}" class="btn btn-outline-secondary btn-lg px-4 me-4">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-save me-2"></i>Atualizar Fatura
                                </button>
                            </div>

                            <!-- Link para visualizar -->
                            <div>
                                <a href="{{ route('faturas.show', $fatura->id) }}" class="btn btn-outline-info btn-lg px-4">
                                    <i class="fas fa-eye me-2"></i>Visualizar
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal para visualização da imagem atual -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title fw-bold" id="imageModalLabel">
                    <i class="fas fa-image text-primary me-3"></i>Imagem Atual da Fatura
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4 text-center">
                <img id="modalImage" src="" alt="Imagem da Fatura" class="img-fluid rounded-3 shadow-sm">
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Fechar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Função para abrir modal da imagem
function openImageModal(imageSrc) {
    const modal = new bootstrap.Modal(document.getElementById('imageModal'));
    const modalImage = document.getElementById('modalImage');
    
    modalImage.src = imageSrc;
    modal.show();
}

// Função para preview da nova imagem selecionada
document.getElementById('imagem').addEventListener('change', function(e) {
    const file = e.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            showImagePreview(e.target.result);
        };
        reader.readAsDataURL(file);
    }
});

function showImagePreview(imageSrc) {
    const existingPreview = document.getElementById('imagePreview');
    if (existingPreview) {
        existingPreview.remove();
    }

    const previewHtml = `
        <div id="imagePreview" class="mt-3 text-center">
            <div class="border rounded-3 p-3 bg-light">
                <label class="form-label fw-semibold text-success">
                    <i class="fas fa-eye text-success me-2"></i>Preview da Nova Imagem
                </label>
                <div>
                    <img src="${imageSrc}" alt="Preview" class="img-thumbnail shadow-sm" style="max-height: 120px;">
                </div>
                <small class="text-success d-block mt-2">
                    <i class="fas fa-check-circle me-1"></i>
                    Nova imagem será salva ao atualizar
                </small>
            </div>
        </div>
    `;
    
    document.getElementById('imagem').closest('.form-group').insertAdjacentHTML('beforeend', previewHtml);
}

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const fornecedor = document.getElementById('fornecedor').value.trim();
    const data = document.getElementById('data').value;
    const valor = document.getElementById('valor').value;

    if (!fornecedor || !data || !valor) {
        e.preventDefault();
        showToast('Por favor, preencha todos os campos obrigatórios.', 'error');
        return;
    }

    if (parseFloat(valor) <= 0) {
        e.preventDefault();
        showToast('O valor deve ser maior que zero.', 'error');
        return;
    }

    // Mostrar loading
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Atualizando...';
    submitBtn.disabled = true;
    
    // Reabilitar o botão após um tempo (caso haja erro)
    setTimeout(() => {
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    }, 10000);
});

// Formatação do NIF (apenas números)
document.getElementById('nif').addEventListener('input', function(e) {
    this.value = this.value.replace(/\D/g, '');
});

// Toast notification function
function showToast(message, type = 'info') {
    const toastHtml = `
        <div class="toast align-items-center text-bg-${type === 'error' ? 'danger' : type === 'success' ? 'success' : 'primary'} border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body">
                    <i class="fas fa-${type === 'error' ? 'exclamation-circle' : type === 'success' ? 'check-circle' : 'info-circle'} me-2"></i>
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

.form-control-lg {
    padding: 12px 16px;
    font-size: 1.1rem;
}

.input-group-lg .input-group-text {
    padding: 12px 16px;
    font-size: 1.1rem;
}

/* Estilos para labels */
.form-label {
    margin-bottom: 8px;
    color: #495057;
}

/* Hover effects para elementos interativos */
.bg-light {
    transition: all 0.2s ease;
}

.bg-light:hover {
    background-color: #f1f3f5 !important;
}

/* Estilo para preview de imagem */
#imagePreview .img-thumbnail {
    transition: all 0.3s ease;
}

#imagePreview .img-thumbnail:hover {
    transform: scale(1.05);
}

/* Modal customizado */
.modal-content {
    border-radius: 15px;
}

.modal-xl {
    max-width: 90vw;
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
    
    .form-control-lg {
        padding: 10px 14px;
        font-size: 1rem;
    }
    
    .input-group-lg .input-group-text {
        padding: 10px 14px;
        font-size: 1rem;
    }
    
    .d-flex.gap-4 {
        gap: 1.5rem !important;
    }
    
    .d-flex.gap-4 > * {
        flex: 1;
        min-width: 0;
    }
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

/* Estilos para estados de validação */
.is-invalid {
    border-color: #dc3545;
}

.invalid-feedback {
    display: block;
    color: #dc3545;
    font-size: 0.875rem;
    margin-top: 5px;
}

.form-text {
    color: #6c757d;
    font-size: 0.875rem;
    margin-top: 5px;
}

/* Destaque para campos obrigatórios */
.form-label::after {
    content: " *";
    color: #dc3545;
}

/* Remover asterisco de campos opcionais */
label[for="nif"]::after,
label[for="imagem"]::after {
    content: "";
}

/* Estados dos botões */
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}

/* Toast container */
.toast-container {
    z-index: 1055;
}

/* Estilo especial para input de valor */
.input-group .form-control:focus {
    border-color: #0d6efd;
    box-shadow: none;
}

.input-group:focus-within {
    box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
}

.input-group:focus-within .input-group-text {
    border-color: #0d6efd;
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
.d-flex.gap-4 {
    gap: 1rem !important;
}

.d-flex.gap-4 .btn {
    margin: 0.25rem;
}

/* Espaçamento específico para botões em linha */
.d-flex:has(.btn) {
    gap: 1rem;
}

.d-flex:has(.btn) > * {
    margin: 0.25rem;
}
</style>
@endsection