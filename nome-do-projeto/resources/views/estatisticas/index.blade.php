@extends('layouts.app')

@section('title', 'Estatísticas Detalhadas')

@section('content_header')
    <div class="content-header-modern">
        <div class="header-content">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-chart-line"></i>
                        Estatísticas Detalhadas
                    </h1>
                    <p class="page-subtitle">Análise por disciplina, dia da semana e período</p>
                </div>
                <div>
                    <a href="{{ route('estatisticas.pdf') }}" class="btn btn-light" style="background: white; color: #667eea; font-weight: 600; padding: 0.75rem 1.5rem; border-radius: 8px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem;">
                        <i class="fas fa-file-pdf"></i>
                        Exportar PDF
                    </a>
                </div>
            </div>
        </div>
    </div>
@stop

@section('content')

    

    <!-- Cards de Resumo Rápido -->
    <div class="quick-stats mb-4">
        <div class="row">
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card-mini stat-blue">
                    <div class="stat-icon-mini">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="stat-info-mini">
                        <div class="stat-value-mini">{{ $totalExplicacoes ?? 0 }}</div>
                        <div class="stat-label-mini">Total Explicações</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card-mini stat-green">
                    <div class="stat-icon-mini">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="stat-info-mini">
                        <div class="stat-value-mini">{{ isset($disciplinasDisponiveis) ? $disciplinasDisponiveis->count() : 0 }}</div>
                        <div class="stat-label-mini">Disciplinas Ativas</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card-mini stat-purple">
                    <div class="stat-icon-mini">
                        <i class="fas fa-euro-sign"></i>
                    </div>
                    <div class="stat-info-mini">
                        <div class="stat-value-mini">€{{ number_format($valorTotal ?? 0, 2, ',', '.') }}</div>
                        <div class="stat-label-mini">Valor Total</div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-6 mb-3">
                <div class="stat-card-mini stat-orange">
                    <div class="stat-icon-mini">
                        <i class="fas fa-trophy"></i>
                    </div>
                    <div class="stat-info-mini">
                        <div class="stat-value-mini">{{ $disciplinaTop ?? 'N/A' }}</div>
                        <div class="stat-label-mini">Disciplina Top</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs de Visualização -->
    <ul class="nav nav-tabs nav-tabs-modern mb-4" id="visualizacaoTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link active" data-toggle="tab" href="#tabTemporais">
                <i class="fas fa-calendar-week mr-2"></i>Análise Temporal
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabDisciplinas">
                <i class="fas fa-book mr-2"></i>Por Disciplina
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabDiasSemana">
                <i class="fas fa-calendar-day mr-2"></i>Dias da Semana
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link" data-toggle="tab" href="#tabComparativo">
                <i class="fas fa-chart-bar mr-2"></i>Comparativo
            </a>
        </li>
    </ul>

    <div class="tab-content">
        <!-- Tab 1: Análise Temporal -->
        <div class="tab-pane fade show active" id="tabTemporais">
            <div class="charts-grid">
                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-chart-line text-primary"></i> Evolução Temporal</h3>
                            <p class="chart-subtitle">Explicações ao longo do tempo</p>
                        </div>
                        <div class="chart-actions">
                            <button class="btn-chart-action" onclick="exportarGrafico('graficoTemporal')" title="Exportar">
                                <i class="fas fa-download"></i>
                            </button>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoTemporal"></canvas>
                    </div>
                </div>

                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-euro-sign text-success"></i> Valores por Período</h3>
                            <p class="chart-subtitle">Rendimento ao longo do tempo</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoValoresTemporal"></canvas>
                    </div>
                </div>

                <div class="chart-card-new chart-card-wide">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-layer-group text-info"></i> Evolução por Disciplina</h3>
                            <p class="chart-subtitle">Comparação entre disciplinas ao longo do tempo</p>
                        </div>
                    </div>
                    <div class="chart-container-new chart-container-tall">
                        <canvas id="graficoEvolucaoDisciplinas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 2: Por Disciplina -->
        <div class="tab-pane fade" id="tabDisciplinas">
            <div class="charts-grid">
                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-chart-pie text-primary"></i> Distribuição por Disciplina</h3>
                            <p class="chart-subtitle">Percentagem de cada disciplina</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoPizzaDisciplinas"></canvas>
                    </div>
                </div>

                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-chart-bar text-success"></i> Ranking de Disciplinas</h3>
                            <p class="chart-subtitle">Disciplinas mais procuradas</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoBarrasDisciplinas"></canvas>
                    </div>
                </div>

                <div class="chart-card-new chart-card-wide">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-coins text-warning"></i> Valores por Disciplina</h3>
                            <p class="chart-subtitle">Rendimento gerado por cada disciplina</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoValoresDisciplinas"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 3: Dias da Semana -->
        <div class="tab-pane fade" id="tabDiasSemana">
            <div class="charts-grid">
                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-calendar-week text-primary"></i> Explicações por Dia da Semana</h3>
                            <p class="chart-subtitle">Distribuição semanal</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoDiasSemana"></canvas>
                    </div>
                </div>

                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-clock text-info"></i> Horários Mais Populares</h3>
                            <p class="chart-subtitle">Períodos do dia com mais explicações</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoHorarios"></canvas>
                    </div>
                </div>

                <div class="chart-card-new chart-card-wide">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-th text-danger"></i> Mapa de Calor: Disciplina × Dia</h3>
                            <p class="chart-subtitle">Intensidade por disciplina em cada dia da semana</p>
                        </div>
                    </div>
                    <div class="chart-container-new chart-container-tall">
                        <canvas id="graficoHeatmapDias"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tab 4: Comparativo -->
        <div class="tab-pane fade" id="tabComparativo">
            <div class="charts-grid">
                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-chart-area text-purple"></i> Evolução Acumulativa</h3>
                            <p class="chart-subtitle">Total acumulado de explicações</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoAcumulativo"></canvas>
                    </div>
                </div>

                <div class="chart-card-new">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-balance-scale text-info"></i> Média Móvel (7 dias)</h3>
                            <p class="chart-subtitle">Tendência suavizada</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoMediaMovel"></canvas>
                    </div>
                </div>

                <div class="chart-card-new chart-card-wide">
                    <div class="chart-header-new">
                        <div>
                            <h3><i class="fas fa-chart-line text-success"></i> Crescimento Mês a Mês</h3>
                            <p class="chart-subtitle">Variação percentual entre períodos</p>
                        </div>
                    </div>
                    <div class="chart-container-new">
                        <canvas id="graficoCrescimento"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela de Dados Detalhados -->
    <div class="data-table-section mt-4">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="fas fa-table mr-2"></i>Dados Detalhados por Disciplina</h5>
                <div>
                    <a href="{{ route('estatisticas.pdf') }}" class="btn btn-sm btn-danger" target="_blank">
                        <i class="fas fa-file-pdf mr-1"></i>Exportar PDF
                    </a>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover table-striped" id="tabelaDisciplinasDetalhada">
                        <thead>
                            <tr>
                                <th>Disciplina</th>
                                <th class="text-center">Total</th>
                                <th class="text-center">Este Mês</th>
                                <th class="text-center">Média/Dia</th>
                                <th class="text-right">Valor Total</th>
                                <th class="text-right">Valor Médio</th>
                                <th class="text-center">% Total</th>
                                <th class="text-center">Tendência</th>
                            </tr>
                        </thead>
                        <tbody id="tabelaDisciplinas">
                            @if(isset($estatisticasPorDisciplina))
                                @foreach($estatisticasPorDisciplina as $stat)
                                <tr>
                                    <td>
                                        <span class="badge badge-disciplina" style="background-color: {{ $stat['cor'] }}15; color: {{ $stat['cor'] }}; border: 1px solid {{ $stat['cor'] }}40;">
                                            <i class="fas fa-book mr-1"></i>{{ $stat['disciplina'] }}
                                        </span>
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $stat['total'] }}</td>
                                    <td class="text-center">{{ $stat['este_mes'] }}</td>
                                    <td class="text-center">{{ number_format($stat['media_dia'], 1) }}</td>
                                    <td class="text-right text-success font-weight-bold">€{{ number_format($stat['valor_total'], 2, ',', '.') }}</td>
                                    <td class="text-right">€{{ number_format($stat['valor_medio'], 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 24px;">
                                            <div class="progress-bar" role="progressbar" 
                                                 style="width: {{ $stat['percentagem'] }}%; background-color: {{ $stat['cor'] }};"
                                                 aria-valuenow="{{ $stat['percentagem'] }}" aria-valuemin="0" aria-valuemax="100">
                                                {{ number_format($stat['percentagem'], 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($stat['tendencia'] > 0)
                                            <span class="badge badge-success">
                                                <i class="fas fa-arrow-up"></i> +{{ $stat['tendencia'] }}%
                                            </span>
                                        @elseif($stat['tendencia'] < 0)
                                            <span class="badge badge-danger">
                                                <i class="fas fa-arrow-down"></i> {{ $stat['tendencia'] }}%
                                            </span>
                                        @else
                                            <span class="badge badge-secondary">
                                                <i class="fas fa-minus"></i> 0%
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8" class="text-center text-muted">Nenhum dado disponível</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabela por Dia da Semana -->
    <div class="data-table-section mt-4">
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0"><i class="fas fa-calendar-week mr-2"></i>Estatísticas por Dia da Semana</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Dia da Semana</th>
                                <th class="text-center">Total Explicações</th>
                                <th class="text-center">Média por Semana</th>
                                <th class="text-right">Valor Total</th>
                                <th class="text-center">% do Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if(isset($estatisticasPorDia))
                                @foreach($estatisticasPorDia as $dia)
                                <tr>
                                    <td>
                                        <span class="badge badge-light px-3 py-2">
                                            <i class="fas fa-calendar-day mr-2"></i>{{ $dia['nome'] }}
                                        </span>
                                    </td>
                                    <td class="text-center font-weight-bold">{{ $dia['total'] }}</td>
                                    <td class="text-center">{{ number_format($dia['media_semana'], 1) }}</td>
                                    <td class="text-right text-success font-weight-bold">€{{ number_format($dia['valor_total'], 2, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-info" role="progressbar" 
                                                 style="width: {{ $dia['percentagem'] }}%;">
                                                {{ number_format($dia['percentagem'], 1) }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Nenhum dado disponível</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<style>
/* Header */
.content-header-modern {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    padding: 2rem 1.5rem;
    margin: -1rem -1rem 2rem -1rem;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 20px rgba(102, 126, 234, 0.3);
    position: relative;
    overflow: hidden;
}

.content-header-modern::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: radial-gradient(circle at top right, rgba(255,255,255,0.1) 0%, transparent 60%);
    pointer-events: none;
}

.header-content {
    max-width: 1400px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.page-title {
    font-size: clamp(1.5rem, 5vw, 2.5rem);
    font-weight: 700;
    margin-bottom: 0.5rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.page-subtitle {
    font-size: clamp(0.9rem, 2.5vw, 1.1rem);
    opacity: 0.95;
    margin: 0;
    font-weight: 300;
}

/* Period Selector */
.period-selector-advanced {
    background: white;
    padding: 1.5rem;
    border-radius: 16px;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
}

.form-label {
    font-size: 0.875rem;
    font-weight: 600;
    color: #374151;
    margin-bottom: 0.5rem;
}

.form-control {
    border-radius: 8px;
    border: 2px solid #e5e7eb;
    padding: 0.625rem 0.875rem;
    font-size: 0.9rem;
    transition: all 0.2s;
}

.form-control:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
}

/* Quick Stats */
.stat-card-mini {
    background: white;
    border-radius: 12px;
    padding: 1.25rem;
    display: flex;
    align-items: center;
    gap: 1rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border-left: 4px solid;
}

.stat-card-mini:hover {
    transform: translateY(-4px);
    box-shadow: 0 12px 28px rgba(0,0,0,0.15);
}

.stat-blue { border-left-color: #3b82f6; }
.stat-green { border-left-color: #10b981; }
.stat-purple { border-left-color: #8b5cf6; }
.stat-orange { border-left-color: #f59e0b; }

.stat-icon-mini {
    width: 56px;
    height: 56px;
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    flex-shrink: 0;
    color: white;
}

.stat-blue .stat-icon-mini { background: linear-gradient(135deg, #3b82f6, #1d4ed8); }
.stat-green .stat-icon-mini { background: linear-gradient(135deg, #10b981, #059669); }
.stat-purple .stat-icon-mini { background: linear-gradient(135deg, #8b5cf6, #7c3aed); }
.stat-orange .stat-icon-mini { background: linear-gradient(135deg, #f59e0b, #d97706); }

.stat-value-mini {
    font-size: 1.75rem;
    font-weight: 700;
    color: #1f2937;
    line-height: 1;
}

.stat-label-mini {
    font-size: 0.85rem;
    color: #6b7280;
    font-weight: 500;
    margin-top: 0.25rem;
}

/* Tabs */
.nav-tabs-modern {
    border-bottom: 2px solid #e5e7eb;
    margin-bottom: 2rem;
}

.nav-tabs-modern .nav-link {
    border: none;
    color: #6b7280;
    font-weight: 500;
    padding: 1rem 1.5rem;
    transition: all 0.2s;
    border-bottom: 3px solid transparent;
}

.nav-tabs-modern .nav-link:hover {
    color: #667eea;
    border-bottom-color: #667eea50;
}

.nav-tabs-modern .nav-link.active {
    color: #667eea;
    background: none;
    border-bottom-color: #667eea;
    font-weight: 600;
}

/* Charts Grid */
.charts-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 1.5rem;
    margin-bottom: 2rem;
}

.chart-card-wide {
    grid-column: 1 / -1;
}

.chart-card-new {
    background: white;
    border-radius: 16px;
    padding: 1.5rem;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
}

.chart-card-new:hover {
    box-shadow: 0 8px 24px rgba(0,0,0,0.12);
    transform: translateY(-2px);
}

.chart-header-new {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid #f3f4f6;
}

.chart-header-new h3 {
    font-size: 1.1rem;
    font-weight: 600;
    color: #1f2937;
    margin: 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.chart-subtitle {
    font-size: 0.85rem;
    color: #6b7280;
    margin: 0.25rem 0 0 0;
}

.chart-actions {
    display: flex;
    gap: 0.5rem;
}

.btn-chart-action {
    width: 32px;
    height: 32px;
    border: none;
    background: #f3f4f6;
    border-radius: 8px;
    color: #6b7280;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-chart-action:hover {
    background: #e5e7eb;
    color: #1f2937;
}

.chart-container-new {
    height: 350px;
    position: relative;
}

.chart-container-tall {
    height: 450px;
}

/* Tables */
.data-table-section .card {
    border: none;
    box-shadow: 0 4px 16px rgba(0,0,0,0.08);
    border-radius: 16px;
}

.data-table-section .card-header {
    border-bottom: 2px solid #f3f4f6;
    padding: 1.25rem 1.5rem;
    border-radius: 16px 16px 0 0;
}

.table {
    margin-bottom: 0;
}

.table thead th {
    background: #f8f9fa;
    color: #6b7280;
    font-weight: 600;
    font-size: 0.85rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    border-bottom: 2px solid #dee2e6;
    padding: 1rem;
}

.table tbody tr {
    transition: background-color 0.2s;
}

.table tbody tr:hover {
    background-color: #f8f9fa;
}

.table tbody td {
    padding: 1rem;
    vertical-align: middle;
}

.badge-disciplina {
    font-size: 0.9rem;
    padding: 0.5rem 0.75rem;
    font-weight: 500;
    border-radius: 8px;
}

/* Progress bars */
.progress {
    background-color: #e5e7eb;
    border-radius: 10px;
    overflow: hidden;
}

.progress-bar {
    transition: width 0.6s ease;
    font-size: 0.75rem;
    font-weight: 600;
    display: flex;
    align-items: center;
    justify-content: center;
}

/* Badges */
.badge {
    font-weight: 500;
    padding: 0.4rem 0.7rem;
    font-size: 0.85rem;
    border-radius: 6px;
}

/* Responsive */
@media (max-width: 1200px) {
    .charts-grid {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 768px) {
    .content-header-modern {
        padding: 1.5rem 1rem;
        margin: -1rem -0.5rem 2rem -0.5rem;
    }

    .page-title {
        flex-direction: column;
        text-align: center;
    }

    .stat-card-mini {
        flex-direction: column;
        text-align: center;
    }

    .chart-container-new {
        height: 280px;
    }

    .chart-container-tall {
        height: 350px;
    }

    .nav-tabs-modern .nav-link {
        font-size: 0.85rem;
        padding: 0.75rem 1rem;
    }
}

/* Animations */
@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.chart-card-new {
    animation: fadeIn 0.5s ease-out;
}
</style>

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Configuração global
Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
Chart.defaults.color = '#6b7280';

// Dados do backend
const dadosBackend = {
    labels: @json($periodos ?? []),
    disciplinas: @json($disciplinasDisponiveis ?? []),
    cores: @json($coresDisciplinas ?? []),
    explicacoesPorPeriodo: @json($explicacoesPorPeriodo ?? []),
    valoresPorPeriodo: @json($valoresPorPeriodo ?? []),
    explicacoesPorDisciplina: @json($explicacoesPorDisciplina ?? []),
    valoresPorDisciplina: @json($valoresPorDisciplina ?? []),
    explicacoesPorDia: @json($explicacoesPorDiaSemana ?? []),
    explicacoesPorHorario: @json($explicacoesPorHorario ?? []),
    dadosHeatmap: {!! json_encode($dadosHeatmap ?? []) !!}
};

// Gráfico 1: Evolução Temporal
const graficoTemporal = new Chart(document.getElementById('graficoTemporal'), {
    type: 'line',
    data: {
        labels: dadosBackend.labels,
        datasets: [{
            label: 'Explicações',
            data: dadosBackend.explicacoesPorPeriodo,
            borderColor: '#3b82f6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#3b82f6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2,
            pointHoverRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12,
                titleFont: { size: 13, weight: 'bold' },
                bodyFont: { size: 12 },
                callbacks: {
                    label: (context) => `Explicações: ${context.parsed.y}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                ticks: { stepSize: 1 }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Gráfico 2: Valores Temporal
new Chart(document.getElementById('graficoValoresTemporal'), {
    type: 'bar',
    data: {
        labels: dadosBackend.labels,
        datasets: [{
            label: 'Valor (€)',
            data: dadosBackend.valoresPorPeriodo,
            backgroundColor: 'rgba(16, 185, 129, 0.8)',
            borderColor: 'rgba(16, 185, 129, 1)',
            borderWidth: 2,
            borderRadius: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12,
                callbacks: {
                    label: (context) => `Valor: €${context.parsed.y.toFixed(2)}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                ticks: {
                    callback: (value) => '€' + value
                }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Gráfico 3: Evolução por Disciplina
const datasetsEvolucao = dadosBackend.disciplinas.map((disc, idx) => ({
    label: disc,
    data: dadosBackend.dadosHeatmap[disc] || [],
    borderColor: dadosBackend.cores[idx],
    backgroundColor: dadosBackend.cores[idx] + '20',
    fill: true,
    tension: 0.4,
    borderWidth: 2,
    pointRadius: 4
}));

new Chart(document.getElementById('graficoEvolucaoDisciplinas'), {
    type: 'line',
    data: {
        labels: dadosBackend.labels,
        datasets: datasetsEvolucao
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: {
            mode: 'index',
            intersect: false
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: { size: 11 }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                stacked: false,
                grid: { color: 'rgba(156, 163, 175, 0.1)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Gráfico 4: Pizza Disciplinas
new Chart(document.getElementById('graficoPizzaDisciplinas'), {
    type: 'doughnut',
    data: {
        labels: dadosBackend.disciplinas,
        datasets: [{
            data: dadosBackend.explicacoesPorDisciplina,
            backgroundColor: dadosBackend.cores,
            borderWidth: 3,
            borderColor: '#fff',
            hoverOffset: 15
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: {
                    padding: 15,
                    usePointStyle: true,
                    font: { size: 12 }
                }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12,
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return `${context.label}: ${context.parsed} (${percentage}%)`;
                    }
                }
            }
        }
    }
});

// Gráfico 5: Barras Disciplinas
new Chart(document.getElementById('graficoBarrasDisciplinas'), {
    type: 'bar',
    data: {
        labels: dadosBackend.disciplinas,
        datasets: [{
            data: dadosBackend.explicacoesPorDisciplina,
            backgroundColor: dadosBackend.cores.map(c => c + 'CC'),
            borderColor: dadosBackend.cores,
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        indexAxis: 'y',
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12
            }
        },
        scales: {
            x: {
                beginAtZero: true,
                grid: { color: 'rgba(156, 163, 175, 0.1)' }
            },
            y: {
                grid: { display: false }
            }
        }
    }
});

// Gráfico 6: Valores por Disciplina
new Chart(document.getElementById('graficoValoresDisciplinas'), {
    type: 'bar',
    data: {
        labels: dadosBackend.disciplinas,
        datasets: [{
            label: 'Valor Total (€)',
            data: dadosBackend.valoresPorDisciplina,
            backgroundColor: 'rgba(245, 158, 11, 0.8)',
            borderColor: 'rgba(245, 158, 11, 1)',
            borderWidth: 2,
            borderRadius: 10
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12,
                callbacks: {
                    label: (context) => `Valor: €${context.parsed.y.toFixed(2)}`
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                ticks: {
                    callback: (value) => '€' + value
                }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Gráfico 7: Dias da Semana
const diasSemana = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
new Chart(document.getElementById('graficoDiasSemana'), {
    type: 'bar',
    data: {
        labels: diasSemana,
        datasets: [{
            label: 'Explicações',
            data: dadosBackend.explicacoesPorDia,
            backgroundColor: [
                'rgba(59, 130, 246, 0.8)',
                'rgba(16, 185, 129, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(239, 68, 68, 0.8)',
                'rgba(139, 92, 246, 0.8)',
                'rgba(236, 72, 153, 0.8)',
                'rgba(14, 165, 233, 0.8)'
            ],
            borderWidth: 2,
            borderRadius: 10,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                grid: { color: 'rgba(156, 163, 175, 0.1)' }
            },
            x: {
                grid: { display: false }
            }
        }
    }
});

// Gráfico 8: Horários
const horariosLabels = ['06-09h', '09-12h', '12-15h', '15-18h', '18-21h', '21-00h'];
new Chart(document.getElementById('graficoHorarios'), {
    type: 'polarArea',
    data: {
        labels: horariosLabels,
        datasets: [{
            data: dadosBackend.explicacoesPorHorario,
            backgroundColor: [
                'rgba(59, 130, 246, 0.7)',
                'rgba(16, 185, 129, 0.7)',
                'rgba(245, 158, 11, 0.7)',
                'rgba(239, 68, 68, 0.7)',
                'rgba(139, 92, 246, 0.7)',
                'rgba(236, 72, 153, 0.7)'
            ],
            borderWidth: 2,
            borderColor: '#fff'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: { padding: 15, font: { size: 11 } }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12
            }
        }
    }
});

// Gráfico 9: Heatmap Dias
new Chart(document.getElementById('graficoHeatmapDias'), {
    type: 'bar',
    data: {
        labels: diasSemana,
        datasets: dadosBackend.disciplinas.map((disc, idx) => ({
            label: disc,
            data: dadosBackend.dadosHeatmap[disc] ? dadosBackend.dadosHeatmap[disc].slice(0, 7) : [],
            backgroundColor: dadosBackend.cores[idx] + 'BB',
            borderColor: dadosBackend.cores[idx],
            borderWidth: 1
        }))
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: { usePointStyle: true, padding: 15 }
            },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12
            }
        },
        scales: {
            x: { stacked: true, grid: { display: false } },
            y: { stacked: true, beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.1)' } }
        }
    }
});

// Gráfico 10: Acumulativo
const acumulativo = dadosBackend.explicacoesPorPeriodo.reduce((acc, val, idx) => {
    acc.push((acc[idx - 1] || 0) + val);
    return acc;
}, []);

new Chart(document.getElementById('graficoAcumulativo'), {
    type: 'line',
    data: {
        labels: dadosBackend.labels,
        datasets: [{
            label: 'Total Acumulado',
            data: acumulativo,
            borderColor: '#8b5cf6',
            backgroundColor: 'rgba(139, 92, 246, 0.1)',
            fill: true,
            tension: 0.4,
            borderWidth: 3,
            pointRadius: 5,
            pointBackgroundColor: '#8b5cf6',
            pointBorderColor: '#fff',
            pointBorderWidth: 2
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12,
                callbacks: {
                    label: (context) => `Total: ${context.parsed.y} explicações`
                }
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.1)' } },
            x: { grid: { display: false } }
        }
    }
});

// Gráfico 11: Média Móvel
function calcularMediaMovel(dados, janela = 7) {
    const resultado = [];
    for (let i = 0; i < dados.length; i++) {
        const inicio = Math.max(0, i - janela + 1);
        const slice = dados.slice(inicio, i + 1);
        const media = slice.reduce((a, b) => a + b, 0) / slice.length;
        resultado.push(media);
    }
    return resultado;
}

const mediaMovel = calcularMediaMovel(dadosBackend.explicacoesPorPeriodo);

new Chart(document.getElementById('graficoMediaMovel'), {
    type: 'line',
    data: {
        labels: dadosBackend.labels,
        datasets: [
            {
                label: 'Dados Reais',
                data: dadosBackend.explicacoesPorPeriodo,
                borderColor: 'rgba(156, 163, 175, 0.5)',
                backgroundColor: 'transparent',
                borderWidth: 2,
                pointRadius: 3,
                borderDash: [5, 5]
            },
            {
                label: 'Média Móvel (7 dias)',
                data: mediaMovel,
                borderColor: '#06b6d4',
                backgroundColor: 'rgba(6, 182, 212, 0.1)',
                fill: true,
                tension: 0.4,
                borderWidth: 3,
                pointRadius: 5,
                pointBackgroundColor: '#06b6d4'
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        interaction: { mode: 'index', intersect: false },
        plugins: {
            legend: { position: 'top', labels: { padding: 15 } },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12
            }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: 'rgba(156, 163, 175, 0.1)' } },
            x: { grid: { display: false } }
        }
    }
});

// Gráfico 12: Crescimento
const crescimento = dadosBackend.explicacoesPorPeriodo.map((val, idx) => {
    if (idx === 0) return 0;
    const anterior = dadosBackend.explicacoesPorPeriodo[idx - 1];
    return anterior === 0 ? 0 : ((val - anterior) / anterior * 100);
});

new Chart(document.getElementById('graficoCrescimento'), {
    type: 'bar',
    data: {
        labels: dadosBackend.labels,
        datasets: [{
            label: 'Crescimento (%)',
            data: crescimento,
            backgroundColor: crescimento.map(v => v >= 0 ? 'rgba(16, 185, 129, 0.8)' : 'rgba(239, 68, 68, 0.8)'),
            borderColor: crescimento.map(v => v >= 0 ? 'rgba(16, 185, 129, 1)' : 'rgba(239, 68, 68, 1)'),
            borderWidth: 2,
            borderRadius: 8
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: 'rgba(17, 24, 39, 0.95)',
                padding: 12,
                callbacks: {
                    label: (context) => `Crescimento: ${context.parsed.y.toFixed(1)}%`
                }
            }
        },
        scales: {
            y: {
                grid: { color: 'rgba(156, 163, 175, 0.1)' },
                ticks: {
                    callback: (value) => value + '%'
                }
            },
            x: { grid: { display: false } }
        }
    }
});

// Funções auxiliares
function atualizarGraficos() {
    const tipo = document.getElementById('tipoPeriodo').value;
    const alcance = document.getElementById('alcancePeriodo').value;
    const disciplina = document.getElementById('filtroDisciplina').value;
    const dataInicio = document.getElementById('dataInicio').value;
    const dataFim = document.getElementById('dataFim').value;
    
    // Construir URL com parâmetros
    let url = '{{ route("estatisticas") }}?';
    const params = new URLSearchParams();
    
    if (tipo) params.append('tipo', tipo);
    if (tipo === 'custom') {
        if (dataInicio) params.append('data_inicio', dataInicio);
        if (dataFim) params.append('data_fim', dataFim);
    } else {
        if (alcance) params.append('alcance', alcance);
    }
    if (disciplina) params.append('disciplina', disciplina);
    
    // Redirecionar com parâmetros
    window.location.href = url + params.toString();
}

function exportarGrafico(id) {
    const canvas = document.getElementById(id);
    const url = canvas.toDataURL('image/png');
    const link = document.createElement('a');
    link.download = `grafico_${id}.png`;
    link.href = url;
    link.click();
}

// Listener para mudança de tipo de período
document.getElementById('tipoPeriodo').addEventListener('change', function() {
    const custom = this.value === 'custom';
    document.getElementById('alcanceContainer').style.display = custom ? 'none' : 'block';
    document.getElementById('dataInicioContainer').style.display = custom ? 'block' : 'none';
    document.getElementById('dataFimContainer').style.display = custom ? 'block' : 'none';
});
</script>
@stop