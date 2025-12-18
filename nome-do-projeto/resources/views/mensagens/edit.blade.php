@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-edit"></i>
                        Editar Mensagem
                    </h5>
                </div>
                
                <div class="card-body">
                    {{-- Alerta de erro --}}
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    {{-- Informação da mensagem original --}}
                    <div class="alert alert-info">
                        <strong>Mensagem original:</strong>
                        <p class="mb-0 mt-2">{{ $mensagem->conteudo }}</p>
                        <small class="text-muted">
                            Enviada em {{ $mensagem->created_at->format('d/m/Y H:i') }}
                        </small>
                    </div>
                    
                    {{-- Formulário de edição --}}
                    <form action="{{ route('mensagens.update', $mensagem) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="form-group">
                            <label for="conteudo">
                                Nova Mensagem
                                <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control @error('conteudo') is-invalid @enderror" 
                                      id="conteudo" 
                                      name="conteudo" 
                                      rows="5" 
                                      maxlength="5000"
                                      required
                                      autofocus>{{ old('conteudo', $mensagem->conteudo) }}</textarea>
                            
                            <small class="form-text text-muted">
                                <span id="charCount">{{ strlen($mensagem->conteudo) }}</span>/5000 caracteres
                            </small>
                            
                            @error('conteudo')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="alert alert-warning">
                            <i class="fas fa-info-circle"></i>
                            <strong>Atenção:</strong> A mensagem será marcada como editada.
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('mensagens.show', $mensagem->grupo_id) }}" 
                               class="btn btn-secondary">
                                <i class="fas fa-times"></i>
                                Cancelar
                            </a>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i>
                                Salvar Alterações
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('conteudo').addEventListener('input', function() {
    document.getElementById('charCount').textContent = this.value.length;
});
</script>
@endsection