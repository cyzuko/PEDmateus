@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Nova Fatura</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('faturas.store') }}" enctype="multipart/form-data">
                        @csrf

                        <!-- Fornecedor -->
                        <div class="form-group row mb-3">
                            <label for="fornecedor" class="col-md-4 col-form-label text-md-right">Fornecedor</label>
                            <div class="col-md-6">
                                <input id="fornecedor" type="text" class="form-control @error('fornecedor') is-invalid @enderror" name="fornecedor" value="{{ old('fornecedor') }}" required>
                                @error('fornecedor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- NIF -->
                        <div class="form-group row mb-3">
                            <label for="nif" class="col-md-4 col-form-label text-md-right">NIF</label>
                            <div class="col-md-6">
                                <input id="nif" type="text" class="form-control @error('nif') is-invalid @enderror" name="nif" value="{{ old('nif') }}" placeholder="ex: 123456789">
                                @error('nif')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Data -->
                        <div class="form-group row mb-3">
                            <label for="data" class="col-md-4 col-form-label text-md-right">Data</label>
                            <div class="col-md-6">
                                <input id="data" type="date" class="form-control @error('data') is-invalid @enderror" name="data" value="{{ old('data') }}" required>
                                @error('data')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Valor -->
                        <div class="form-group row mb-3">
                            <label for="valor" class="col-md-4 col-form-label text-md-right">Valor</label>
                            <div class="col-md-6">
                                <input id="valor" type="number" step="0.01" class="form-control @error('valor') is-invalid @enderror" name="valor" value="{{ old('valor') }}" required>
                                @error('valor')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Captura de Imagem -->
                        <div class="form-group row mb-3">
                            <label for="imagem" class="col-md-4 col-form-label text-md-right">Imagem da Fatura</label>
                            <div class="col-md-6">
                                <!-- Botão para Capturar Imagem -->
                                <button type="button" id="captureButton" class="btn btn-primary mb-2">Capturar Imagem</button>

                                <!-- Elemento de Vídeo -->
                                <video id="video" width="100%" height="auto" autoplay playsinline style="max-height: 300px;"></video>
                                <canvas id="canvas" style="display: none;"></canvas>

                                <!-- Mostrar imagem capturada -->
                                <div class="mt-3 text-center">
                                    <img id="capturedImage" src="#" alt="Imagem Capturada" style="display: none; max-width: 100%; margin-bottom: 15px;">
                                </div>

                                <input id="imagem" type="hidden" name="imagem">
                                <input type="file" id="fileImage" class="form-control mt-2" name="imagem_upload" accept="image/*">

                                <!-- Botão para OCR -->
                                <button type="button" id="ocrButton" class="btn btn-info mt-3" style="display: none;">
                                    <span class="spinner-border spinner-border-sm d-none" id="ocrSpinner" role="status" aria-hidden="true"></span>
                                    Reconhecer Dados (OCR)
                                </button>

                                <!-- Progresso do OCR -->
                                <div id="ocrProgress" class="progress mt-2" style="display: none;">
                                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
                                </div>

                                <!-- Resultados OCR -->
                                <div id="ocrResults" class="mt-3" style="display: none;">
                                    <h5>Dados Reconhecidos:</h5>
                                    <div class="alert alert-info">
                                        <p id="ocrFornecedor"><strong>Fornecedor:</strong> <span></span></p>
                                        <p id="ocrNif"><strong>NIF:</strong> <span></span></p>
                                        <p id="ocrData"><strong>Data:</strong> <span></span></p>
                                        <p id="ocrValor"><strong>Valor:</strong> <span></span></p>
                                        <button type="button" id="applyOcrButton" class="btn btn-sm btn-success">Aplicar Dados</button>
                                    </div>
                                </div>

                                @error('imagem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <!-- Botões -->
                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    Salvar Fatura
                                </button>
                                <a href="{{ route('faturas.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- OCR via Tesseract.js -->
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@4.1.1/dist/tesseract.min.js"></script>

<script>
const video = document.getElementById('video');
const captureButton = document.getElementById('captureButton');
const canvas = document.getElementById('canvas');
const capturedImage = document.getElementById('capturedImage');
const imagemInput = document.getElementById('imagem');
const fileImage = document.getElementById('fileImage');
const ocrButton = document.getElementById('ocrButton');
const ocrResults = document.getElementById('ocrResults');
const ocrSpinner = document.getElementById('ocrSpinner');
const ocrProgress = document.getElementById('ocrProgress');
const progressBar = document.querySelector('.progress-bar');

let stream;
let imageSource = null;

// Inicializa a câmera
// Inicializa a câmera com verificação para mobile
async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: {
                facingMode: { ideal: 'environment' }, // traseira em telemóvel
                width: { ideal: 1280 },
                height: { ideal: 720 }
            },
            audio: false
        });
        video.srcObject = stream;
    } catch (err) {
        alert("Erro ao acessar a câmera. Verifique as permissões do navegador.");
        console.error("Erro ao acessar a câmera: ", err);
    }
}

