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
                                        <i class="fas fa-plus-circle me-2"></i>Nova Fatura
                                    </h2>
                                    <p class="text-muted mb-0">Adicione uma nova fatura ao sistema</p>
                                </div>
                                <div class="d-none d-md-block">
                                    <i class="fas fa-file-invoice text-primary" style="font-size: 3rem; opacity: 0.1;"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('faturas.store') }}" enctype="multipart/form-data">
                        @csrf
                        
                        <!-- Dados Básicos -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fas fa-info-circle text-primary me-3"></i>Dados Básicos
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Fornecedor -->
                                    <div class="col-md-6">
                                        <label for="fornecedor" class="form-label fw-semibold">
                                            <i class="fas fa-building me-1 text-muted"></i>Fornecedor *
                                        </label>
                                        <input id="fornecedor" type="text" 
                                            class="form-control form-control-lg @error('fornecedor') is-invalid @enderror" 
                                            name="fornecedor" value="{{ old('fornecedor') }}" 
                                            placeholder="Nome do fornecedor..." required>
                                        @error('fornecedor')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- NIF -->
                                    <div class="col-md-6">
                                        <label for="nif" class="form-label fw-semibold">
                                            <i class="fas fa-id-card me-1 text-muted"></i>NIF
                                        </label>
                                        <input id="nif" type="text" 
                                            class="form-control form-control-lg @error('nif') is-invalid @enderror" 
                                            name="nif" value="{{ old('nif') }}" 
                                            placeholder="123456789">
                                        @error('nif')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Data -->
                                    <div class="col-md-6">
                                        <label for="data" class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1 text-muted"></i>Data da Fatura *
                                        </label>
                                        <input id="data" type="date" 
                                            class="form-control form-control-lg @error('data') is-invalid @enderror" 
                                            name="data" value="{{ old('data') }}" required>
                                        @error('data')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Valor -->
                                    <div class="col-md-6">
                                        <label for="valor" class="form-label fw-semibold">
                                            <i class="fas fa-euro-sign me-1 text-muted"></i>Valor *
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light">€</span>
                                            <input id="valor" type="number" step="0.01" 
                                                class="form-control @error('valor') is-invalid @enderror" 
                                                name="valor" value="{{ old('valor') }}" 
                                                placeholder="0,00" required>
                                        </div>
                                        @error('valor')
                                            <div class="invalid-feedback">
                                                <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Captura de Imagem -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fas fa-camera text-primary me-2"></i>Imagem da Fatura
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <!-- Botões de Ação -->
                                <div class="row g-3 mb-4">
                                    <div class="col-sm-6">
                                        <button type="button" id="captureButton" class="btn btn-primary btn-lg w-100">
                                            <i class="fas fa-camera me-2"></i>Capturar Imagem
                                        </button>
                                    </div>
                                    <div class="col-sm-6">
                                        <label for="fileImage" class="btn btn-outline-primary btn-lg w-100 mb-0">
                                            <i class="fas fa-upload me-2"></i>Selecionar Arquivo
                                        </label>
                                        <input type="file" id="fileImage" class="d-none" name="imagem_upload" accept="image/*">
                                    </div>
                                </div>

                                <!-- Câmera/Preview -->
                                <div class="row">
                                    <div class="col-12">
                                        <div class="position-relative bg-light rounded-3 overflow-hidden" style="min-height: 300px;">
                                            <video id="video" class="w-100 h-100" autoplay playsinline style="object-fit: cover; max-height: 400px;"></video>
                                            <canvas id="canvas" class="d-none"></canvas>
                                            
                                            <!-- Imagem Capturada -->
                                            <div id="imagePreview" class="text-center" style="display: none;">
                                                <img id="capturedImage" src="#" alt="Imagem Capturada" class="img-fluid rounded-3 shadow-sm">
                                            </div>

                                            <!-- Overlay quando não há câmera -->
                                            <div id="cameraPlaceholder" class="position-absolute top-50 start-50 translate-middle text-center" style="display: none;">
                                                <i class="fas fa-camera text-muted" style="font-size: 4rem;"></i>
                                                <p class="text-muted mt-3">Câmera não disponível</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <input id="imagem" type="hidden" name="imagem">

                                <!-- Botão OCR -->
                                <div class="text-center mt-4">
                                    <button type="button" id="ocrButton" class="btn btn-info btn-lg" style="display: none;">
                                        <span id="ocrSpinner" class="spinner-border spinner-border-sm me-2 d-none" role="status"></span>
                                        <i class="fas fa-eye me-2"></i>Reconhecer Dados (OCR)
                                    </button>
                                </div>

                                <!-- Progresso OCR -->
                                <div id="ocrProgress" class="mt-3" style="display: none;">
                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                        <small class="text-muted">Processando imagem...</small>
                                        <small id="progressText" class="text-muted">0%</small>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped progress-bar-animated bg-info" role="progressbar" style="width: 0%"></div>
                                    </div>
                                </div>

                                <!-- Resultados OCR -->
                                <div id="ocrResults" class="mt-4" style="display: none;">
                                    <div class="alert alert-info border-0 shadow-sm">
                                        <div class="d-flex align-items-center mb-3">
                                            <i class="fas fa-magic text-info me-2"></i>
                                            <h6 class="mb-0 fw-semibold">Dados Reconhecidos</h6>
                                        </div>
                                        
                                        <div class="row g-3">
                                            <div class="col-sm-6">
                                                <div class="bg-white p-3 rounded-2">
                                                    <small class="text-muted d-block">Fornecedor:</small>
                                                    <div id="ocrFornecedor" class="fw-semibold">-</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="bg-white p-3 rounded-2">
                                                    <small class="text-muted d-block">NIF:</small>
                                                    <div id="ocrNif" class="fw-semibold">-</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="bg-white p-3 rounded-2">
                                                    <small class="text-muted d-block">Data:</small>
                                                    <div id="ocrData" class="fw-semibold">-</div>
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="bg-white p-3 rounded-2">
                                                    <small class="text-muted d-block">Valor:</small>
                                                    <div id="ocrValor" class="fw-semibold">-</div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center mt-3">
                                            <button type="button" id="applyOcrButton" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Aplicar Dados
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                @error('imagem')
                                    <div class="alert alert-danger border-0 shadow-sm mt-3">
                                        <i class="fas fa-exclamation-triangle me-2"></i>{{ $message }}
                                    </div>
                                @enderror
                            </div>
                        </div>

                        <!-- Notificações -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-header bg-light border-0 py-3">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fas fa-bell text-primary me-2"></i>Notificações
                                </h5>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Checkboxes -->
                                    <div class="col-12">
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                                    <input class="form-check-input" type="checkbox" name="enviar_email" 
                                                        id="enviar_email" value="1" {{ old('enviar_email') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="enviar_email">
                                                        <i class="fas fa-envelope text-primary me-2"></i>
                                                        Notificação por Email
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                                    <input class="form-check-input" type="checkbox" name="enviar_sms" 
                                                        id="enviar_sms" value="1" {{ old('enviar_sms') ? 'checked' : '' }}>
                                                    <label class="form-check-label fw-semibold" for="enviar_sms">
                                                        <i class="fas fa-sms text-success me-2"></i>
                                                        Notificação por SMS
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Configuração Email -->
                                    <div id="email_config" class="col-12" style="display: none;">
                                        <div class="card border-primary">
                                            <div class="card-body">
                                                <h6 class="card-title text-primary">
                                                    <i class="fas fa-envelope me-2"></i>Configuração do Email
                                                </h6>
                                                <div class="form-group">
                                                    <label for="email_para" class="form-label">Email de destino:</label>
                                                    <input type="email" class="form-control @error('email_para') is-invalid @enderror" 
                                                        name="email_para" id="email_para" value="{{ old('email_para') }}"
                                                        placeholder="exemplo@email.com">
                                                    @error('email_para')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Configuração SMS -->
                                    <div id="sms_config" class="col-12" style="display: none;">
                                        <div class="card border-success">
                                            <div class="card-body">
                                                <h6 class="card-title text-success">
                                                    <i class="fas fa-sms me-2"></i>Configuração do SMS
                                                </h6>
                                                <div class="form-group">
                                                    <label for="telefone" class="form-label">Número de telefone:</label>
                                                    <input type="text" class="form-control @error('telefone') is-invalid @enderror" 
                                                        name="telefone" id="telefone" value="{{ old('telefone') }}" 
                                                        placeholder="+351910000000">
                                                    <div class="form-text">
                                                        <i class="fas fa-info-circle me-1"></i>
                                                        Formatos aceites: +351910000000, 00351910000000, 910000000
                                                    </div>
                                                    @error('telefone')
                                                        <div class="invalid-feedback">
                                                            <i class="fas fa-exclamation-circle me-1"></i>{{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Botões de Ação -->
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <div class="d-flex gap-4 justify-content-end flex-wrap">
                                    <a href="{{ route('faturas.index') }}" class="btn btn-outline-secondary btn-lg px-4">
                                        <i class="fas fa-times me-2"></i>Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary btn-lg px-4">
                                        <i class="fas fa-save me-2"></i>Criar Fatura
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.1.1/dist/tesseract.min.js"></script>
        <script>
        const video = document.getElementById('video');
        const captureButton = document.getElementById('captureButton');
        const canvas = document.getElementById('canvas');
        const capturedImage = document.getElementById('capturedImage');
        const imagePreview = document.getElementById('imagePreview');
        const imagemInput = document.getElementById('imagem');
        const fileImage = document.getElementById('fileImage');
        const ocrButton = document.getElementById('ocrButton');
        const ocrResults = document.getElementById('ocrResults');
        const ocrSpinner = document.getElementById('ocrSpinner');
        const ocrProgress = document.getElementById('ocrProgress');
        const progressBar = document.querySelector('.progress-bar');
        const progressText = document.getElementById('progressText');
        const cameraPlaceholder = document.getElementById('cameraPlaceholder');

        let stream;
        let imageSource = null;

        // Inicializa a câmera
        async function startCamera() {
            try {
                // Detectar se é dispositivo móvel
                const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
                
                const constraints = {
                    video: {
                        facingMode: { ideal: 'environment' },
                        width: { ideal: isMobile ? 720 : 1280 },
                        height: { ideal: isMobile ? 1280 : 720 }
                    },
                    audio: false
                };

                stream = await navigator.mediaDevices.getUserMedia(constraints);
                video.srcObject = stream;
                cameraPlaceholder.style.display = 'none';
                
                // Para dispositivos móveis, adicionar rotação CSS se necessário
                if (isMobile) {
                    video.style.transform = 'rotate(0deg)';
                    video.style.objectFit = 'cover';
                }
                
            } catch (err) {
                console.error("Erro ao acessar a câmera: ", err);
                video.style.display = 'none';
                cameraPlaceholder.style.display = 'block';
            }
        }
        // Captura de imagem
        captureButton.addEventListener('click', function() {
            if (video.readyState >= 2) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

                const dataUrl = canvas.toDataURL('image/png');
                capturedImage.src = dataUrl;
                imagePreview.style.display = 'block';
                video.style.display = 'none';
                imagemInput.value = dataUrl;
                imageSource = 'camera';

                ocrButton.style.display = 'inline-block';
                
                // Feedback visual
                captureButton.innerHTML = '<i class="fas fa-redo me-2"></i>Capturar Novamente';
            } else {
                // Toast notification
                showToast('A câmera ainda está carregando. Aguarde um momento.', 'warning');
            }
        });

        // Upload de arquivo
        fileImage.addEventListener('change', function(e) {
            if (e.target.files && e.target.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(event) {
                    capturedImage.src = event.target.result;
                    imagePreview.style.display = 'block';
                    video.style.display = 'none';
                    imageSource = 'file';
                    ocrButton.style.display = 'inline-block';
                }
                
                reader.readAsDataURL(e.target.files[0]);
            }
        });

        // OCR Processing
        ocrButton.addEventListener('click', async function() {
            ocrSpinner.classList.remove('d-none');
            ocrProgress.style.display = 'block';
            ocrButton.disabled = true;
            progressBar.style.width = '0%';
            progressText.textContent = '0%';

            try {
                const worker = await Tesseract.createWorker({
                    logger: m => {
                        if (m.status === 'recognizing text') {
                            const progress = Math.round(m.progress * 100);
                            progressBar.style.width = `${progress}%`;
                            progressText.textContent = `${progress}%`;
                        }
                    },
                });

                await worker.load();
                await worker.loadLanguage('por');
                await worker.initialize('por');

                const result = await worker.recognize(capturedImage.src);
                await worker.terminate();

                const { text } = result.data;
                const fornecedor = extractFornecedor(text);
                const nif = extractNIF(text);
                const data = extractDate(text);
                const valor = extractValue(text);

                document.getElementById('ocrFornecedor').textContent = fornecedor || 'Não identificado';
                document.getElementById('ocrNif').textContent = nif || 'Não identificado';
                document.getElementById('ocrData').textContent = data || 'Não identificado';
                document.getElementById('ocrValor').textContent = valor || 'Não identificado';

                ocrResults.style.display = 'block';
                ocrResults.scrollIntoView({ behavior: 'smooth' });
                
                showToast('Dados reconhecidos com sucesso!', 'success');
            } catch (err) {
                console.error('Erro OCR:', err);
                showToast('Erro ao processar a imagem. Tente novamente.', 'error');
            } finally {
                ocrSpinner.classList.add('d-none');
                ocrProgress.style.display = 'none';
                ocrButton.disabled = false;
            }
        });

        // Aplicar dados do OCR
        document.getElementById('applyOcrButton').addEventListener('click', function() {
            const fornecedor = document.getElementById('ocrFornecedor').textContent;
            const nif = document.getElementById('ocrNif').textContent;
            const data = document.getElementById('ocrData').textContent;
            const valor = document.getElementById('ocrValor').textContent;

            if (fornecedor !== 'Não identificado') {
                document.getElementById('fornecedor').value = fornecedor;
            }
            if (nif !== 'Não identificado') {
                document.getElementById('nif').value = nif.replace(/\s/g, '');
            }
            if (data !== 'Não identificado') {
                document.getElementById('data').value = data;
            }
            if (valor !== 'Não identificado') {
                document.getElementById('valor').value = valor.replace(/[^\d,.-]/g, '').replace(',', '.');
            }
            
            showToast('Dados aplicados nos campos!', 'success');
            document.querySelector('.card').scrollIntoView({ behavior: 'smooth' });
        });

        // Configuração de notificações
        document.getElementById('enviar_email').addEventListener('change', function() {
            const emailConfig = document.getElementById('email_config');
            if (this.checked) {
                emailConfig.style.display = 'block';
                emailConfig.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                emailConfig.style.display = 'none';
            }
        });

        document.getElementById('enviar_sms').addEventListener('change', function() {
            const smsConfig = document.getElementById('sms_config');
            if (this.checked) {
                smsConfig.style.display = 'block';
                smsConfig.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            } else {
                smsConfig.style.display = 'none';
            }
        });

        // Verificar estado inicial
        window.addEventListener('DOMContentLoaded', (event) => {
            if (document.getElementById('enviar_email').checked) {
                document.getElementById('email_config').style.display = 'block';
            }
            
            if (document.getElementById('enviar_sms').checked) {
                document.getElementById('sms_config').style.display = 'block';
            }
        });

        // Toast notification function
        function showToast(message, type = 'info') {
            // Criar elemento toast
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
            
            // Adicionar ao container de toasts
            let toastContainer = document.getElementById('toast-container');
            if (!toastContainer) {
                toastContainer = document.createElement('div');
                toastContainer.id = 'toast-container';
                toastContainer.className = 'toast-container position-fixed top-0 end-0 p-3';
                toastContainer.style.zIndex = '1055';
                document.body.appendChild(toastContainer);
            }
            
            toastContainer.insertAdjacentHTML('beforeend', toastHtml);
            
            // Mostrar toast
            const toastElement = toastContainer.lastElementChild;
            const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
            toast.show();
            
            // Remover do DOM após esconder
            toastElement.addEventListener('hidden.bs.toast', () => {
                toastElement.remove();
            });
        }

        // Funções de extração (mantidas as originais)
        function extractFornecedor(text) {
            const patterns = [
                /(?:razão\s*social|denominação\s*social)[\s\:\-]*([^\n\r]{5,50})/i,
                /(?:empresa|nome\s*da\s*empresa)[\s\:\-]*([^\n\r]{5,50})/i,
                /(?:fornecedor|prestador)[\s\:\-]*([^\n\r]{5,50})/i,
                /(?:emitente|vendedor)[\s\:\-]*([^\n\r]{5,50})/i,
                /^([A-ZÁÀÂÃÇÉÊÍÓÔÕÚ][a-záàâãçéêíóôõú\s\.\-&]{4,}(?:LDA|LTDA|S\.?A\.?|UNIPESSOAL)?)/im
            ];
            
            for (const pattern of patterns) {
                const match = text.match(pattern);
                if (match && match[1]) {
                    let name = match[1].trim()
                        .replace(/^\W+|\W+$/g, '')
                        .replace(/\s+/g, ' ');
                    
                    if (name.length > 3 && !/^\d+[\.\,\d]*$/.test(name)) {
                        return name;
                    }
                }
            }
            
            return null;
        }

        function extractNIF(text) {
            const patterns = [
                /(?:NIF|NIPC|N\.?\s*I\.?\s*F\.?|Contribuinte|Nr\.?\s*Contribuinte|Número\s*(?:de\s*)?Contribuinte|NPC|Tax\s*ID)[\s\:\.\-]*(\d{9})/i,
                /(?:CPF|CNPJ|C\.?P\.?F\.?|C\.?N\.?P\.?J\.?)[\s\:\.\-]*(\d{3}\.?\d{3}\.?\d{3}[-\.]?\d{2}|\d{2}\.?\d{3}\.?\d{3}\/?\d{4}[-\.]?\d{2})/i,
                /(?:contribuinte|fiscal|tributário)[\s\:\.\-]*(\d{9,14})/i
            ];
            
            for (const pattern of patterns) {
                const match = text.match(pattern);
                if (match && match[1]) {
                    return match[1].replace(/[\.\-\/\s]/g, '');
                }
            }
            
            const nifPattern = /\b(\d{9})\b/g;
            const matches = [...text.matchAll(nifPattern)];
            for (const match of matches) {
                const nif = match[1];
                if (nif[0] !== '0') {
                    return nif;
                }
            }
            
            return null;
        }

        function extractDate(text) {
            const regex = /(\d{2}[\/\-]\d{2}[\/\-]\d{4})|(\d{4}[\/\-]\d{2}[\/\-]\d{2})/g;
            const matches = text.match(regex);
            if (matches) {
                for (let date of matches) {
                    const clean = date.replace(/[\/\-]/g, '-');
                    const parts = clean.split('-');
                    if (parts[0].length === 4) {
                        return `${parts[0]}-${parts[1].padStart(2, '0')}-${parts[2].padStart(2, '0')}`;
                    } else {
                        return `${parts[2]}-${parts[1].padStart(2, '0')}-${parts[0].padStart(2, '0')}`;
                    }
                }
            }
            return null;
        }

        function extractValue(text) {
            const cleanedText = text.replace(/\s+/g, ' ').toLowerCase();
            const totalRegex = /(?:total\s*(?:da\s*nota|geral|a\s*pagar|valor)?\s*[:\-]?\s*)(r?\$\s?\d{1,3}(?:\.\d{3})*,\d{2}|\d+(?:[.,]\d{2}))/i;
            const match = cleanedText.match(totalRegex);
            if (match && match[1]) {
                return match[1].replace(/\s/g, '').replace(',', '.');
            }
            const fallbackRegex = /(r?\$\s?\d{1,3}(?:\.\d{3})*,\d{2}|\d+(?:[.,]\d{2}))/gi;
            const values = [...cleanedText.matchAll(fallbackRegex)].map(m => parseFloat(m[0].replace(/[^\d,]/g, '').replace(',', '.')));
            if (values.length) {
                const max = Math.max(...values);
                return `€ ${max.toFixed(2).replace('.', ',')}`;
            }
            return null;
        }

        // Inicializar câmera ao carregar a página
        startCamera();
        </script>

        <style>
        /* Estilos customizados para melhorar a aparência */
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

        .form-check-input:checked {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        .toast-container {
            z-index: 1055;
        }

        /* Animações para os cards de notificação */
        #email_config, #sms_config {
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Estilo para o preview da imagem */
        #imagePreview img {
            max-height: 400px;
            object-fit: contain;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
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
                padding: 12px 16px;
                font-size: 1rem;
            }
        }

        /* Estilo para o placeholder da câmera */
        #cameraPlaceholder {
            background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        }

        /* Estilo melhorado para os checkboxes */
        .form-check-lg .form-check-input {
            width: 1.5em;
            height: 1.5em;
            margin-top: 0.125em;
        }

        .form-check-lg .form-check-label {
            font-size: 1.1rem;
            padding-left: 0.5rem;
        }

        /* Efeito nos cards de configuração */
        .card.border-primary {
            border-width: 2px !important;
            background: linear-gradient(135deg, rgba(13, 110, 253, 0.02) 0%, rgba(255, 255, 255, 1) 100%);
        }

        .card.border-success {
            border-width: 2px !important;
            background: linear-gradient(135deg, rgba(25, 135, 84, 0.02) 0%, rgba(255, 255, 255, 1) 100%);
        }

        /* Melhorias no progresso do OCR */
        .progress {
            height: 8px;
            border-radius: 10px;
            overflow: hidden;
        }

        .progress-bar {
            transition: width 0.3s ease;
        }

        /* Estilo para os resultados do OCR */
        #ocrResults .bg-white {
            border: 1px solid rgba(0,0,0,0.05);
            transition: all 0.2s ease;
        }

        #ocrResults .bg-white:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            transform: translateY(-1px);
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