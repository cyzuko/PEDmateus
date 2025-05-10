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

                        <div class="form-group row mb-3">
                            <label for="imagem" class="col-md-4 col-form-label text-md-right">Imagem da Fatura</label>

                            <div class="col-md-6">
                                <!-- Botão para capturar a imagem -->
                                <button type="button" id="captureButton" class="btn btn-primary">Capturar Imagem</button>
                                
                                <!-- O elemento de vídeo será usado para mostrar a câmera -->
                                <video id="video" width="100%" height="auto" autoplay></video>
                                <canvas id="canvas" style="display: none;"></canvas>
                                <img id="capturedImage" src="#" alt="Imagem Capturada" style="display: none; max-width: 100%; margin-top: 15px;">
                                
                                <input id="imagem" type="hidden" name="imagem">

                                @error('imagem')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

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

<script>
// Função para capturar imagem da câmera
const video = document.getElementById('video');
const captureButton = document.getElementById('captureButton');
const canvas = document.getElementById('canvas');
const capturedImage = document.getElementById('capturedImage');
const imagemInput = document.getElementById('imagem');

let stream;

// Inicializar a câmera
async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({
            video: true
        });
        video.srcObject = stream;
    } catch (err) {
        console.log("Erro ao acessar a câmera: ", err);
    }
}

// Captura a imagem quando o botão for pressionado
captureButton.addEventListener('click', function() {
    canvas.width = video.videoWidth;
    canvas.height = video.videoHeight;
    const ctx = canvas.getContext('2d');
    ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
    
    // Exibir a imagem capturada
    const dataUrl = canvas.toDataURL('image/png');
    capturedImage.src = dataUrl;
    capturedImage.style.display = 'block';

    // Enviar a imagem como base64 para o servidor
    imagemInput.value = dataUrl;  // A imagem em base64 será enviada
});

// Iniciar a câmera assim que a página for carregada
window.onload = startCamera;
</script>

@endsection
