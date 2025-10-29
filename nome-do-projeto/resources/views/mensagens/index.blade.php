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
                        @php
                            $totalNaoLidas = $grupos->sum('nao_lidas');
                        @endphp
                        @if($totalNaoLidas > 0)
                            <span class="badge badge-danger ml-2 pulse">
                                {{ $totalNaoLidas }}
                            </span>
                        @endif
                    </h4>
                </div>

                <div class="card-body p-0">
                    @if($grupos->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($grupos as $grupo)
                                <a href="{{ route('mensagens.show', $grupo) }}" 
                                   class="list-group-item list-group-item-action grupo-item">
                                    <div class="d-flex w-100 justify-content-between align-items-center py-2">
                                        <div class="d-flex align-items-center flex-grow-1 min-width-0">
                                            <!-- Ícone do grupo -->
                                            <div class="bg-{{ $grupo->cor }} text-white rounded-circle d-flex align-items-center justify-content-center mr-3 grupo-icone flex-shrink-0" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="fas fa-{{ $grupo->icone }} fa-lg"></i>
                                            </div>
                                            
                                            <!-- Informações do grupo -->
                                            <div class="flex-grow-1 min-width-0">
                                                <h5 class="mb-1 text-truncate font-weight-bold">
                                                    {{ $grupo->nome }}
                                                </h5>
                                                
                                                @if($grupo->ultimaMensagem)
                                                    <p class="mb-1 text-muted small text-truncate">
                                                        <strong>{{ $grupo->ultimaMensagem->user->name }}:</strong>
                                                        {{ Str::limit($grupo->ultimaMensagem->conteudo, 60) }}
                                                    </p>
                                                    <small class="text-muted">
                                                        <i class="far fa-clock"></i>
                                                        {{ $grupo->ultimaMensagem->created_at->diffForHumans() }}
                                                    </small>
                                                @else
                                                    <p class="mb-1 text-muted small">
                                                        <i class="fas fa-info-circle"></i>
                                                        Nenhuma mensagem ainda
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        
                                        <!-- Badges laterais -->
                                        <div class="text-right ml-3 flex-shrink-0">
                                            <div class="mb-2">
                                                <span class="badge badge-secondary">
                                                    <i class="fas fa-users"></i> {{ $grupo->membros->count() }}
                                                </span>
                                            </div>
                                            @if($grupo->nao_lidas > 0)
                                                <span class="badge badge-danger badge-pill animate-badge">
                                                    {{ $grupo->nao_lidas }}
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
.grupo-item {
    transition: all 0.2s ease;
    border-left: 4px solid transparent;
}

.grupo-item:hover {
    transform: translateX(5px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    border-left-color: #007bff;
    background-color: #f8f9fa;
}

.grupo-icone {
    transition: transform 0.2s ease;
}

.grupo-item:hover .grupo-icone {
    transform: scale(1.1) rotate(5deg);
}

.min-width-0 {
    min-width: 0;
}

.pulse {
    animation: pulse 2s infinite;
}

.animate-badge {
    animation: bounce 1s ease infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

@keyframes bounce {
    0%, 100% {
        transform: translateY(0);
    }
    50% {
        transform: translateY(-3px);
    }
}

.text-truncate {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}
</style>
@endsection