// Espera o vídeo carregar antes de capturar
captureButton.addEventListener('click', function() {
    if (video.readyState >= 2) {
        canvas.width = video.videoWidth;
        canvas.height = video.videoHeight;
        const ctx = canvas.getContext('2d');
        ctx.drawImage(video, 0, 0, canvas.width, canvas.height);

        const dataUrl = canvas.toDataURL('image/png');
        capturedImage.src = dataUrl;
        capturedImage.style.display = 'block';
        imagemInput.value = dataUrl;
        imageSource = 'camera';

        ocrButton.style.display = 'inline-block';
    } else {
        alert("A câmera ainda está carregando. Aguarde um momento e tente novamente.");
    }
});

fileImage.addEventListener('change', function(e) {
    if (e.target.files && e.target.files[0]) {
        const reader = new FileReader();

        reader.onload = function(event) {
            capturedImage.src = event.target.result;
            capturedImage.style.display = 'block';
            imageSource = 'file';
            ocrButton.style.display = 'inline-block';
        }

        reader.readAsDataURL(e.target.files[0]);
    }
});

ocrButton.addEventListener('click', async function() {
    ocrSpinner.classList.remove('d-none');
    ocrProgress.style.display = 'block';
    progressBar.style.width = '0%';

    try {
        const worker = await Tesseract.createWorker({
            logger: m => {
                if (m.status === 'recognizing text') {
                    progressBar.style.width = `${Math.round(m.progress * 100)}%`;
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

        document.querySelector('#ocrFornecedor span').textContent = fornecedor || 'Não identificado';
        document.querySelector('#ocrNif span').textContent = nif || 'Não identificado';
        document.querySelector('#ocrData span').textContent = data || 'Não identificado';
        document.querySelector('#ocrValor span').textContent = valor || 'Não identificado';

        ocrResults.style.display = 'block';
    } catch (err) {
        alert('Erro ao processar a imagem. Tente novamente.');
    } finally {
        ocrSpinner.classList.add('d-none');
        ocrProgress.style.display = 'none';
    }
});

document.getElementById('applyOcrButton').addEventListener('click', function() {
    const fornecedor = document.querySelector('#ocrFornecedor span').textContent;
    const nif = document.querySelector('#ocrNif span').textContent;
    const data = document.querySelector('#ocrData span').textContent;
    const valor = document.querySelector('#ocrValor span').textContent;

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
        document.getElementById('valor').value = valor.replace('R$', '').trim().replace(',', '.');
    }
});

// Extrações
function extractFornecedor(text) {
    const regex = /(?:fornecedor|empresa|emitente|nome)\s*[:\-\s]?\s*([\w\s\.\-]+(?:\s+[a-zA-Z]+)*\w+)/i;
    const match = text.match(regex);
    return match ? match[1].trim() : null;
}

// Extração de NIF (adicionada)
function extractNIF(text) {
    // Procura por padrões de NIF
    // Portugal: 9 dígitos
    const regex = /(?:NIF|NIPC|Contribuinte|Nr\.?\s*Contribuinte|Número\s*(?:de\s*)?Contribuinte|NPC|Tax\s*ID)[\s\:\.\-]*(\d{9})/i;
    const match = text.match(regex);
    return match ? match[1].trim() : null;
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
        return `R$ ${max.toFixed(2).replace('.', ',')}`;
    }
    return null;
}

startCamera();
</script>
@endsection