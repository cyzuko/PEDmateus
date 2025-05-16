@extends('layouts.app')

@section('title', 'Estatísticas das Faturas')

@section('content_header')
    <h1>Estatísticas das Faturas</h1>
@stop

@section('content')

    <div class="card mb-4" style="max-width: 700px;">
        <div class="card-body">
            <h3>Resumo Geral</h3>
            <p><strong>Total de Faturas:</strong> {{ $totalFaturas }}</p>
            <p><strong>Valor Total das Faturas:</strong> €{{ number_format($valorTotal, 2, ',', '.') }}</p>
            <p><strong>Valor Médio por Fatura:</strong> €{{ number_format($mediaValor, 2, ',', '.') }}</p>
        </div>
    </div>

    <style>.graficos-container {
    display: flex;
    flex-wrap: wrap;
    gap: 1.5rem;
    justify-content: space-between;
}

.grafico-box {
    flex: 1 1 48%;
    background: white;
    border-radius: 8px;
    padding: 1rem;
    box-shadow: 0 2px 6px rgb(0 0 0 / 0.1);
    min-width: 300px;
    height: 320px; /* altura fixa para todos os gráficos */
    display: flex;
    flex-direction: column;
}

.grafico-box h3 {
    margin-bottom: 0.5rem;
    font-weight: 600;
    font-size: 1.1rem;
}

.grafico-box canvas {
    flex-grow: 1; /* canvas preenche o espaço restante */
    max-height: 100%;
    max-width: 100%;
}

/* Responsivo */
@media (max-width: 768px) {
    .grafico-box {
        flex: 1 1 100%;
        height: 320px;
    }
}
.content-wrapper {
    margin-bottom: 2rem; /* ou o quanto quiser de espaço */
}

    </style>

    <div class="graficos-container">

        <div class="grafico-box">
            <h3>Total de Faturas por Mês</h3>
            <canvas id="graficoTotalFaturasMensal" width="400" height="250"></canvas>
        </div>

        <div class="grafico-box">
            <h3>Valor Total das Faturas por Mês (€)</h3>
            <canvas id="graficoValorTotalMensal" width="400" height="250"></canvas>
        </div>

        <div class="grafico-box">
            <h3>Total de Faturas por Fornecedor</h3>
            <canvas id="graficoTotalFaturasFornecedor" width="400" height="250"></canvas>
        </div>

        <div class="grafico-box">
            <h3>Valor Total das Faturas por Fornecedor (€)</h3>
            <canvas id="graficoValorTotalFornecedor" width="400" height="250"></canvas>
        </div>

    </div>

@stop

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Dados
    const meses = @json($estatisticasMensais->pluck('mes'));
    const totalFaturasMes = @json($estatisticasMensais->pluck('total'));
    const valorTotalMes = @json($estatisticasMensais->pluck('total_valor'));

    const fornecedores = @json($estatisticasFornecedor->pluck('fornecedor'));
    const totalFaturasFornecedor = @json($estatisticasFornecedor->pluck('total'));
    const valorTotalFornecedor = @json($estatisticasFornecedor->pluck('total_valor'));

    // Gráfico barras - Total de Faturas Mensais
    new Chart(document.getElementById('graficoTotalFaturasMensal'), {
        type: 'bar',
        data: {
            labels: meses,
            datasets: [{
                label: 'Total de Faturas',
                data: totalFaturasMes,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
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
                fill: false,
                borderColor: 'rgba(255, 99, 132, 0.8)',
                backgroundColor: 'rgba(255, 99, 132, 0.4)',
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Gráfico barras horizontal - Total de Faturas por Fornecedor
    new Chart(document.getElementById('graficoTotalFaturasFornecedor'), {
        type: 'bar',
        data: {
            labels: fornecedores,
            datasets: [{
                label: 'Total de Faturas',
                data: totalFaturasFornecedor,
                backgroundColor: 'rgba(75, 192, 192, 0.7)',
                borderRadius: 5,
                borderSkipped: false
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: { stepSize: 1 }
                }
            }
        }
    });

    // Gráfico linha - Valor Total por Fornecedor
    new Chart(document.getElementById('graficoValorTotalFornecedor'), {
        type: 'line',
        data: {
            labels: fornecedores,
            datasets: [{
                label: 'Valor Total (€)',
                data: valorTotalFornecedor,
                fill: false,
                borderColor: 'rgba(153, 102, 255, 0.8)',
                backgroundColor: 'rgba(153, 102, 255, 0.4)',
                tension: 0.3,
                pointRadius: 5,
                pointHoverRadius: 7,
                borderWidth: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false },
                title: {
                    display: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@stop
