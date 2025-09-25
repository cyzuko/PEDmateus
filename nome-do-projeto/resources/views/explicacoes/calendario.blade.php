@extends('layouts.app')

@section('title', 'Calendário de Explicações')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3>
                        <i class="fas fa-calendar mr-2"></i>
                        Calendário de Explicações - {{ date('F Y', mktime(0, 0, 0, $mesAtual, 1, $anoAtual)) }}
                    </h3>
                    <div class="btn-group">
                        <a href="{{ route('explicacoes.create') }}" class="btn btn-success">
                            <i class="fas fa-plus"></i> Nova Explicação
                        </a>
                        <a href="{{ route('explicacoes.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Lista
                        </a>
                    </div>
                </div>

                <div class="card-body">
                    <!-- Filtros rápis -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label>Mês:</label>
                            <select class="form-control" id="filtroMes">
                                @php
                                    $mesesNomes = [
                                        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril',
                                        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                                        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                                    ];
                                @endphp
                                @for($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ $mesAtual == $i ? 'selected' : '' }}>
                                        {{ $mesesNomes[$i] }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Ano:</label>
                            <select class="form-control" id="filtroAno">
                                @for($ano = date('Y') - 1; $ano <= date('Y') + 2; $ano++)
                                    <option value="{{ $ano }}" {{ $anoAtual == $ano ? 'selected' : '' }}>
                                        {{ $ano }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Status:</label>
                            <select class="form-control" id="filtroStatus">
                                <option value="">Todos</option>
                                <option value="agendada">Agendadas</option>
                                <option value="confirmada">Confirmadas</option>
                                <option value="concluida">Concluídas</option>
                                <option value="cancelada">Canceladas</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label>Aprovação:</label>
                            <select class="form-control" id="filtroAprovacao">
                                <option value="">Todas</option>
                                <option value="pendente">Pendentes</option>
                                <option value="aprovada">Aprovadas</option>
                                <option value="rejeitada">Rejeitadas</option>
                            </select>
                        </div>
                    </div>

                    <!-- Navegação rápida do mês -->
                    <div class="row mb-3">
                        <div class="col-12 text-center">
                            <div class="btn-group">
                                <a href="{{ route('explicacoes.calendario', ['mes' => $mesAnterior, 'ano' => $anoAnterior]) }}" 
                                   class="btn btn-outline-secondary">
                                    <i class="fas fa-chevron-left"></i> Mês Anterior
                                </a>
                                <a href="{{ route('explicacoes.calendario') }}" 
                                   class="btn btn-outline-primary">
                                    <i class="fas fa-home"></i> Mês Atual
                                </a>
                                <a href="{{ route('explicacoes.calendario', ['mes' => $mesProximo, 'ano' => $anoProximo]) }}" 
                                   class="btn btn-outline-secondary">
                                    Próximo Mês <i class="fas fa-chevron-right"></i>
                                </a>
                            </div>
                        </div>
                    </div>

                    <!-- Vista mensal -->
                    <div class="table-responsive">
                        <table class="table table-bordered calendario-table">
                            <thead class="thead-dark">
                                <tr>
                                    <th>Dom</th>
                                    <th>Seg</th>
                                    <th>Ter</th>
                                    <th>Qua</th>
                                    <th>Qui</th>
                                    <th>Sex</th>
                                    <th>Sáb</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $primeiroDia = date('w', mktime(0, 0, 0, $mesAtual, 1, $anoAtual));
                                    $diasNoMes = date('t', mktime(0, 0, 0, $mesAtual, 1, $anoAtual));
                                    $diaAtual = 1;
                                    $semanas = ceil(($diasNoMes + $primeiroDia) / 7);
                                    
                                    // Para debug
                                    // dd("Mês: $mesAtual, Ano: $anoAtual, Primeiro dia: $primeiroDia, Dias no mês: $diasNoMes");
                                @endphp

                                @for($semana = 0; $semana < $semanas; $semana++)
                                    <tr>
                                        @for($diaSemana = 0; $diaSemana < 7; $diaSemana++)
                                            <td class="calendario-dia" style="height: 140px; vertical-align: top;">
                                                @if(($semana == 0 && $diaSemana >= $primeiroDia) || ($semana > 0 && $diaAtual <= $diasNoMes))
                                                    @if($diaAtual <= $diasNoMes)
                                                        <div class="dia-numero">
                                                            <strong>{{ $diaAtual }}</strong>
                                                            @if($diaAtual == date('j') && $mesAtual == date('n') && $anoAtual == date('Y'))
                                                                <span class="badge badge-primary badge-sm">Hoje</span>
                                                            @endif
                                                        </div>
                                                        
                                                        <!-- Explicações do dia -->
                                                        @php
                                                            $dataCompleta = sprintf('%04d-%02d-%02d', $anoAtual, $mesAtual, $diaAtual);
                                                            $explicacoesDia = $explicacoes->filter(function($explicacao) use ($dataCompleta) {
                                                                return $explicacao->data_explicacao === $dataCompleta;
                                                            });
                                                        @endphp

                                                        @foreach($explicacoesDia as $explicacao)
                                                            @php
                                                                // Definir classe CSS baseada no status e aprovação
                                                                $classeCSS = 'secondary';
                                                                $icone = 'fas fa-question-circle';
                                                                
                                                                if ($explicacao->aprovacao_admin === 'pendente') {
                                                                    $classeCSS = 'secondary';
                                                                    $icone = 'fas fa-clock';
                                                                } elseif ($explicacao->aprovacao_admin === 'rejeitada') {
                                                                    $classeCSS = 'danger';
                                                                    $icone = 'fas fa-times-circle';
                                                                } elseif ($explicacao->aprovacao_admin === 'aprovada') {
                                                                    switch($explicacao->status) {
                                                                        case 'agendada':
                                                                            $classeCSS = 'warning';
                                                                            $icone = 'fas fa-calendar-alt';
                                                                            break;
                                                                        case 'confirmada':
                                                                            $classeCSS = 'info';
                                                                            $icone = 'fas fa-check-circle';
                                                                            break;
                                                                        case 'concluida':
                                                                            $classeCSS = 'success';
                                                                            $icone = 'fas fa-check-double';
                                                                            break;
                                                                        case 'cancelada':
                                                                            $classeCSS = 'dark';
                                                                            $icone = 'fas fa-ban';
                                                                            break;
                                                                    }
                                                                }
                                                            @endphp
                                                            <div class="explicacao-item mb-1" 
                                                                 data-status="{{ $explicacao->status }}" 
                                                                 data-aprovacao="{{ $explicacao->aprovacao_admin }}">
                                                                <a href="{{ route('explicacoes.show', $explicacao->id) }}" 
                                                                   class="btn btn-{{ $classeCSS }} btn-sm btn-block text-truncate" 
                                                                   title="{{ $explicacao->disciplina }} - {{ $explicacao->nome_aluno }} ({{ substr($explicacao->hora_inicio, 0, 5) }}) - Status: {{ ucfirst($explicacao->status) }} - Aprovação: {{ ucfirst($explicacao->aprovacao_admin) }}{{ $explicacao->aprovacao_admin === 'rejeitada' && $explicacao->motivo_rejeicao ? ' - ' . $explicacao->motivo_rejeicao : '' }}">
                                                                    <i class="{{ $icone }} me-1"></i>
                                                                    {{ substr($explicacao->disciplina, 0, 8) }}{{ strlen($explicacao->disciplina) > 8 ? '...' : '' }}
                                                                    <br><small>{{ substr($explicacao->hora_inicio, 0, 5) }}</small>
                                                                </a>
                                                            </div>
                                                        @endforeach

                                                        @php $diaAtual++; @endphp
                                                    @endif
                                                @endif
                                            </td>
                                        @endfor
                                    </tr>
                                @endfor
                            </tbody>
                        </table>
                    </div>

                    <!-- Legenda expandida -->
                    <div class="row mt-3">
                        <div class="col-12">
                            <h6>Legenda por Status:</h6>
                            <div class="mb-2">
                                <span class="badge badge-warning mr-2"><i class="fas fa-calendar-alt"></i> Agendada (Aprovada)</span>
                                <span class="badge badge-info mr-2"><i class="fas fa-check-circle"></i> Confirmada</span>
                                <span class="badge badge-success mr-2"><i class="fas fa-check-double"></i> Concluída</span>
                                <span class="badge badge-dark mr-2"><i class="fas fa-ban"></i> Cancelada</span>
                            </div>
                            <h6>Legenda por Aprovação:</h6>
                            <div>
                                <span class="badge badge-secondary mr-2"><i class="fas fa-clock"></i> Pendente de Aprovação</span>
                                <span class="badge badge-danger mr-2"><i class="fas fa-times-circle"></i> Rejeitada</span>
                            </div>
                        </div>
                    </div>

                    <!-- Resumo completo -->
                    <div class="row mt-4">
                        <div class="col-md-2">
                            <div class="card bg-secondary text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $explicacoes->where('aprovacao_admin', 'pendente')->count() }}</h5>
                                    <p class="mb-0 small">Pendentes</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-warning text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $explicacoes->where('status', 'agendada')->where('aprovacao_admin', 'aprovada')->count() }}</h5>
                                    <p class="mb-0 small">Agendadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-info text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $explicacoes->where('status', 'confirmada')->count() }}</h5>
                                    <p class="mb-0 small">Confirmadas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-success text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $explicacoes->where('status', 'concluida')->count() }}</h5>
                                    <p class="mb-0 small">Concluídas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-dark text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $explicacoes->where('status', 'cancelada')->count() }}</h5>
                                    <p class="mb-0 small">Canceladas</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <div class="card bg-danger text-white">
                                <div class="card-body text-center">
                                    <h5>{{ $explicacoes->where('aprovacao_admin', 'rejeitada')->count() }}</h5>
                                    <p class="mb-0 small">Rejeitadas</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Elementos
    const filtroMes = document.getElementById('filtroMes');
    const filtroAno = document.getElementById('filtroAno');
    const filtroStatus = document.getElementById('filtroStatus');
    const filtroAprovacao = document.getElementById('filtroAprovacao');

    // Função para atualizar calendário (mês/ano) - Melhorada
    function atualizarCalendario() {
        const mes = filtroMes.value;
        const ano = filtroAno.value;
        
        if (mes && ano) {
            // Criar URL com parâmetros
            const baseUrl = '{{ route("explicacoes.calendario") }}';
            const url = `${baseUrl}?mes=${mes}&ano=${ano}`;
            
            // Mostrar indicador de carregamento
            document.body.style.cursor = 'wait';
            
            // Redirecionar
            window.location.href = url;
        }
    }

    // Event listeners para filtros de mês/ano - Sem debounce para melhor UX
    if (filtroMes) {
        filtroMes.addEventListener('change', atualizarCalendario);
    }
    if (filtroAno) {
        filtroAno.addEventListener('change', atualizarCalendario);
    }

    // Filtros locais (status e aprovação) - Mantém a funcionalidade original
    function aplicarFiltros() {
        const statusSelecionado = filtroStatus ? filtroStatus.value : '';
        const aprovacaoSelecionada = filtroAprovacao ? filtroAprovacao.value : '';
        
        const explicacoes = document.querySelectorAll('.explicacao-item');
        
        explicacoes.forEach(function(item) {
            const status = item.getAttribute('data-status');
            const aprovacao = item.getAttribute('data-aprovacao');
            let mostrar = true;
            
            // Filtrar por status
            if (statusSelecionado && status !== statusSelecionado) {
                mostrar = false;
            }
            
            // Filtrar por aprovação
            if (aprovacaoSelecionada && aprovacao !== aprovacaoSelecionada) {
                mostrar = false;
            }
            
            // Mostrar/ocultar
            if (mostrar) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Event listeners para filtros locais
    if (filtroStatus) {
        filtroStatus.addEventListener('change', aplicarFiltros);
    }
    if (filtroAprovacao) {
        filtroAprovacao.addEventListener('change', aplicarFiltros);
    }

    // Inicializar tooltips (se Bootstrap estiver disponível)
    if (typeof bootstrap !== 'undefined') {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
        tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
    }

    // Restaurar cursor normal quando página carrega
    document.body.style.cursor = 'default';
});
</script>

<style>
.calendario-table {
    font-size: 0.85em;
}

.calendario-dia {
    position: relative;
    padding: 3px;
}

.dia-numero {
    margin-bottom: 3px;
}

.explicacao-item .btn {
    font-size: 0.7em;
    padding: 1px 4px;
    line-height: 1.1;
    margin-bottom: 1px;
}

.explicacao-item .btn small {
    font-size: 0.9em;
}

.badge-sm {
    font-size: 0.6em;
}

.calendario-dia:hover {
    background-color: #f8f9fa;
}

/* Cores específicas para estados mistos */
.btn-dark {
    background-color: #6c757d;
    border-color: #6c757d;
}

/* Indicador de carregamento */
body[style*="cursor: wait"] {
    pointer-events: none;
}

body[style*="cursor: wait"]:after {
    content: 'Carregando...';
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: rgba(0,0,0,0.8);
    color: white;
    padding: 10px 20px;
    border-radius: 5px;
    z-index: 9999;
}

@media (max-width: 768px) {
    .calendario-table {
        font-size: 0.75em;
    }
    
    .calendario-dia {
        height: 120px !important;
    }
    
    .explicacao-item .btn {
        font-size: 0.65em;
        padding: 1px 2px;
    }
    
    .row.mt-4 .col-md-2 {
        margin-bottom: 10px;
    }
}
</style>
@endsection