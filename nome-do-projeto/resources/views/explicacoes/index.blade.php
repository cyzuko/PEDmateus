@extends('layouts.app')

@section('title', 'Horários de Explicações')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center py-3">
                    <h3 class="card-title mb-0 font-weight-bold">
                        <i class="fas fa-calendar-alt mr-2 text-primary"></i>
                        Horários de Explicações
                    </h3>
                    <div>
                    </div>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle mr-1"></i>{{ session('success') }}
                            <button type="button" class="close" data-dismiss="alert">
                                <span>&times;</span>
                            </button>
                        </div>
                    @endif

                    <!-- Cards de Estatísticas -->
                    @if(isset($stats))
                    <div class="row mb-4">
                        <div class="col">
                            <div class="stat-card stat-primary">
                                <div class="stat-icon">
                                    <i class="fas fa-calendar-plus"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Total</div>
                                    <div class="stat-value">{{ $stats['total'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card stat-warning">
                                <div class="stat-icon">
                                    <i class="fas fa-clock"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Pendentes</div>
                                    <div class="stat-value">{{ $stats['pendentes'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card stat-success">
                                <div class="stat-icon">
                                    <i class="fas fa-check"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Aprovadas</div>
                                    <div class="stat-value">{{ $stats['aprovadas'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card stat-danger">
                                <div class="stat-icon">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Rejeitadas</div>
                                    <div class="stat-value">{{ $stats['rejeitadas'] }}</div>
                                </div>
                            </div>
                        </div>
                        <div class="col">
                            <div class="stat-card stat-info">
                                <div class="stat-icon">
                                    <i class="fas fa-check-double"></i>
                                </div>
                                <div class="stat-content">
                                    <div class="stat-label">Concluídas</div>
                                    <div class="stat-value">{{ $explicacoes->where('status', 'concluida')->count() }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                    <!-- Filtros -->
                    <div class="filters-section mb-4">
                        <div class="row g-2">
                            <div class="col-md-2">
                                <select class="form-control form-control-sm" id="filtroStatus">
                                    <option value="">Todos os status</option>
                                    <option value="agendada">Agendadas</option>
                                    <option value="confirmada">Confirmadas</option>
                                    <option value="concluida">Concluídas</option>
                                    <option value="cancelada">Canceladas</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <select class="form-control form-control-sm" id="filtroAprovacao">
                                    <option value="">Status de Aprovação</option>
                                    <option value="pendente">Pendente</option>
                                    <option value="aprovada">Aprovada</option>
                                    <option value="rejeitada">Rejeitada</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <input type="date" class="form-control form-control-sm" id="filtroData">
                            </div>
                            <div class="col-md-4">
                                <input type="text" class="form-control form-control-sm" id="filtroDisciplina" placeholder="Filtrar por disciplina">
                            </div>
                            <div class="col-md-2">
                                <button class="btn btn-sm btn-outline-secondary btn-block" onclick="limparFiltros()">
                                    <i class="fas fa-times mr-1"></i>Limpar
                                </button>
                            </div>
                        </div>
                    </div>

                    @if($explicacoes->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead>
                                    <tr>
                                        <th>Data</th>
                                        <th>Horário</th>
                                        <th>Disciplina</th>
                                        <th>Aluno</th>
                                        <th>Local</th>
                                        <th>Preço</th>
                                        <th>Status</th>
                                        <th>Aprovação</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($explicacoes as $explicacao)
                                        @php
                                            $statusExplicacao = $explicacao->status;
                                            if ($explicacao->aprovacao_admin === 'aprovada' && $explicacao->status === 'agendada') {
                                                $statusExplicacao = 'confirmada';
                                            }
                                            
                                            $dataHora = strtotime($explicacao->data_explicacao . ' ' . $explicacao->hora_fim);
                                            $jaPassou = $dataHora < time();
                                            $podeSerEditada = !in_array($explicacao->status, ['concluida']);
                                            $podeSerCancelada = !$jaPassou && in_array($statusExplicacao, ['agendada', 'confirmada']);
                                            $podeSerConcluida = in_array($statusExplicacao, ['confirmada']) && !$jaPassou;
                                            
                                            $rowClass = '';
                                            if ($explicacao->aprovacao_admin === 'rejeitada') {
                                                $rowClass = 'row-rejeitada';
                                            } elseif ($explicacao->aprovacao_admin === 'aprovada') {
                                                $rowClass = 'row-aprovada';
                                            }
                                        @endphp
                                        
                                        <tr data-status="{{ $statusExplicacao }}" 
                                            data-aprovacao="{{ $explicacao->aprovacao_admin ?? 'pendente' }}"
                                            data-data="{{ $explicacao->data_explicacao }}" 
                                            data-disciplina="{{ strtolower($explicacao->disciplina) }}"
                                            class="{{ $rowClass }}">
                                            <td>
                                                <div class="font-weight-bold">{{ date('d/m/Y', strtotime($explicacao->data_explicacao)) }}</div>
                                                @if(date('Y-m-d') == $explicacao->data_explicacao)
                                                    <small><span class="badge badge-sm badge-primary">Hoje</span></small>
                                                @elseif(date('Y-m-d', strtotime('+1 day')) == $explicacao->data_explicacao)
                                                    <small><span class="badge badge-sm badge-info">Amanhã</span></small>
                                                @elseif($explicacao->data_explicacao < date('Y-m-d'))
                                                    <small><span class="badge badge-sm badge-secondary">Passou</span></small>
                                                @endif
                                            </td>
                                            <td>
                                                <div>{{ $explicacao->hora_inicio }} - {{ $explicacao->hora_fim }}</div>
                                                <small class="text-muted">
                                                    @php
                                                        $inicio = strtotime($explicacao->hora_inicio);
                                                        $fim = strtotime($explicacao->hora_fim);
                                                        $duracao = ($fim - $inicio) / 60;
                                                    @endphp
                                                    {{ $duracao }}min
                                                </small>
                                            </td>
                                            <td>
                                                <span class="badge badge-light">{{ $explicacao->disciplina }}</span>
                                            </td>
                                            <td>
                                                <div class="font-weight-medium">{{ $explicacao->nome_aluno }}</div>
                                                <small class="text-muted">{{ $explicacao->contacto_aluno }}</small>
                                            </td>
                                            <td>
                                                <small>{{ $explicacao->local }}</small>
                                            </td>
                                            <td>
                                                <span class="font-weight-bold text-success">€{{ number_format($explicacao->preco, 2) }}</span>
                                            </td>
                                            <td>
                                                @php
                                                    $statusLabels = [
                                                        'agendada' => ['label' => 'Agendada', 'class' => 'warning'],
                                                        'confirmada' => ['label' => 'Confirmada', 'class' => 'success'],
                                                        'concluida' => ['label' => 'Concluída', 'class' => 'info'],
                                                        'cancelada' => ['label' => 'Cancelada', 'class' => 'danger'],
                                                    ];
                                                    $statusInfo = $statusLabels[$statusExplicacao] ?? ['label' => $statusExplicacao, 'class' => 'secondary'];
                                                @endphp
                                                <span class="badge badge-{{ $statusInfo['class'] }} badge-pill">
                                                    @if($statusExplicacao === 'confirmada')
                                                        <i class="fas fa-check-circle mr-1"></i>
                                                    @endif
                                                    {{ $statusInfo['label'] }}
                                                </span>
                                            </td>
                                            <td>
                                                @if($explicacao->aprovacao_admin === 'pendente' || !isset($explicacao->aprovacao_admin))
                                                    <span class="badge badge-warning badge-pill">
                                                        <i class="fas fa-clock"></i> Pendente
                                                    </span>
                                                @elseif($explicacao->aprovacao_admin === 'aprovada')
                                                    <span class="badge badge-success badge-pill">
                                                        <i class="fas fa-check"></i> Aprovada
                                                    </span>
                                                    @if($explicacao->data_aprovacao)
                                                        <div><small class="text-muted">{{ $explicacao->data_aprovacao->format('d/m H:i') }}</small></div>
                                                    @endif
                                                    @if($explicacao->aprovadoPor)
                                                        <div><small class="text-muted">por {{ $explicacao->aprovadoPor->name }}</small></div>
                                                    @endif
                                                @elseif($explicacao->aprovacao_admin === 'rejeitada')
                                                    <span class="badge badge-danger badge-pill">
                                                        <i class="fas fa-times"></i> Rejeitada
                                                    </span>
                                                    @if($explicacao->data_aprovacao)
                                                        <div><small class="text-muted">{{ $explicacao->data_aprovacao->format('d/m H:i') }}</small></div>
                                                    @endif
                                                    @if($explicacao->motivo_rejeicao)
                                                        <div class="mt-1">
                                                            <button class="btn btn-xs btn-link text-danger p-0" 
                                                                    onclick="mostrarMotivoRejeicao('{{ addslashes($explicacao->motivo_rejeicao) }}')">
                                                                <i class="fas fa-info-circle"></i> Ver motivo
                                                            </button>
                                                        </div>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                <div class="action-buttons">
                                                    <a href="{{ route('explicacoes.show', $explicacao->id) }}" 
                                                       class="btn btn-sm btn-light" title="Ver detalhes">
                                                        <i class="fas fa-eye"></i>
                                                    </a>

                                                    @if($podeSerEditada)
                                                        <a href="{{ route('explicacoes.edit', $explicacao->id) }}" 
                                                           class="btn btn-sm btn-light" title="Editar">
                                                            <i class="fas fa-edit text-primary"></i>
                                                        </a>
                                                    @endif

                                                    @if($podeSerConcluida)
                                                        <form method="POST" action="{{ route('explicacoes.concluir', $explicacao->id) }}" class="d-inline">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-light" title="Marcar como concluída"
                                                                    onclick="return confirm('Marcar como concluída?')">
                                                                <i class="fas fa-check-double text-success"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($podeSerCancelada)
                                                        <form method="POST" action="{{ route('explicacoes.cancelar', $explicacao->id) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Cancelar esta explicação?')">
                                                            @csrf
                                                            @method('PATCH')
                                                            <button type="submit" class="btn btn-sm btn-light" title="Cancelar">
                                                                <i class="fas fa-ban text-warning"></i>
                                                            </button>
                                                        </form>
                                                    @endif

                                                    @if($explicacao->status !== 'concluida')
                                                        <form method="POST" action="{{ route('explicacoes.destroy', $explicacao->id) }}" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Eliminar esta explicação?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-light" title="Eliminar">
                                                                <i class="fas fa-trash text-danger"></i>
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

                        <div class="d-flex justify-content-center mt-3">
                            {{ $explicacoes->links() }}
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-calendar-times fa-4x text-muted mb-3"></i>
                            <h5 class="text-muted">Nenhuma explicação encontrada</h5>
                            <p class="text-muted mb-4">Comece por criar a sua primeira explicação.</p>
                            <a href="{{ route('explicacoes.create') }}" class="btn btn-success">
                                <i class="fas fa-plus mr-1"></i>Nova Explicação
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="modalMotivoRejeicao" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-info-circle text-danger mr-2"></i>Motivo da Rejeição
                </h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger mb-3">
                    <strong>Motivo:</strong>
                    <p id="motivoRejeicaoTexto" class="mb-0 mt-2"></p>
                </div>
                <p class="text-muted small mb-0">
                    <i class="fas fa-lightbulb mr-1"></i>
                    Pode editar a explicação e submeter novamente.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
/* Stats Cards */
.stat-card {
    display: flex;
    align-items: center;
    padding: 1.25rem;
    border-radius: 10px;
    background: white;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    transition: all 0.2s;
}

.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 16px rgba(0,0,0,0.12);
}

.stat-icon {
    width: 52px;
    height: 52px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    font-size: 1.5rem;
    margin-right: 1rem;
}

.stat-content {
    flex: 1;
}

.stat-label {
    font-size: 0.75rem;
    color: #6c757d;
    text-transform: uppercase;
    font-weight: 600;
    letter-spacing: 0.5px;
    margin-bottom: 0.25rem;
}

.stat-value {
    font-size: 1.75rem;
    font-weight: 700;
    color: #212529;
}

.stat-primary .stat-icon { 
    background: #3b82f6;
    color: white;
}

.stat-warning .stat-icon { 
    background: #f59e0b;
    color: white;
}

.stat-success .stat-icon { 
    background: #10b981;
    color: white;
}

.stat-danger .stat-icon { 
    background: #ef4444;
    color: white;
}

.stat-info .stat-icon { 
    background: #06b6d4;
    color: white;
}

/* Table */
.table {
    font-size: 0.95rem;
}

.table thead th {
    border-bottom: 2px solid #dee2e6;
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: #495057;
    background: #f8f9fa;
}

.table tbody tr {
    transition: background-color 0.15s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.row-aprovada {
    background-color: rgba(212, 237, 218, 0.2);
}

.row-rejeitada {
    background-color: rgba(248, 215, 218, 0.2);
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.4em 0.7em;
    font-size: 0.85rem;
}

.badge-sm {
    font-size: 0.75rem;
    padding: 0.3em 0.6em;
}

.badge-pill {
    border-radius: 10rem;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    gap: 0.25rem;
    justify-content: center;
    flex-wrap: wrap;
}

.action-buttons .btn {
    padding: 0.375rem 0.5rem;
    border: 1px solid #dee2e6;
}

.action-buttons .btn:hover {
    background: #e9ecef;
}

.btn-xs {
    padding: 0.125rem 0.5rem;
    font-size: 0.75rem;
}

/* Filters */
.filters-section {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 6px;
}

.font-weight-medium {
    font-weight: 500;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.getElementById('filtroStatus').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroAprovacao').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroData').addEventListener('change', aplicarFiltros);
    document.getElementById('filtroDisciplina').addEventListener('input', aplicarFiltros);
});

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
        
        if (statusFiltro && status !== statusFiltro) mostrar = false;
        if (aprovacaoFiltro && aprovacao !== aprovacaoFiltro) mostrar = false;
        if (dataFiltro && data !== dataFiltro) mostrar = false;
        if (disciplinaFiltro && !disciplina.includes(disciplinaFiltro)) mostrar = false;
        
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
@endsection