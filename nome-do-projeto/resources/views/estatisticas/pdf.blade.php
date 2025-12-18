<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Estatísticas - Relatório</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            text-align: center;
            margin-bottom: 25px;
        }

        .header h1 {
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 12px;
            opacity: 0.9;
        }

        .info-section {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #667eea;
        }

        .info-section strong {
            color: #667eea;
        }

        .stats-grid {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-spacing: 10px;
        }

        .stat-card {
            display: table-cell;
            width: 25%;
            background: white;
            border: 2px solid #e5e7eb;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
        }

        .stat-card.blue { border-left: 4px solid #3b82f6; }
        .stat-card.green { border-left: 4px solid #10b981; }
        .stat-card.purple { border-left: 4px solid #8b5cf6; }
        .stat-card.orange { border-left: 4px solid #f59e0b; }

        .stat-value {
            font-size: 22px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 5px;
        }

        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin: 25px 0 15px 0;
            padding-bottom: 8px;
            border-bottom: 2px solid #667eea;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
            background: white;
        }

        table thead {
            background: #f3f4f6;
        }

        table th {
            padding: 10px;
            text-align: left;
            font-size: 10px;
            font-weight: bold;
            color: #6b7280;
            text-transform: uppercase;
            border-bottom: 2px solid #e5e7eb;
        }

        table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
            font-size: 11px;
        }

        table tbody tr:hover {
            background: #f9fafb;
        }

        .badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 500;
        }

        .badge-disciplina {
            background: #e0e7ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-success {
            color: #10b981;
            font-weight: bold;
        }

        .progress-bar {
            height: 20px;
            background: #e5e7eb;
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .progress-fill {
            height: 100%;
            background: #3b82f6;
            color: white;
            font-size: 9px;
            font-weight: bold;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .footer {
            margin-top: 40px;
            padding-top: 15px;
            border-top: 2px solid #e5e7eb;
            text-align: center;
            font-size: 9px;
            color: #6b7280;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <!-- Cabeçalho -->
    <div class="header">
        <h1>📊 Relatório de Estatísticas</h1>
        <p>Sistema de Gestão de Explicações</p>
    </div>

    <!-- Informações do Relatório -->
    <div class="info-section">
        <p><strong>Período:</strong> {{ $dataInicio }} a {{ $dataFim }}</p>
        <p><strong>Gerado em:</strong> {{ $dataGeracao }}</p>
        <p><strong>Gerado por:</strong> {{ $usuario }}</p>
    </div>

    <!-- Cards de Resumo -->
    <div class="stats-grid">
        <div class="stat-card blue">
            <div class="stat-value">{{ $totalExplicacoes }}</div>
            <div class="stat-label">Total Explicações</div>
        </div>
        <div class="stat-card green">
            <div class="stat-value">{{ count($estatisticasPorDisciplina) }}</div>
            <div class="stat-label">Disciplinas</div>
        </div>
        <div class="stat-card purple">
            <div class="stat-value">€{{ number_format($valorTotal, 2, ',', '.') }}</div>
            <div class="stat-label">Valor Total</div>
        </div>
        <div class="stat-card orange">
            <div class="stat-value">{{ $disciplinaTop }}</div>
            <div class="stat-label">Disciplina Top</div>
        </div>
    </div>

    <!-- Tabela: Estatísticas por Disciplina -->
    <h2 class="section-title">Estatísticas por Disciplina</h2>
    <table>
        <thead>
            <tr>
                <th>Disciplina</th>
                <th class="text-center">Total</th>
                <th class="text-center">Média/Dia</th>
                <th class="text-right">Valor Total</th>
                <th class="text-right">Valor Médio</th>
                <th class="text-center">% Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estatisticasPorDisciplina as $stat)
            <tr>
                <td>
                    <span class="badge badge-disciplina">{{ $stat['disciplina'] }}</span>
                </td>
                <td class="text-center" style="font-weight: bold;">{{ $stat['total'] }}</td>
                <td class="text-center">{{ number_format($stat['media_dia'], 1) }}</td>
                <td class="text-right text-success">€{{ number_format($stat['valor_total'], 2, ',', '.') }}</td>
                <td class="text-right">€{{ number_format($stat['valor_medio'], 2, ',', '.') }}</td>
                <td class="text-center">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $stat['percentagem'] }}%; background-color: {{ $stat['cor'] }};">
                            {{ number_format($stat['percentagem'], 1) }}%
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center">Nenhum dado disponível</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Nova Página -->
    <div class="page-break"></div>

    <!-- Tabela: Estatísticas por Dia da Semana -->
    <h2 class="section-title">Estatísticas por Dia da Semana</h2>
    <table>
        <thead>
            <tr>
                <th>Dia da Semana</th>
                <th class="text-center">Total Explicações</th>
                <th class="text-center">Média/Semana</th>
                <th class="text-right">Valor Total</th>
                <th class="text-center">% do Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse($estatisticasPorDia as $dia)
            <tr>
                <td>
                    <span class="badge" style="background: #f3f4f6; color: #374151;">
                        {{ $dia['nome'] }}
                    </span>
                </td>
                <td class="text-center" style="font-weight: bold;">{{ $dia['total'] }}</td>
                <td class="text-center">{{ number_format($dia['media_semana'], 1) }}</td>
                <td class="text-right text-success">€{{ number_format($dia['valor_total'], 2, ',', '.') }}</td>
                <td class="text-center">
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: {{ $dia['percentagem'] }}%; background-color: #06b6d4;">
                            {{ number_format($dia['percentagem'], 1) }}%
                        </div>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">Nenhum dado disponível</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Resumo Final -->
    <div class="info-section" style="margin-top: 30px;">
        <h3 style="margin-bottom: 10px; color: #667eea;">📈 Resumo do Período</h3>
        <p><strong>Total de Explicações:</strong> {{ $totalExplicacoes }}</p>
        <p><strong>Receita Total:</strong> €{{ number_format($valorTotal, 2, ',', '.') }}</p>
        <p><strong>Valor Médio por Explicação:</strong> €{{ number_format($mediaValor, 2, ',', '.') }}</p>
        <p><strong>Disciplina Mais Procurada:</strong> {{ $disciplinaTop }}</p>
    </div>

    <!-- Rodapé -->
    <div class="footer">
        <p>Este relatório foi gerado automaticamente pelo Sistema de Gestão de Explicações</p>
        <p>© {{ date('Y') }} - Todos os direitos reservados</p>
    </div>
</body>
</html>