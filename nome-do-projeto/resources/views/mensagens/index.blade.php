@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-comments"></i>
                        Os meus grupos
                    </h4>
                </div>

                <div class="card-body">
                    @if($grupos->count() > 0)
                        <div class="list-group">
                            @foreach($grupos as $grupo)
                                <a href="{{ route('mensagens.show', $grupo) }}" 
                                   class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between align-items-center">
                                        <div class="d-flex align-items-center">
                                            <div class="bg-{{ $grupo->cor }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3" 
                                                 style="width: 50px; height: 50px; min-width: 50px;">
                                                <i class="fas fa-{{ $grupo->icone }} fa-lg"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-1">{{ $grupo->nome }}</h5>
                                                @if($grupo->ultimaMensagem)
                                                    <p class="mb-1 text-muted small">
                                                        <strong>{{ $grupo->ultimaMensagem->user->name }}:</strong>
                                                        {{ Str::limit($grupo->ultimaMensagem->conteudo, 60) }}
                                                    </p>
                                                    <small class="text-muted">
                                                        {{ $grupo->ultimaMensagem->created_at->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <p class="mb-1 text-muted small">Nenhuma mensagem ainda</p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <div class="mb-2">
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-users"></i> {{ $grupo->membros->count() }}
                                                </span>
                                            </div>
                                            @if($grupo->nao_lidas > 0)
                                                <span class="badge badge-danger badge-pill">
                                                    {{ $grupo->nao_lidas }} nova(s)
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Você ainda não está em nenhum grupo</h5>
                            <p class="text-muted">Aguarde um administrador adicionar você a um grupo de conversa.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item-action:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: all 0.2s ease;
}
</style>
@endsection