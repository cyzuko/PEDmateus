@extends('layouts.app')

@section('title', 'Detalhes da Explicação')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>
                        <i class="fas fa-eye mr-2"></i>
                        Detalhes da Explicação
                    </h3>
                    <div class="btn-group">
                        @php
                            $dataHora = strtotime($explicacao->data_explicacao . ' ' . $explicacao->hora_fim);
                            $jaPassou = $dataHora < time();
                            $podeSerEditada = !$jaPassou && $explicacao->status !== 'cancelada';
                        @endphp
                        
                        @if($podeSerEditada)
                            <a href="{{ route('explicacoes.edit', $explicacao->id) }}" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Editar
                            </a>
                        @endif
                        <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Header com disciplina e status -->
                    <div class="row mb-4">
                        <div class="col-md-8">
                            <h4 class="mb-2">{{ $explicacao->disciplina }}</h4>
                            @php
                                $statusLabels = [
                                    'agendada' => 'Agendada',
                                    'confirmada' => 'Confirmada',
                                    'concluida' => 'Concluída',
                                    'cancelada' => 'Cancelada',
                                ];
                                $statusClasses = [
                                    'agendada' => 'warning',
                                    'confirmada' => 'info',
                                    'concluida' => 'success',
                                    'cancelada' => 'danger',
                                ];
                            @endphp
                            <span class="badge badge-{{ $statusClasses[$explicacao->status] ?? 'secondary' }} badge-lg p-2">
                                {{ $statusLabels[$explicacao->status] ?? $explicacao->status }}
                            </span>
                        </div>
                        <div class="col-md-4 text-right">
                            <h4 class="text-success mb-0">€{{ number_format($explicacao->preco, 2) }}</h4>
                            @php
                                $inicio = strtotime($explicacao->hora_inicio);
                                $fim = strtotime($explicacao->hora_fim);
                                $duracao = ($fim - $inicio) / 60; // em minutos
                            @endphp
                            <small class="text-muted">{{ $duracao }} minutos</small>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Data e Horário -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-calendar-alt text-primary mr-2"></i>
                                        Data e Horário
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Data:</strong> 
                                        {{ date('d/m/Y', strtotime($explicacao->data_explicacao)) }}
                                        @if(date('Y-m-d') == $explicacao->data_explicacao)
                                            <span class="badge badge-primary ml-1">Hoje</span>
                                        @elseif(date('Y-m-d', strtotime('+1 day')) == $explicacao->data_explicacao)
                                            <span class="badge badge-info ml-1">Amanhã</span>
                                        @elseif($explicacao->data_explicacao < date('Y-m-d'))
                                            <span class="badge badge-secondary ml-1">Passou</span>
                                        @endif
                                    </p>
                                    <p><strong>Horário:</strong> {{ $explicacao->hora_inicio }} às {{ $explicacao->hora_fim }}</p>
                                    <p class="mb-0"><strong>Duração:</strong> {{ $duracao }} minutos</p>
                                </div>
                            </div>
                        </div>

                        <!-- Local -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-map-marker-alt text-success mr-2"></i>
                                        Local
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p class="mb-0">
                                        <span class="badge badge-light p-2 h6">{{ $explicacao->local }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Dados do Aluno -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-user-graduate text-info mr-2"></i>
                                        Dados do Aluno
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Nome:</strong> {{ $explicacao->nome_aluno }}</p>
                                    <p class="mb-0"><strong>Contacto:</strong> 
                                        @if(filter_var($explicacao->contacto_aluno, FILTER_VALIDATE_EMAIL))
                                            <a href="mailto:{{ $explicacao->contacto_aluno }}">{{ $explicacao->contacto_aluno }}</a>
                                        @elseif(preg_match('/^[0-9\s\+\-\(\)]+$/', $explicacao->contacto_aluno))
                                            <a href="tel:{{ preg_replace('/\s+/', '', $explicacao->contacto_aluno) }}">{{ $explicacao->contacto_aluno }}</a>
                                        @else
                                            {{ $explicacao->contacto_aluno }}
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Informações Adicionais -->
                        <div class="col-md-6">
                            <div class="card bg-light mb-3">
                                <div class="card-header">
                                    <h5 class="mb-0">
                                        <i class="fas fa-info-circle text-warning mr-2"></i>
                                        Informações
                                    </h5>
                                </div>
                                <div class="card-body">
                                    <p><strong>Criado:</strong> {{ date('d/m/Y H:i', strtotime($explicacao->created_at)) }}</p>
                                    @if($explicacao->updated_at != $explicacao->created_at)
                                        <p class="mb-0"><strong>Atualizado:</strong> {{ date('d/m/Y H:i', strtotime($explicacao->updated_at)) }}</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Observações -->
                    @if($explicacao->observacoes)
                        <div class="card bg-light mb-3">
                            <div class="card-header">
                                <h5 class="mb-0">
                                    <i class="fas fa-sticky-note text-secondary mr-2"></i>
                                    Observações
                                </h5>
                            </div>
                            <div class="card-body">
                                <p class="mb-0">{{ $explicacao->observacoes }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Ações -->
                    <hr>
                    <div class="text-center">
                        @php
                            $podeSerCancelada = !$jaPassou && in_array($explicacao->status, ['agendada', 'confirmada']);
                        @endphp

                        <!-- Confirmar -->
                        @if($explicacao->status === 'agendada')
                            <form method="POST" action="{{ route('explicacoes.confirmar', $explicacao->id) }}" 
                                  style="display: inline;" class="mr-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Confirmar esta explicação?')">
                                    <i class="fas fa-check"></i> Confirmar Explicação
                                </button>
                            </form>
                        @endif

                        <!-- Concluir -->
                        @if($explicacao->status === 'confirmada' && !$jaPassou)
                            <form method="POST" action="{{ route('explicacoes.concluir', $explicacao->id) }}" 
                                  style="display: inline;" class="mr-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-success" 
                                        onclick="return confirm('Marcar como concluída?')">
                                    <i class="fas fa-check-double"></i> Marcar como Concluída
                                </button>
                            </form>
                        @endif

                        <!-- Cancelar -->
                        @if($podeSerCancelada)
                            <form method="POST" action="{{ route('explicacoes.cancelar', $explicacao->id) }}" 
                                  style="display: inline;" class="mr-2">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-warning" 
                                        onclick="return confirm('Tem certeza que deseja cancelar esta explicação?')">
                                    <i class="fas fa-times"></i> Cancelar Explicação
                                </button>
                            </form>
                        @endif

                        <!-- Eliminar -->
                        <form method="POST" action="{{ route('explicacoes.destroy', $explicacao->id) }}" 
                              style="display: inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger" 
                                    onclick="return confirm('Tem certeza que deseja eliminar esta explicação? Esta ação não pode ser desfeita.')">
                                <i class="fas fa-trash"></i> Eliminar
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection