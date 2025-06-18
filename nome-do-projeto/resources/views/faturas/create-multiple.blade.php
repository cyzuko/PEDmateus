        @extends('layouts.app')
        @section('content')<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nova Fatura Múltipla</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body class="bg-light">
    <div class="container-fluid px-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10 col-xl-8">
                <!-- Header Card -->
                <div class="card shadow-sm border-0 mb-4">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <h2 class="mb-1 fw-bold text-primary">
                                    <i class="fas fa-plus-circle me-2"></i>Múltiplas Faturas
                                </h2>
                                <p class="text-muted mb-0">Adicione várias faturas de uma só vez</p>
                            </div>
                            <div class="d-none d-md-block">
                                <i class="fas fa-file-invoice-dollar text-primary" style="font-size: 3rem; opacity: 0.1;"></i>
                            </div>
                        </div>
                    </div>
                </div>

               <!-- CORREÇÃO: Formulário com action e CSRF token corretos -->
                <form id="multipleFaturasForm" method="POST" action="{{ route('faturas.store-multiple') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <div id="faturasContainer">
                        <!-- Primeira fatura (template) -->
                        <div class="fatura-item card shadow-sm border-0 mb-4" data-index="0">
                            <div class="card-header bg-light border-0 py-3 d-flex justify-content-between align-items-center">
                                <h5 class="mb-0 fw-semibold">
                                    <i class="fas fa-file-invoice text-primary me-2"></i>Fatura #1
                                </h5>
                                <button type="button" class="btn btn-outline-danger btn-sm remove-fatura" style="display: none;">
                                    <i class="fas fa-trash me-1"></i>Remover
                                </button>
                            </div>
                            <div class="card-body p-4">
                                <div class="row g-4">
                                    <!-- Dados Básicos -->
                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-building me-1 text-muted"></i>Fornecedor *
                                        </label>
                                        <input type="text" class="form-control form-control-lg" 
                                            name="faturas[0][fornecedor]" placeholder="Nome do fornecedor..." required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-id-card me-1 text-muted"></i>NIF
                                        </label>
                                        <input type="text" class="form-control form-control-lg" 
                                            name="faturas[0][nif]" placeholder="123456789">
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-calendar me-1 text-muted"></i>Data da Fatura *
                                        </label>
                                        <input type="date" class="form-control form-control-lg" 
                                            name="faturas[0][data]" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label fw-semibold">
                                            <i class="fas fa-euro-sign me-1 text-muted"></i>Valor *
                                        </label>
                                        <div class="input-group input-group-lg">
                                            <span class="input-group-text bg-light">€</span>
                                            <input type="number" step="0.01" class="form-control" 
                                                name="faturas[0][valor]" placeholder="0,00" required>
                                        </div>
                                    </div>
                                </div>

                                <!-- Captura de Imagem -->
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <h6 class="fw-semibold mb-3">
                                            <i class="fas fa-camera text-primary me-2"></i>Imagem da Fatura
                                        </h6>
                                        
                                        <div class="row g-3 mb-3">
                                            <div class="col-sm-6">
                                                <button type="button" class="btn btn-primary btn-lg w-100 capture-btn">
                                                    <i class="fas fa-camera me-2"></i>Capturar Imagem
                                                </button>
                                            </div>
                                            <div class="col-sm-6">
                                                <label class="btn btn-outline-primary btn-lg w-100 mb-0">
                                                    <i class="fas fa-upload me-2"></i>Selecionar Arquivo
                                                    <input type="file" class="d-none file-input" name="faturas[0][imagem_upload]" accept="image/*">
                                                </label>
                                            </div>
                                        </div>

                                        <!-- Preview da imagem -->
                                        <div class="image-preview bg-light rounded-3 p-3 text-center" style="min-height: 150px; display: none;">
                                            <img class="img-fluid rounded shadow-sm" style="max-height: 200px;">
                                            <div class="mt-2">
                                                <button type="button" class="btn btn-info btn-sm ocr-btn">
                                                    <i class="fas fa-eye me-1"></i>Reconhecer Dados (OCR)
                                                </button>
                                            </div>
                                        </div>

                                        <input type="hidden" class="imagem-hidden" name="faturas[0][imagem]">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botão Adicionar Fatura -->
                    <div class="text-center mb-4">
                        <button type="button" id="addFaturaBtn" class="btn btn-success btn-lg">
                            <i class="fas fa-plus me-2"></i>Adicionar Outra Fatura
                        </button>
                    </div>

                    <!-- Notificações Globais -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-header bg-light border-0 py-3">
                            <h5 class="mb-0 fw-semibold">
                                <i class="fas fa-bell text-primary me-2"></i>Notificações (Para Todas)
                            </h5>
                        </div>
                        <div class="card-body p-4">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                        <input class="form-check-input" type="checkbox" name="enviar_email" id="enviar_email" value="1">
                                        <label class="form-check-label fw-semibold" for="enviar_email">
                                            <i class="fas fa-envelope text-primary me-2"></i>Notificação por Email
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-lg p-3 bg-light rounded-3">
                                        <input class="form-check-input" type="checkbox" name="enviar_sms" id="enviar_sms" value="1">
                                        <label class="form-check-label fw-semibold" for="enviar_sms">
                                            <i class="fas fa-sms text-success me-2"></i>Notificação por SMS
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <!-- Config Email -->
                            <div id="email_config" class="mt-3" style="display: none;">
                                <div class="card border-primary">
                                    <div class="card-body">
                                        <label class="form-label">Email de destino:</label>
                                        <input type="email" class="form-control" name="email_para" placeholder="exemplo@email.com">
                                    </div>
                                </div>
                            </div>

                            <!-- Config SMS -->
                            <div id="sms_config" class="mt-3" style="display: none;">
                                <div class="card border-success">
                                    <div class="card-body">
                                        <label class="form-label">Número de telefone:</label>
                                        <input type="text" class="form-control" name="telefone" placeholder="+351910000000">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Botões de Ação -->
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex gap-4 justify-content-end flex-wrap">
                                <a href="/faturas" class="btn btn-outline-secondary btn-lg px-4">
                                    <i class="fas fa-times me-2"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-primary btn-lg px-4">
                                    <i class="fas fa-save me-2"></i>Salvar Todas (<span id="faturaCount"></span>)
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Camera Modal -->
    <div class="modal fade" id="cameraModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Capturar Imagem</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center">
                        <video id="modalVideo" class="w-100 rounded" autoplay playsinline style="max-height: 400px;"></video>
                        <canvas id="modalCanvas" class="d-none"></canvas>
                        <div class="mt-3">
                            <button type="button" id="modalCaptureBtn" class="btn btn-primary">
                                <i class="fas fa-camera me-2"></i>Capturar
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.1.1/dist/tesseract.min.js"></script>
    <script>
        let faturaIndex = 0;
        let currentFaturaTarget = null;
        let stream = null;

        // Inicializar
        document.addEventListener('DOMContentLoaded', function() {
            updateFaturaCount();
            initializeEventListeners();
            setupNotificationToggles();
        });

        function initializeEventListeners() {
            // Adicionar nova fatura
            document.getElementById('addFaturaBtn').addEventListener('click', addNewFatura);
            
            // Camera modal
            document.getElementById('modalCaptureBtn').addEventListener('click', captureFromModal);
            
            // Quando modal é aberto, iniciar camera
            document.getElementById('cameraModal').addEventListener('shown.bs.modal', startModalCamera);
            document.getElementById('cameraModal').addEventListener('hidden.bs.modal', stopModalCamera);
        }

        function addNewFatura() {
            faturaIndex++;
            const template = document.querySelector('.fatura-item').cloneNode(true);
            
            // Atualizar índices e nomes
            template.setAttribute('data-index', faturaIndex);
            template.querySelector('h5').innerHTML = `<i class="fas fa-file-invoice text-primary me-2"></i>Fatura #${faturaIndex + 1}`;
            template.querySelector('.remove-fatura').style.display = 'inline-block';
            
            // Atualizar campos
            const inputs = template.querySelectorAll('input');
            inputs.forEach(input => {
                if (input.name) {
                    input.name = input.name.replace(/\[\d+\]/, `[${faturaIndex}]`);
                    input.value = '';
                }
            });
            
            // Limpar preview
            const preview = template.querySelector('.image-preview');
            preview.style.display = 'none';
            preview.querySelector('img').src = '';
            
            document.getElementById('faturasContainer').appendChild(template);
            
            // Adicionar event listeners
            setupFaturaEventListeners(template);
            updateFaturaCount();
            
            // Scroll para nova fatura
            template.scrollIntoView({ behavior: 'smooth' });
        }

        function setupFaturaEventListeners(faturaElement) {
            // Remover fatura
            const removeBtn = faturaElement.querySelector('.remove-fatura');
            removeBtn.addEventListener('click', function() {
                faturaElement.remove();
                updateFaturaCount();
                reindexFaturas();
            });
            
            // Capturar imagem
            const captureBtn = faturaElement.querySelector('.capture-btn');
            captureBtn.addEventListener('click', function() {
                currentFaturaTarget = faturaElement;
                new bootstrap.Modal(document.getElementById('cameraModal')).show();
            });
            
            // Upload de arquivo
            const fileInput = faturaElement.querySelector('.file-input');
            fileInput.addEventListener('change', function(e) {
                handleFileUpload(e, faturaElement);
            });
            
            // OCR
            const ocrBtn = faturaElement.querySelector('.ocr-btn');
            ocrBtn.addEventListener('click', function() {
                performOCR(faturaElement);
            });
        }

        function handleFileUpload(event, faturaElement) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    showImagePreview(faturaElement, e.target.result);
                };
                reader.readAsDataURL(file);
            }
        }

        function showImagePreview(faturaElement, imageSrc) {
            const preview = faturaElement.querySelector('.image-preview');
            const img = preview.querySelector('img');
            const hiddenInput = faturaElement.querySelector('.imagem-hidden');
            
            img.src = imageSrc;
            preview.style.display = 'block';
            hiddenInput.value = imageSrc;
        }

        async function startModalCamera() {
            try {
                const constraints = {
                    video: { facingMode: { ideal: 'environment' } },
                    audio: false
                };
                
                stream = await navigator.mediaDevices.getUserMedia(constraints);
                document.getElementById('modalVideo').srcObject = stream;
            } catch (err) {
                console.error('Erro ao acessar câmera:', err);
                showToast('Erro ao acessar a câmera', 'error');
            }
        }

        function stopModalCamera() {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
                stream = null;
            }
        }

        function captureFromModal() {
            const video = document.getElementById('modalVideo');
            const canvas = document.getElementById('modalCanvas');
            
            if (video.readyState >= 2 && currentFaturaTarget) {
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0);
                
                const dataUrl = canvas.toDataURL('image/png');
                showImagePreview(currentFaturaTarget, dataUrl);
                
                // Fechar modal
                bootstrap.Modal.getInstance(document.getElementById('cameraModal')).hide();
                showToast('Imagem capturada com sucesso!', 'success');
            }
        }

        async function performOCR(faturaElement) {
            const img = faturaElement.querySelector('.image-preview img');
            const ocrBtn = faturaElement.querySelector('.ocr-btn');
            
            if (!img.src) return;
            
            ocrBtn.disabled = true;
            ocrBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Processando...';
            
            try {
                const worker = await Tesseract.createWorker();
                await worker.load();
                await worker.loadLanguage('por');
                await worker.initialize('por');
                
                const result = await worker.recognize(img.src);
                await worker.terminate();
                
                // Extrair dados
                const text = result.data.text;
                const fornecedor = extractFornecedor(text);
                const nif = extractNIF(text);
                const data = extractDate(text);
                const valor = extractValue(text);
                
                // Preencher campos
                if (fornecedor) faturaElement.querySelector('input[name*="[fornecedor]"]').value = fornecedor;
                if (nif) faturaElement.querySelector('input[name*="[nif]"]').value = nif;
                if (data) faturaElement.querySelector('input[name*="[data]"]').value = data;
                if (valor) faturaElement.querySelector('input[name*="[valor]"]').value = valor.replace(/[^\d,.-]/g, '').replace(',', '.');
                
                showToast('Dados reconhecidos e preenchidos!', 'success');
                
            } catch (err) {
                console.error('Erro OCR:', err);
                showToast('Erro ao processar imagem', 'error');
            } finally {
                ocrBtn.disabled = false;
                ocrBtn.innerHTML = '<i class="fas fa-eye me-1"></i>Reconhecer Dados (OCR)';
            }
        }

        function updateFaturaCount() {
            const count = document.querySelectorAll('.fatura-item').length;
            document.getElementById('faturaCount').textContent = count;
            
            // Mostrar/esconder botões de remover
            const faturas = document.querySelectorAll('.fatura-item');
            faturas.forEach((fatura, index) => {
                const removeBtn = fatura.querySelector('.remove-fatura');
                removeBtn.style.display = faturas.length > 1 ? 'inline-block' : 'none';
            });
        }

        function reindexFaturas() {
            const faturas = document.querySelectorAll('.fatura-item');
            faturas.forEach((fatura, index) => {
                fatura.setAttribute('data-index', index);
                fatura.querySelector('h5').innerHTML = `<i class="fas fa-file-invoice text-primary me-2"></i>Fatura #${index + 1}`;
                
                // Atualizar nomes dos campos
                const inputs = fatura.querySelectorAll('input');
                inputs.forEach(input => {
                    if (input.name) {
                        input.name = input.name.replace(/\[\d+\]/, `[${index}]`);
                    }
                });
            });
            faturaIndex = faturas.length - 1;
        }

        function setupNotificationToggles() {
            document.getElementById('enviar_email').addEventListener('change', function() {
                document.getElementById('email_config').style.display = this.checked ? 'block' : 'none';
            });
            
            document.getElementById('enviar_sms').addEventListener('change', function() {
                document.getElementById('sms_config').style.display = this.checked ? 'block' : 'none';
            });
        }

        function showToast(message, type = 'info') {
            const colors = { success: 'success', error: 'danger', info: 'primary' };
            const icons = { success: 'check-circle', error: 'exclamation-circle', info: 'info-circle' };
            
            const toastHtml = `
                <div class="toast align-items-center text-bg-${colors[type]} border-0" role="alert">
                    <div class="d-flex">
                        <div class="toast-body">
                            <i class="fas fa-${icons[type]} me-2"></i>${message}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                    </div>
                </div>
            `;
            
            let container = document.getElementById('toast-container');
            if (!container) {
                container = document.createElement('div');
                container.id = 'toast-container';
                container.className = 'toast-container position-fixed top-0 end-0 p-3';
                container.style.zIndex = '1055';
                document.body.appendChild(container);
            }
            
            container.insertAdjacentHTML('beforeend', toastHtml);
            const toastElement = container.lastElementChild;
            const toast = new bootstrap.Toast(toastElement, { delay: 4000 });
            toast.show();
            
            toastElement.addEventListener('hidden.bs.toast', () => toastElement.remove());
        }

        // Funções de extração OCR (simplificadas)
        function extractFornecedor(text) {
            const patterns = [
                /(?:empresa|fornecedor)[\s\:\-]*([^\n\r]{5,50})/i,
                /^([A-ZÁÀÂÃÇÉÊÍÓÔÕÚ][a-záàâãçéêíóôõú\s\.\-&]{4,})/im
            ];
            
            for (const pattern of patterns) {
                const match = text.match(pattern);
                if (match && match[1]) {
                    return match[1].trim().replace(/^\W+|\W+$/g, '');
                }
            }
            return null;
        }

        function extractNIF(text) {
            const match = text.match(/(?:NIF|NIPC)[\s\:\.\-]*(\d{9})/i);
            return match ? match[1] : null;
        }

        function extractDate(text) {
            const match = text.match(/(\d{2}[\/\-]\d{2}[\/\-]\d{4})|(\d{4}[\/\-]\d{2}[\/\-]\d{2})/);
            if (match) {
                const date = match[0].replace(/[\/\-]/g, '-');
                const parts = date.split('-');
                return parts[0].length === 4 ? date : `${parts[2]}-${parts[1]}-${parts[0]}`;
            }
            return null;
        }

        function extractValue(text) {
            const match = text.match(/(?:total|valor)[\s\:\-]*(\d+[,\.]\d{2})/i);
            return match ? match[1] : null;
        }

        // Inicializar event listeners na primeira fatura
        setupFaturaEventListeners(document.querySelector('.fatura-item'));

        
    </script>

    <style>
        .fatura-item {
            transition: all 0.3s ease;
        }
        .fatura-item:hover {
            transform: translateY(-2px);
        }
        .image-preview {
            transition: all 0.3s ease;
        }
        .btn {
            transition: all 0.3s ease;
        }
        .btn:hover {
            transform: translateY(-1px);
        }
        @media (max-width: 768px) {
            .container-fluid {
                padding-left: 15px;
                padding-right: 15px;
            }
            .card-body {
                padding: 20px !important;
            }
        }
    </style>
</body>
</html>@endsection