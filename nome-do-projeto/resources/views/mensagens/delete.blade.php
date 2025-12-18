@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-danger">
                <div class="card-header bg-danger text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Confirmar Eliminação de Mensagem
                    </h5>
                </div>
                
                <div class="card-body">
                    <div class="alert alert-danger">
                        <h5 class="alert-heading">
                            <i class="fas fa-trash"></i>
                            Atenção!
                        </h5>
                        <p>Você está prestes a eliminar esta mensagem permanentemente.</p>
                        <hr>
                        <p class="mb-0">
                            <strong>Esta ação não pode ser desfeita!</strong>
                        </p>
                    </div>
                    
                    {{-- Pré-visualização da mensagem --}}
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <strong>Mensagem a ser eliminada:</strong>
                        </div>
                        <div class="card-body">
                            @if($mensagem->tipo === 'imagem' && $mensagem->arquivo_url)
                                <div class="mb-3">
                                    <img src="{{ asset('storage/' . $mensagem->arquivo_url) }}" 
                                         class="img-fluid rounded" 
                                         alt="Imagem da mensagem"
                                         style="max-height: 300px;">
                                </div>
                            @endif
                            
                            @if($mensagem->conteudo)
                                <p class="mb-2">{{ $mensagem->conteudo }}</p>
                            @endif
                            
                            <small class="text-muted">
                                <i class="fas fa-clock"></i>
                                Enviada em {{ $mensagem->created_at->format('d/m/Y H:i') }}
                                
                                @if($mensagem->editada)
                                    <span class="badge badge-info ml-2">
                                        <i class="fas fa-edit"></i> Editada
                                    </span>
                                @endif
                            </small>
                        </div>
                    </div>
                    
                    {{-- Botões de ação --}}
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('mensagens.show', $mensagem->grupo_id) }}" 
                           class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i>
                            Cancelar
                        </a>
                        
                        <form action="{{ route('mensagens.destroy', $mensagem) }}" 
                              method="POST" 
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            
                            <button type="submit" 
                                    class="btn btn-danger"
                                    onclick="return confirm('Tem certeza que deseja eliminar esta mensagem? Esta ação é irreversível!')">
                                <i class="fas fa-trash"></i>
                                Sim, Eliminar Mensagem
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection