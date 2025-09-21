@extends('layouts.app')

@section('title', 'Horários de Explicações')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">
                        <i class="fas fa-calendar-alt mr-2"></i>
                        Horários de Explicações
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('explicacoes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nova Explicação
                        </a>
                        <a href="{{ route('explicacoes.calendario') }}" class="btn btn-info">
                            <i class="fas fa-calendar"></i> Calendário
                        </a>
                        <a href="{{ route('explicacoes.disponibilidade') }}" class="btn btn-warning">
                            <i class="fas fa-clock"></i> Disponibilidade
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

                    <!-- Cards de Estatísticas com Status de Aprovação -->
                    @if(isset($stats))
                    <div class="row mb-4">
                        <div class="col-md-2">
                            <div class="info-box bg-primary">
                                <span class="info-box-icon"><i class="fas fa-calendar-plus"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Total</span>
                                    <span class="info-box-number">{{ $stats['total'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-warning">
                                <span class="info-box-icon"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Pendentes</span>
                                    <span class="info-box-number">{{ $stats['pendentes'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-success">
                                <span class="info-box-icon"><i class="fas fa-check"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Aprovadas</span>
                                    <span class="info-box-number">{{ $stats['aprovadas'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-danger">
                                <span class="info-box-icon"><i class="fas fa-times"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Rejeitadas</span>
                                    <span class="info-box-number">{{ $stats['rejeitadas'] }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="info-box bg-info">
                                <span class="info-box-icon"><i class="fas fa-check-double"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Concluídas</span>
                                    <span class="info-box-number">{{ $explicacoes->where('status', 'concluida')->count() }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Filtros -->
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <select class="form-control" id="filtroStatus">
                                <option value="">Todos os status</option>
                                <option value="agendada">Agendadas</option>
                                <option value="confirmada">Confirmadas</option>
                                <option value="concluida">Concluídas</option>
                                <option value="cancelada">Canceladas</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <select class="form-control" id="filtroAprovacao">
                                <option value="">Status de Aprovação</option>
                                <option value="pendente">Pendente</option>
                                <option value="aprovada">Aprovada</option>
                                <option value="rejeitada">Rejeitada</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="date" class="form-control" id="filtroData" placeholder="Filtrar por data">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" id="filtroDisciplina" placeholder="Filtrar por disciplina">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-secondary" onclick="limparFiltros()">
                                <i class="fas fa-times"></i> Limpar
                            </button>
                        </div>
                    </div>

                    @if($explicacoes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Data</th>
                                        <th>Horário</th>
                                        <th>Disciplina</th>
                                        <th>Aluno</th>
                                        <th>Local</th>
                                        <th>Preço</th>
                                        <th>Status</th>
                                        <th>Aprovação Admin</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($explicacoes as $explicacao)
                                        @php
                                            // Lógica para determinar o status da explicação
                                            $statusExplicacao = $explicacao->status;
                                            
                                            // Se foi aprovada pelo admin e ainda está como "agendada", muda para "confirmada"
                                            if ($explicacao->aprovacao_admin === 'aprovada' && $explicacao->status === 'agendada') {
                                                $statusExplicacao = 'confirmada';
                                            }
                                        @endphp
                                        
                                        <tr data-status="{{ $statusExplicacao }}" 
                                            data-aprovacao="{{ $explicacao->aprovacao_admin ?? 'pendente' }}"
                                            data-data="{{ $explicacao->data_explicacao }}" 
                                            data-disciplina="{{ strtolower($explicacao->disciplina) }}"
                                            class="{{ $explicacao->aprovacao_admin === 'rejeitada' ? 'table-danger-light' : ($explicacao->aprovacao_admin === 'aprovada' ? 'table-success-light' : '') }}">
                                            <td>
                                                <strong>{{ date('d/m/Y', strtotime($explicacao->data_explicacao)) }}</strong>
                                                @if(date('Y-m-d') == $explicacao->data_explicacao)
                                                    <span class="badge badge-primary ml-1">Hoje</span>
                                                @elseif(date('Y-m-d', strtotime('+1 day')) == $explicacao->data_explicacao)
                                                    <span class="badge badge-info ml-1">Amanhã</span>
                                                @elseif($explicacao->data_explicacao < date('Y-m-d'))
                                                    <span class="badge badge-secondary ml-1">Passou</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="text-muted">{{ $explicacao->hora_inicio }} - {{ $explicacao->hora_fim }}</span>
                                                <br>
                                                <small class="text-muted">
                                                    @php
                                                        $inicio = strtotime($explicacao->hora_inicio);
                                                        $fim = strtotime($explicacao->hora_fim);
                                                        $duracao = ($fim - $inicio) / 60; // em minutos
                                                    @endphp
                                                    ({{ $duracao }}min)
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light p-2">
                                                    {{ $explicacao->disciplina }}
                                                </span>
                                            </td>
                                            <td>
                                                <strong>{{ $explicacao->nome_aluno }}</strong>
                                                <br>
                                                <small class="text-muted">{{ $explicacao->contacto_aluno }}</small>
                                            </td>
                                            <td>{{ $explicacao->local }}</td>
                                            <td>
                                                <strong class="text-success">€{{ number_format($explicacao->preco, 2) }}</strong>
                                            </td>
                                            <td>
                                                @php
                                                    $statusLabels = [
                                                        'agendada' => 'Agendada',
                                                        'confirmada' => 'Confirmada',
                                                        'concluida' => 'Concluída',
                                                        'cancelada' => 'Cancelada',
                                                    ];
                                                    $statusClasses = [
                                                        'agendada' => 'warning',
                                                        'confirmada' => 'success',
                                                        'concluida' => 'info',
                                                        'cancelada' => 'danger',
                                                    ];
                                                @endphp
                                                <span class="badge badge-{{ $statusClasses[$statusExplicacao] ?? 'secondary' }}">
                                                    @if($statusExplicacao === 'confirmada')
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    @endif
                                                    {{ $statusLabels[$statusExplicacao] ?? $statusExplicacao }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($explicacao->aprovacao_admin === 'pendente' || !isset($explicacao->aprovacao_admin))
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-clock mr-1"></i>
                                                        Pendente
                                                    </span>
                                                @elseif($explicacao->aprovacao_admin === 'aprovada')
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check mr-1"></i>
                                                        Aprovada
                                                    </span>
                                                    @if($explicacao->data_aprovacao)
                                                        <br><small class="text-muted">{{ $explicacao->data_aprovacao->format('d/m H:i') }}</small>
                                                    @endif
                                                    @if($explicacao->aprovadoPor)
                                                        <br><small class="text-muted">por {{ $explicacao->aprovadoPor->name }}</small>
                                                    @endif
                                                @elseif($explicacao->aprovacao_admin === 'rejeitada')
                                                    <span class="badge badge-danger">
                                                        <i class="fas fa-times mr-1"></i>
                                                        Rejeitada
                                                    </span>
                                                    @if($explicacao->data_aprovacao)
                                                        <br><small class="text-muted">{{ $explicacao->data_aprovacao->format('d/m H:i') }}</small>
                                                    @endif
                                                    @if($explicacao->motivo_rejeicao)
                                                        <br>
                                                        <button class="btn btn-xs btn-outline-danger mt-1" 
                                                                onclick="mostrarMotivoRejeicao('{{ addslashes($explicacao->motivo_rejeicao) }}')">
                                                            <i class="fas fa-info-circle"></i> Ver Motivo
                                                        </button>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group btn-group-sm" role="group">
                                                    <!-- Ver detalhes -->
                                                    <a href="{{ route('explicacoes.show', $explicacao->id) }}" 
                                                       class="btn btn-outline-info" title="Ver detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @php
                                                        $dataHora = strtotime($explicacao->data_explicacao . ' ' . $explicacao->hora_fim);
                                                        $agora = time();
                                                        $jaPassou = $dataHora < $agora;
                                                        
                                                        // Lógica atualizada para considerar o status "confirmada"
                                                        $podeSerEditada = !in_array($explicacao->status, ['concluida']);
                                                        $podeSerCancelada = !$jaPassou && in_array($statusExplicacao, ['agendada', 'confirmada']);
                                                        $podeSerConcluida = in_array($statusExplicacao, ['confirmada']) && !$jaPassou;
                                                    @endphp
                                                    
                                                    <!-- Botão Editar -->
                                                    @if($podeSerEditada)
                                                        <a href="{{ route('explicacoes.edit', $explicacao->id) }}" 
                                                           class="btn btn-outline-primary" title="Editar">
                                                            <i class="fas fa-edit"></i>
                                                        </a>
                                                    @else
                                                        <span class="btn btn-outline-secondary disabled" title="Não pode editar ({{ $explicacao->status }})">
                                                            <i class="fas fa-edit"></i>
                                                        </span>
                                                    @endif

                                                    <!-- Marcar como concluída -->
                                                    @if($podeSerConcluida)
                                                        <form method="POST" action="{{ route('explicacoes.concluir', $explicacao->id) }}" 
                                                              style="display: inline;">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-success" title="Marcar como concluída"
                                                                    onclick="return confirm('Marcar esta explicação como concluída?')">
                                                                <i class="fas fa-check-double"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Cancelar -->
                                                    @if($podeSerCancelada)
                                                        <form method="POST" action="{{ route('explicacoes.cancelar', $explicacao->id) }}" 
                                                              style="display: inline;" 
                                                              onsubmit="return confirm('Tem certeza que deseja cancelar esta explicação?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-outline-warning" title="Cancelar">
                                                                <i class="fas fa-times"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    <!-- Eliminar -->
                                                    @if($explicacao->status !== 'concluida')
                                                        <form method="POST" action="{{ route('explicacoes.destroy', $explicacao->id) }}" 
                                                              style="display: inline;" 
                                                              onsubmit="return confirm('Tem certeza que deseja eliminar esta explicação? Esta ação não pode ser desfeita.')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-outline-danger" title="Eliminar">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Paginação -->
                        <div class="d-flex justify-content-center">
                            {{ $explicacoes->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-5x text-muted mb-3"></i>
                            <h4 class="text-muted">Nenhuma explicação encontrada</h4>
                            <p class="text-muted">Comece por criar a sua primeira explicação.</p>
                            <a href="{{ route('explicacoes.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Nova Explicação
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar motivo de rejeição -->
<div class="modal fade" id="modalMotivoRejeicao" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-danger mr-2"></i>
                    Motivo da Rejeição
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger">
                    <strong>Motivo:</strong>
                    <p id="motivoRejeicaoTexto" class="mb-0 mt-2"></p>
                </div>
                <p class="text-muted">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Pode editar a sua explicação e submeter novamente após corrigir os pontos mencionados.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<script>
// Filtros em tempo real
document.getElementById('filtroStatus').addEventListener('change', aplicarFiltros);
document.getElementById('filtroAprovacao').addEventListener('change', aplicarFiltros);
document.getElementById('filtroData').addEventListener('change', aplicarFiltros);
document.getElementById('filtroDisciplina').addEventListener('input', aplicarFiltros);

function aplicarFiltros() {
    const statusFiltro = document.getElementById('filtroStatus').value.toLowerCase();
    const aprovacaoFiltro = document.getElementById('filtroAprovacao').value.toLowerCase();
    const dataFiltro = document.getElementById('filtroData').value;
    const disciplinaFiltro = document.getElementById('filtroDisciplina').value.toLowerCase();
    
    const linhas = document.querySelectorAll('tbody tr');
    
    linhas.forEach(linha => {
        const status = linha.dataset.status;
        const aprovacao = linha.dataset.aprovacao;
        const data = linha.dataset.data;
        const disciplina = linha.dataset.disciplina;
        
        let mostrar = true;
        
        if (statusFiltro && status !== statusFiltro) {
            mostrar = false;
        }
        
        if (aprovacaoFiltro && aprovacao !== aprovacaoFiltro) {
            mostrar = false;
        }
        
        if (dataFiltro && data !== dataFiltro) {
            mostrar = false;
        }
        
        if (disciplinaFiltro && !disciplina.includes(disciplinaFiltro)) {
            mostrar = false;
        }
        
        linha.style.display = mostrar ? '' : 'none';
    });
}

function limparFiltros() {
    document.getElementById('filtroStatus').value = '';
    document.getElementById('filtroAprovacao').value = '';
    document.getElementById('filtroData').value = '';
    document.getElementById('filtroDisciplina').value = '';
    aplicarFiltros();
}

function mostrarMotivoRejeicao(motivo) {
    document.getElementById('motivoRejeicaoTexto').innerHTML = motivo;
    $('#modalMotivoRejeicao').modal('show');
}
</script>

<style>
.info-box {
    border-radius: 10px;
    box-shadow: 0 0 1px rgba(0,0,0,.125), 0 1px 3px rgba(0,0,0,.2);
    margin-bottom: 1rem;
}

.table-hover tbody tr:hover {
    background-color: rgba(0,123,255,.075);
}

.badge {
    font-size: 0.875em;
}

.btn-group-sm > .btn {
    margin: 0 1px;
}

.table-success-light {
    background-color: rgba(212, 237, 218, 0.3);
}

.table-danger-light {
    background-color: rgba(248, 215, 218, 0.3);
}

.btn-xs {
    padding: 0.125rem 0.25rem;
    font-size: 0.675rem;
}

/* Estilo especial para explicações confirmadas */
.badge-primary {
    background-color: #007bff !important;
}

.text-success {
    color: #28a745 !important;
}
</style>
@endsection