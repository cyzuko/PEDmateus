@extends('layouts.app')

@section('title', 'Estatísticas das Explicações')

@section('content_header')
    <div class="content-header-modern">
        <div class="header-content">
            <h1 class="page-title">
                <i class="fas fa-chart-bar"></i>
                Estatísticas das Explicações
            </h1>
            <p class="page-subtitle">Análise detalhada do desempenho</p>
        </div>
    </div>
@stop

@section('content')

    <!-- Cards de Resum -->
    <div class="stats-summary">
        <div class="stats-card primary">
            <div class="stats-icon">
                <i class="fas fa-graduation-cap"></i>
            </div>
            <div class="stats-content">
                <h3>{{ $totalExplicacoes }}</h3>
                <p>Total de Explicações</p>
                <span class="stats-trend">
                    <i class="fas fa-arrow-up"></i>
                    Registadas
                </span>
            </div>
        </div>

        <div class="stats-card success">
            <div class="stats-icon">
                <i class="fas fa-euro-sign"></i>
            </div>
            <div class="stats-content">
                <h3>€{{ number_format($valorTotal, 2, ',', '.') }}</h3>
                <p>Valor Total</p>
                <span class="stats-trend">
                    <i class="fas fa-chart-line"></i>
                    Faturação
                </span>
            </div>
        </div>

        <div class="stats-card info">
            <div class="stats-icon">
                <i class="fas fa-calculator"></i>
            </div>
            <div class="stats-content">
                <h3>€{{ number_format($mediaValor, 2, ',', '.') }}</h3>
                <p>Valor Médio</p>
                <span class="stats-trend">
                    <i class="fas fa-balance-scale"></i>
                    Por Explicação
                </span>
            </div>
        </div>
    </div>

    <!-- Gráficos -->
    <div class="charts-section">
        <div class="section-header">
            <h2><i class="fas fa-analytics"></i> Análises Gráficas</h2>
            <p>Visualização detalhada dos dados</p>
        </div>

        <!-- Linha superior - Gráficos Mensais -->
        <div class="charts-row">
            <div class="chart-card large">
                <div class="chart-header">
                    <h3><i class="fas fa-calendar-alt"></i> Explicações por Mês</h3>
                    <div class="chart-legend">
                        <span class="legend-item blue">Quantidade mensal</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="graficoTotalExplicacoesMensal"></canvas>
                </div>
            </div>

            <div class="chart-card large">
                <div class="chart-header">
                    <h3><i class="fas fa-money-bill-wave"></i> Valor Mensal (€)</h3>
                    <div class="chart-legend">
                        <span class="legend-item red">Evolução de valores</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="graficoValorTotalMensal"></canvas>
                </div>
            </div>
        </div>

        <!-- Linha inferior - Gráficos de Disciplinas -->
        <div class="charts-row">
            <div class="chart-card large">
                <div class="chart-header">
                    <h3><i class="fas fa-book"></i> Explicações por Disciplina</h3>
                    <div class="chart-legend">
                        <span class="legend-item teal">Distribuição por disciplina</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="graficoTotalExplicacoesDisciplina"></canvas>
                </div>
            </div>

            <div class="chart-card large">
                <div class="chart-header">
                    <h3><i class="fas fa-chart-line"></i> Valores por Disciplina (€)</h3>
                    <div class="chart-legend">
                        <span class="legend-item purple">Análise de valores</span>
                    </div>
                </div>
                <div class="chart-container">
                    <canvas id="graficoValorTotalDisciplina"></canvas>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Reset e base */
        * {
            box-sizing: border-box;
        }

        /* Garantir que o conteúdo não ultrapasse a largura */
        .content-wrapper {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Mobile first - base styles */
        body {
            font-size: 14px;
            line-height: 1.5;
        }

        /* Header moderno */
        .content-header-modern {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem 1.5rem;
            margin: -1rem -1rem 2rem -1rem;
            border-radius: 0 0 20px 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .page-title i {
            font-size: 2rem;
        }

        .page-subtitle {
            font-size: 1.1rem;
            opacity: 0.9;
            margin: 0;
            font-weight: 300;
        }

        /* Cards de estatísticas */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-bottom: 3rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.3s ease;
            border-left: 4px solid;
        }

        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .stats-card.primary {
            border-left-color: #3b82f6;
        }

        .stats-card.success {
            border-left-color: #10b981;
        }

        .stats-card.info {
            border-left-color: #8b5cf6;
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            flex-shrink: 0;
        }

        .stats-card.primary .stats-icon {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
        }

        .stats-card.success .stats-icon {
            background: linear-gradient(135deg, #10b981, #059669);
        }

        .stats-card.info .stats-icon {
            background: linear-gradient(135deg, #8b5cf6, #7c3aed);
        }

        .stats-content h3 {
            font-size: 2.2rem;
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
        }

        .stats-content p {
            font-size: 1rem;
            color: #6b7280;
            margin: 0 0 0.5rem 0;
            font-weight: 500;
        }

        .stats-trend {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
            color: #059669;
            font-weight: 500;
        }

        /* Seção de gráficos */
        .charts-section {
            margin-top: 3rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .section-header h2 {
            font-size: 2rem;
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
        }

        .section-header p {
            color: #6b7280;
            font-size: 1.1rem;
            margin: 0;
        }

        /* Layout em linhas para desktop */
        .charts-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 10px 40px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            border: 1px solid rgba(0,0,0,0.05);
        }

        .chart-card.large {
            min-height: 500px;
        }

        .chart-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 15px 50px rgba(0,0,0,0.15);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
        }

        .chart-header h3 {
            font-size: 1.3rem;
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chart-header i {
            color: #6b7280;
        }

        .chart-legend {
            display: flex;
            gap: 1rem;
        }

        .legend-item {
            font-size: 0.85rem;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-weight: 500;
            position: relative;
        }

        .legend-item.blue {
            background: rgba(59, 130, 246, 0.1);
            color: #1d4ed8;
        }

        .legend-item.red {
            background: rgba(239, 68, 68, 0.1);
            color: #dc2626;
        }

        .legend-item.teal {
            background: rgba(20, 184, 166, 0.1);
            color: #0d9488;
        }

        .legend-item.purple {
            background: rgba(139, 92, 246, 0.1);
            color: #7c3aed;
        }

        .chart-container {
            height: 400px;
            position: relative;
        }

        .chart-container canvas {
            max-height: 100% !important;
            border-radius: 8px;
        }

        /* Responsivo - Tablet */
        @media (max-width: 1200px) {
            .stats-summary {
                grid-template-columns: repeat(3, 1fr);
                gap: 1.5rem;
            }

            .charts-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .chart-card.large {
                min-height: 450px;
            }

            .chart-container {
                height: 350px;
            }
        }

        /* Mobile */
        @media (max-width: 768px) {
            .content-header-modern {
                margin: -1rem -0.5rem 2rem -0.5rem;
                padding: 1.5rem 1rem;
            }

            .page-title {
                font-size: 1.8rem;
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .page-title i {
                font-size: 1.5rem;
            }

            .page-subtitle {
                text-align: center;
                font-size: 1rem;
            }

            .stats-summary {
                grid-template-columns: 1fr;
                gap: 1rem;
            }

            .stats-card {
                padding: 1.5rem;
                flex-direction: column;
                text-align: center;
                gap: 1rem;
            }

            .stats-content h3 {
                font-size: 1.8rem;
            }

            .charts-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .chart-card.large {
                min-height: auto;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .chart-header h3 {
                font-size: 1.1rem;
            }

            .chart-container {
                height: 280px;
            }

            .section-header h2 {
                font-size: 1.5rem;
                flex-direction: column;
                gap: 0.5rem;
            }

            .section-header p {
                font-size: 1rem;
            }
        }

        /* Mobile pequeno */
        @media (max-width: 480px) {
            .content-header-modern {
                padding: 1rem 0.75rem;
                margin: -1rem -0.25rem 1.5rem -0.25rem;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .page-title i {
                font-size: 1.3rem;
            }

            .page-subtitle {
                font-size: 0.9rem;
            }

            .stats-card {
                padding: 1.25rem;
            }

            .stats-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .stats-content h3 {
                font-size: 1.6rem;
            }

            .stats-content p {
                font-size: 0.9rem;
            }

            .chart-card {
                padding: 1.25rem;
            }

            .chart-header h3 {
                font-size: 1rem;
            }

            .chart-container {
                height: 250px;
            }

            .legend-item {
                font-size: 0.75rem;
                padding: 0.25rem 0.6rem;
            }

            .section-header h2 {
                font-size: 1.3rem;
            }

            .section-header p {
                font-size: 0.9rem;
            }
        }

        /* Animações suaves */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .stats-card,
        .chart-card {
            animation: fadeInUp 0.6s ease-out;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.2s;
        }

        .chart-card:nth-child(1) {
            animation-delay: 0.3s;
        }

        .chart-card:nth-child(2) {
            animation-delay: 0.4s;
        }

        .charts-row:nth-child(3) .chart-card:nth-child(1) {
            animation-delay: 0.5s;
        }

        .charts-row:nth-child(3) .chart-card:nth-child(2) {
            animation-delay: 0.6s;
        }
    </style>

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configurações globais do Chart.js
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif";
    Chart.defaults.color = '#6b7280';

    // Dados
    const meses = @json($estatisticasMensais->pluck('mes'));
    const totalExplicacoesMes = @json($estatisticasMensais->pluck('total'));
    const valorTotalMes = @json($estatisticasMensais->pluck('total_valor'));

    const disciplinas = @json($estatisticasDisciplina->pluck('disciplina'));
    const totalExplicacoesDisciplina = @json($estatisticasDisciplina->pluck('total'));
    const valorTotalDisciplina = @json($estatisticasDisciplina->pluck('total_valor'));

    // Gráfico barras - Total de Explicações Mensais
    new Chart(document.getElementById('graficoTotalExplicacoesMensal'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Total de Explicações',
                data: totalExplicacoesMes,
                backgroundColor: 'rgba(59, 130, 246, 0.8)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(29, 78, 216, 0.9)',
                hoverBorderColor: 'rgba(29, 78, 216, 1)'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(59, 130, 246, 1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.2)'
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Gráfico linha - Valor Total Mensal
    new Chart(document.getElementById('graficoValorTotalMensal'), {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: 'Valor Total (€)',
                data: valorTotalMes,
                fill: true,
                borderColor: 'rgba(239, 68, 68, 1)',
                backgroundColor: 'rgba(239, 68, 68, 0.1)',
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 8,
                borderWidth: 3,
                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgba(220, 38, 38, 1)',
                pointHoverBorderColor: 'white'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(239, 68, 68, 1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Valor: €' + context.parsed.y.toLocaleString('pt-PT', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return '€' + value.toLocaleString('pt-PT');
                        }
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.2)'
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Gráfico barras horizontal - Total de Explicações por Disciplina
    new Chart(document.getElementById('graficoTotalExplicacoesDisciplina'), {
        type: 'bar',
        data: {
            labels: disciplinas,
            datasets: [{
                label: 'Total de Explicações',
                data: totalExplicacoesDisciplina,
                backgroundColor: 'rgba(20, 184, 166, 0.8)',
                borderColor: 'rgba(20, 184, 166, 1)',
                borderWidth: 2,
                borderRadius: 8,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(13, 148, 136, 0.9)',
                hoverBorderColor: 'rgba(13, 148, 136, 1)'
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(20, 184, 166, 1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.2)'
                    }
                },
                y: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });

    // Gráfico linha - Valor Total por Disciplina
    new Chart(document.getElementById('graficoValorTotalDisciplina'), {
        type: 'line',
        data: {
            labels: disciplinas,
            datasets: [{
                label: 'Valor Total (€)',
                data: valorTotalDisciplina,
                fill: true,
                borderColor: 'rgba(139, 92, 246, 1)',
                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 8,
                borderWidth: 3,
                pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                pointBorderColor: 'white',
                pointBorderWidth: 2,
                pointHoverBackgroundColor: 'rgba(124, 58, 237, 1)',
                pointHoverBorderColor: 'white'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    borderColor: 'rgba(139, 92, 246, 1)',
                    borderWidth: 1,
                    cornerRadius: 8,
                    displayColors: false,
                    callbacks: {
                        label: function(context) {
                            return 'Valor: €' + context.parsed.y.toLocaleString('pt-PT', {minimumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12
                        },
                        callback: function(value) {
                            return '€' + value.toLocaleString('pt-PT');
                        }
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.2)'
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12
                        }
                    },
                    grid: {
                        display: false
                    }
                }
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    });
</script>
@stop