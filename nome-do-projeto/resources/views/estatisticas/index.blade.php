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

    <!-- Cards de Resumo -->
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
            <h2><i class="fas fa-chart-pie"></i> Análises Gráficas</h2>
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
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        /* Garantir que o conteúdo não ultrapasse a largura */
        .content-wrapper {
            max-width: 100%;
            overflow-x: hidden;
        }

        /* Mobile first - base styles */
        body {
            font-size: 14px;
            line-height: 1.6;
        }

        /* Header moderno */
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

        .page-title i {
            font-size: clamp(1.3rem, 4vw, 2rem);
        }

        .page-subtitle {
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            opacity: 0.95;
            margin: 0;
            font-weight: 300;
            letter-spacing: 0.3px;
        }

        /* Cards de estatísticas */
        .stats-summary {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
            max-width: 1400px;
            margin-left: auto;
            margin-right: auto;
        }

        .stats-card {
            background: white;
            border-radius: 16px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            display: flex;
            align-items: center;
            gap: 1.5rem;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border-left: 4px solid;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            opacity: 0.05;
            transition: all 0.4s ease;
        }

        .stats-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.15);
        }

        .stats-card:hover::before {
            transform: scale(1.5);
            opacity: 0.08;
        }

        .stats-card.primary {
            border-left-color: #3b82f6;
        }

        .stats-card.primary::before {
            background: #3b82f6;
        }

        .stats-card.success {
            border-left-color: #10b981;
        }

        .stats-card.success::before {
            background: #10b981;
        }

        .stats-card.info {
            border-left-color: #8b5cf6;
        }

        .stats-card.info::before {
            background: #8b5cf6;
        }

        .stats-icon {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            color: white;
            flex-shrink: 0;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            transition: transform 0.3s ease;
        }

        .stats-card:hover .stats-icon {
            transform: scale(1.1) rotate(5deg);
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

        .stats-content {
            flex: 1;
            min-width: 0;
        }

        .stats-content h3 {
            font-size: clamp(1.6rem, 4vw, 2.2rem);
            font-weight: 700;
            color: #1f2937;
            margin: 0 0 0.5rem 0;
            line-height: 1;
        }

        .stats-content p {
            font-size: clamp(0.9rem, 2vw, 1rem);
            color: #6b7280;
            margin: 0 0 0.5rem 0;
            font-weight: 500;
        }

        .stats-trend {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.85rem;
            color: #059669;
            font-weight: 500;
            background: rgba(16, 185, 129, 0.1);
            padding: 0.25rem 0.75rem;
            border-radius: 20px;
        }

        .stats-trend i {
            font-size: 0.75rem;
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
            font-size: clamp(1.3rem, 4vw, 2rem);
            color: #1f2937;
            margin-bottom: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.75rem;
            font-weight: 700;
        }

        .section-header p {
            color: #6b7280;
            font-size: clamp(0.9rem, 2.5vw, 1.1rem);
            margin: 0;
            font-weight: 400;
        }

        /* Layout em linhas para desktop */
        .charts-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
            gap: 2rem;
            margin-bottom: 2rem;
        }

        .chart-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
            border: 1px solid rgba(0,0,0,0.04);
        }

        .chart-card.large {
            min-height: 500px;
        }

        .chart-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 40px rgba(0,0,0,0.12);
            border-color: rgba(0,0,0,0.08);
        }

        .chart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f3f4f6;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .chart-header h3 {
            font-size: clamp(1rem, 2.5vw, 1.3rem);
            font-weight: 600;
            color: #1f2937;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .chart-header i {
            color: #6b7280;
            font-size: 1.1rem;
        }

        .chart-legend {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .legend-item {
            font-size: 0.85rem;
            padding: 0.35rem 0.9rem;
            border-radius: 20px;
            font-weight: 500;
            white-space: nowrap;
            transition: all 0.3s ease;
        }

        .legend-item:hover {
            transform: translateY(-2px);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
                border-radius: 0 0 16px 16px;
            }

            .page-title {
                flex-direction: column;
                gap: 0.5rem;
                text-align: center;
            }

            .page-subtitle {
                text-align: center;
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

            .charts-row {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .chart-card {
                padding: 1.5rem;
            }

            .chart-card.large {
                min-height: auto;
            }

            .chart-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 1rem;
            }

            .chart-container {
                height: 280px;
            }
        }

        /* Mobile pequeno */
        @media (max-width: 480px) {
            .content-header-modern {
                padding: 1rem 0.75rem;
                margin: -1rem -0.25rem 1.5rem -0.25rem;
                border-radius: 0 0 12px 12px;
            }

            .stats-card {
                padding: 1.25rem;
            }

            .stats-icon {
                width: 60px;
                height: 60px;
                font-size: 1.5rem;
            }

            .chart-card {
                padding: 1.25rem;
            }

            .chart-container {
                height: 250px;
            }

            .legend-item {
                font-size: 0.75rem;
                padding: 0.3rem 0.7rem;
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

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .stats-card {
            animation: fadeInUp 0.6s ease-out backwards;
        }

        .stats-card:nth-child(1) {
            animation-delay: 0.1s;
        }

        .stats-card:nth-child(2) {
            animation-delay: 0.2s;
        }

        .stats-card:nth-child(3) {
            animation-delay: 0.3s;
        }

        .chart-card {
            animation: slideInLeft 0.6s ease-out backwards;
        }

        .charts-row:nth-child(2) .chart-card:nth-child(1) {
            animation-delay: 0.4s;
        }

        .charts-row:nth-child(2) .chart-card:nth-child(2) {
            animation-delay: 0.5s;
        }

        .charts-row:nth-child(3) .chart-card:nth-child(1) {
            animation-delay: 0.6s;
        }

        .charts-row:nth-child(3) .chart-card:nth-child(2) {
            animation-delay: 0.7s;
        }

        /* Scroll suave */
        html {
            scroll-behavior: smooth;
        }

        /* Loading skeleton opcional */
        @keyframes shimmer {
            0% {
                background-position: -1000px 0;
            }
            100% {
                background-position: 1000px 0;
            }
        }
    </style>

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Configurações globais do Chart.js
    Chart.defaults.font.family = "'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', sans-serif";
    Chart.defaults.color = '#6b7280';
    Chart.defaults.plugins.tooltip.padding = 12;
    Chart.defaults.plugins.tooltip.cornerRadius = 8;
    Chart.defaults.plugins.tooltip.caretSize = 6;

    // Dados do servidor
    const meses = @json($estatisticasMensais->pluck('mes'));
    const totalExplicacoesMes = @json($estatisticasMensais->pluck('total'));
    const valorTotalMes = @json($estatisticasMensais->pluck('total_valor'));
    const disciplinas = @json($estatisticasDisciplina->pluck('disciplina'));
    const totalExplicacoesDisciplina = @json($estatisticasDisciplina->pluck('total'));
    const valorTotalDisciplina = @json($estatisticasDisciplina->pluck('total_valor'));

    // Configuração comum de animação
    const animationConfig = {
        duration: 2000,
        easing: 'easeOutQuart',
        onComplete: function() {
            this.options.animation.duration = 750;
        }
    };

    // Gráfico barras - Total de Explicações Mensais
    new Chart(document.getElementById('graficoTotalExplicacoesMensal'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Total de Explicações',
                data: totalExplicacoesMes,
                backgroundColor: 'rgba(59, 130, 246, 0.85)',
                borderColor: 'rgba(59, 130, 246, 1)',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(29, 78, 216, 0.95)',
                hoverBorderColor: 'rgba(29, 78, 216, 1)',
                hoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(59, 130, 246, 0.5)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return 'Explicações: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 8
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.15)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 8
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: animationConfig
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
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(239, 68, 68, 0.2)');
                    gradient.addColorStop(1, 'rgba(239, 68, 68, 0.01)');
                    return gradient;
                },
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 9,
                borderWidth: 3,
                pointBackgroundColor: 'rgba(239, 68, 68, 1)',
                pointBorderColor: 'white',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: 'rgba(220, 38, 38, 1)',
                pointHoverBorderColor: 'white',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(239, 68, 68, 0.5)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return 'Valor: €' + context.parsed.y.toLocaleString('pt-PT', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
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
                            size: 12,
                            weight: '500'
                        },
                        padding: 8,
                        callback: function(value) {
                            return '€' + value.toLocaleString('pt-PT');
                        }
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.15)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 8
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: animationConfig
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
                backgroundColor: 'rgba(20, 184, 166, 0.85)',
                borderColor: 'rgba(20, 184, 166, 1)',
                borderWidth: 2,
                borderRadius: 10,
                borderSkipped: false,
                hoverBackgroundColor: 'rgba(13, 148, 136, 0.95)',
                hoverBorderColor: 'rgba(13, 148, 136, 1)',
                hoverBorderWidth: 3
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(20, 184, 166, 0.5)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return 'Explicações: ' + context.parsed.x;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { 
                        stepSize: 1,
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 8
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.15)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                y: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 8
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: animationConfig
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
                backgroundColor: (context) => {
                    const ctx = context.chart.ctx;
                    const gradient = ctx.createLinearGradient(0, 0, 0, 400);
                    gradient.addColorStop(0, 'rgba(139, 92, 246, 0.2)');
                    gradient.addColorStop(1, 'rgba(139, 92, 246, 0.01)');
                    return gradient;
                },
                tension: 0.4,
                pointRadius: 6,
                pointHoverRadius: 9,
                borderWidth: 3,
                pointBackgroundColor: 'rgba(139, 92, 246, 1)',
                pointBorderColor: 'white',
                pointBorderWidth: 3,
                pointHoverBackgroundColor: 'rgba(124, 58, 237, 1)',
                pointHoverBorderColor: 'white',
                pointHoverBorderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                intersect: false,
                mode: 'index'
            },
            plugins: {
                legend: { 
                    display: false 
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: 'white',
                    bodyColor: 'white',
                    titleFont: {
                        size: 14,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 13
                    },
                    borderColor: 'rgba(139, 92, 246, 0.5)',
                    borderWidth: 1,
                    displayColors: false,
                    callbacks: {
                        title: function(context) {
                            return context[0].label;
                        },
                        label: function(context) {
                            return 'Valor: €' + context.parsed.y.toLocaleString('pt-PT', {
                                minimumFractionDigits: 2,
                                maximumFractionDigits: 2
                            });
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
                            size: 12,
                            weight: '500'
                        },
                        padding: 8,
                        callback: function(value) {
                            return '€' + value.toLocaleString('pt-PT');
                        }
                    },
                    grid: {
                        color: 'rgba(156, 163, 175, 0.15)',
                        drawBorder: false
                    },
                    border: {
                        display: false
                    }
                },
                x: {
                    ticks: { 
                        color: '#9ca3af',
                        font: {
                            size: 12,
                            weight: '500'
                        },
                        padding: 8
                    },
                    grid: {
                        display: false
                    },
                    border: {
                        display: false
                    }
                }
            },
            animation: animationConfig
        }
    });
</script>
@stop