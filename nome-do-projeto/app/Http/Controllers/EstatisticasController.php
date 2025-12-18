<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Explicacao;
use App\Models\Disciplina;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class EstatisticasController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Query base
        $query = Explicacao::query();

        // Se não for admin, filtra pelas explicações do usuário
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Processar filtros de período
        $tipo = $request->get('tipo', 'meses');
        $alcance = $request->get('alcance', 90);
        $dataInicio = $request->get('data_inicio');
        $dataFim = $request->get('data_fim');
        $disciplinaFiltro = $request->get('disciplina');

        // Definir período
        if ($tipo === 'custom' && $dataInicio && $dataFim) {
            $dataInicio = Carbon::parse($dataInicio);
            $dataFim = Carbon::parse($dataFim);
        } else {
            $dataInicio = Carbon::now()->subDays($alcance);
            $dataFim = Carbon::now();
        }

        // Filtrar por período
        $query->whereBetween('data_explicacao', [$dataInicio, $dataFim]);

        // Filtrar por disciplina se selecionada
        if ($disciplinaFiltro) {
            $query->where('disciplina', $disciplinaFiltro);
        }

        // Filtrar por período
        $query->whereBetween('data_explicacao', [$dataInicio, $dataFim]);

        // =============================================
        // ESTATÍSTICAS GERAIS
        // =============================================
        $totalExplicacoes = (clone $query)->count();
        $valorTotal = (clone $query)->sum('preco') ?? 0;
        $mediaValor = (clone $query)->avg('preco') ?? 0;

        // Disciplina com mais explicações
        $disciplinaTopData = (clone $query)
            ->select('disciplina', DB::raw('COUNT(*) as total'))
            ->groupBy('disciplina')
            ->orderBy('total', 'desc')
            ->first();
        
        $disciplinaTop = $disciplinaTopData->disciplina ?? 'N/A';

        // =============================================
        // DISCIPLINAS DISPONÍVEIS
        // =============================================
        $disciplinasDisponiveis = Disciplina::where('ativa', true)
            ->pluck('nome');

        // =============================================
        // CORES PARA GRÁFICOS
        // =============================================
        $coresDisciplinas = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
            '#8b5cf6', '#ec4899', '#06b6d4', '#14b8a6',
            '#f97316', '#84cc16', '#a855f7', '#f43f5e'
        ];

        // =============================================
        // PERÍODOS (LABELS DOS GRÁFICOS)
        // =============================================
        $periodos = [];
        $explicacoesPorPeriodo = [];
        $valoresPorPeriodo = [];

        // Gerar períodos mensais
        $mesAtual = Carbon::parse($dataInicio);
        while ($mesAtual->lte($dataFim)) {
            $mesFormatado = $mesAtual->format('M/Y');
            $periodos[] = $mesFormatado;

            // Contar explicações do mês
            $totalMes = (clone $query)
                ->whereYear('data_explicacao', $mesAtual->year)
                ->whereMonth('data_explicacao', $mesAtual->month)
                ->count();
            
            $valorMes = (clone $query)
                ->whereYear('data_explicacao', $mesAtual->year)
                ->whereMonth('data_explicacao', $mesAtual->month)
                ->sum('preco') ?? 0;

            $explicacoesPorPeriodo[] = $totalMes;
            $valoresPorPeriodo[] = round($valorMes, 2);

            $mesAtual->addMonth();
        }

        // =============================================
        // ESTATÍSTICAS POR DISCIPLINA
        // =============================================
        $estatisticasPorDisciplinaRaw = (clone $query)
            ->select(
                'disciplina',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(preco) as total_valor')
            )
            ->groupBy('disciplina')
            ->orderBy('total_valor', 'desc')
            ->get();

        $explicacoesPorDisciplina = [];
        $valoresPorDisciplina = [];
        $estatisticasPorDisciplina = [];

        $mesAnterior = Carbon::now()->subMonth();

        foreach ($estatisticasPorDisciplinaRaw as $index => $stat) {
            $explicacoesPorDisciplina[] = $stat->total;
            $valoresPorDisciplina[] = round($stat->total_valor, 2);

            // Total do mês atual
            $totalMesAtual = (clone $query)
                ->where('disciplina', $stat->disciplina)
                ->whereYear('data_explicacao', Carbon::now()->year)
                ->whereMonth('data_explicacao', Carbon::now()->month)
                ->count();

            // Total do mês anterior
            $totalMesAnterior = (clone $query)
                ->where('disciplina', $stat->disciplina)
                ->whereYear('data_explicacao', $mesAnterior->year)
                ->whereMonth('data_explicacao', $mesAnterior->month)
                ->count();

            // Calcular tendência
            $tendencia = 0;
            if ($totalMesAnterior > 0) {
                $tendencia = round((($totalMesAtual - $totalMesAnterior) / $totalMesAnterior) * 100, 1);
            }

            // Média por dia
            $diasPeriodo = $dataInicio->diffInDays($dataFim) ?: 1;
            $mediaDia = round($stat->total / $diasPeriodo, 2);

            // Percentagem do total
            $percentagem = $totalExplicacoes > 0 ? round(($stat->total / $totalExplicacoes) * 100, 2) : 0;

            $estatisticasPorDisciplina[] = [
                'disciplina' => $stat->disciplina,
                'total' => $stat->total,
                'este_mes' => $totalMesAtual,
                'media_dia' => $mediaDia,
                'valor_total' => $stat->total_valor,
                'valor_medio' => $stat->total > 0 ? round($stat->total_valor / $stat->total, 2) : 0,
                'percentagem' => $percentagem,
                'tendencia' => $tendencia,
                'cor' => $coresDisciplinas[$index % count($coresDisciplinas)]
            ];
        }

        // =============================================
        // ESTATÍSTICAS POR DIA DA SEMANA
        // =============================================
        $explicacoesPorDiaSemana = [0, 0, 0, 0, 0, 0, 0]; // Seg a Dom
        $valoresPorDiaSemana = [0, 0, 0, 0, 0, 0, 0];

        $dadosDias = (clone $query)
            ->select(
                DB::raw('DAYOFWEEK(data_explicacao) as dia_semana'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(preco) as total_valor')
            )
            ->groupBy('dia_semana')
            ->get();

        foreach ($dadosDias as $dia) {
            // MySQL DAYOFWEEK: 1=Domingo, 2=Segunda, ..., 7=Sábado
            // Converter para: 0=Segunda, 1=Terça, ..., 6=Domingo
            $indice = ($dia->dia_semana == 1) ? 6 : $dia->dia_semana - 2;
            $explicacoesPorDiaSemana[$indice] = $dia->total;
            $valoresPorDiaSemana[$indice] = $dia->total_valor ?? 0;
        }

        // Preparar dados da tabela de dias da semana
        $diasNomes = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
        $estatisticasPorDia = [];
        $numeroSemanas = ceil($dataInicio->diffInDays($dataFim) / 7) ?: 1;

        foreach ($diasNomes as $index => $nome) {
            $total = $explicacoesPorDiaSemana[$index];
            $valor = $valoresPorDiaSemana[$index];
            $percentagem = $totalExplicacoes > 0 ? round(($total / $totalExplicacoes) * 100, 2) : 0;

            $estatisticasPorDia[] = [
                'nome' => $nome,
                'total' => $total,
                'media_semana' => round($total / $numeroSemanas, 2),
                'valor_total' => $valor,
                'percentagem' => $percentagem
            ];
        }

        // =============================================
        // ESTATÍSTICAS POR HORÁRIO
        // =============================================
        $explicacoesPorHorario = [0, 0, 0, 0, 0, 0]; // 6 períodos do dia

        $dadosHorarios = (clone $query)
            ->select(
                DB::raw('HOUR(hora_inicio) as hora'),
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('hora')
            ->get();

        foreach ($dadosHorarios as $horario) {
            $hora = $horario->hora;
            if ($hora >= 6 && $hora < 9) $explicacoesPorHorario[0] += $horario->total;
            elseif ($hora >= 9 && $hora < 12) $explicacoesPorHorario[1] += $horario->total;
            elseif ($hora >= 12 && $hora < 15) $explicacoesPorHorario[2] += $horario->total;
            elseif ($hora >= 15 && $hora < 18) $explicacoesPorHorario[3] += $horario->total;
            elseif ($hora >= 18 && $hora < 21) $explicacoesPorHorario[4] += $horario->total;
            else $explicacoesPorHorario[5] += $horario->total;
        }

        // =============================================
        // DADOS PARA HEATMAP (Disciplina x Período)
        // =============================================
        $dadosHeatmap = [];

        foreach ($disciplinasDisponiveis as $disciplina) {
            $dadosHeatmap[$disciplina] = [];
            
            $mesTemp = Carbon::parse($dataInicio);
            while ($mesTemp->lte($dataFim)) {
                $totalDisciplinaMes = (clone $query)
                    ->where('disciplina', $disciplina)
                    ->whereYear('data_explicacao', $mesTemp->year)
                    ->whereMonth('data_explicacao', $mesTemp->month)
                    ->count();

                $dadosHeatmap[$disciplina][] = $totalDisciplinaMes;
                $mesTemp->addMonth();
            }
        }

        // =============================================
        // RETORNAR PARA A VIEW
        // =============================================
        return view('estatisticas.index', compact(
            'totalExplicacoes',
            'valorTotal',
            'mediaValor',
            'disciplinaTop',
            'disciplinasDisponiveis',
            'coresDisciplinas',
            'periodos',
            'explicacoesPorPeriodo',
            'valoresPorPeriodo',
            'explicacoesPorDisciplina',
            'valoresPorDisciplina',
            'explicacoesPorDiaSemana',
            'explicacoesPorHorario',
            'dadosHeatmap',
            'estatisticasPorDisciplina',
            'estatisticasPorDia'
        ));
    }

    /**
     * Exportar estatísticas para PDF
     */
    public function exportarPDF()
    {
        $user = Auth::user();

        // Query base
        $query = Explicacao::query();

        // Se não for admin, filtra pelas explicações do usuário
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Período padrão: últimos 90 dias
        $dataInicio = Carbon::now()->subDays(90);
        $dataFim = Carbon::now();

        // Filtrar por período
        $query->whereBetween('data_explicacao', [$dataInicio, $dataFim]);

        // Estatísticas gerais
        $totalExplicacoes = (clone $query)->count();
        $valorTotal = (clone $query)->sum('preco') ?? 0;
        $mediaValor = (clone $query)->avg('preco') ?? 0;

        // Disciplina top
        $disciplinaTopData = (clone $query)
            ->select('disciplina', DB::raw('COUNT(*) as total'))
            ->groupBy('disciplina')
            ->orderBy('total', 'desc')
            ->first();
        
        $disciplinaTop = $disciplinaTopData->disciplina ?? 'N/A';

        // Estatísticas por disciplina
        $estatisticasPorDisciplinaRaw = (clone $query)
            ->select(
                'disciplina',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(preco) as total_valor')
            )
            ->groupBy('disciplina')
            ->orderBy('total_valor', 'desc')
            ->get();

        $estatisticasPorDisciplina = [];
        $coresDisciplinas = [
            '#3b82f6', '#10b981', '#f59e0b', '#ef4444', 
            '#8b5cf6', '#ec4899', '#06b6d4', '#14b8a6',
        ];

        foreach ($estatisticasPorDisciplinaRaw as $index => $stat) {
            $diasPeriodo = $dataInicio->diffInDays($dataFim) ?: 1;
            $mediaDia = round($stat->total / $diasPeriodo, 2);
            $percentagem = $totalExplicacoes > 0 ? round(($stat->total / $totalExplicacoes) * 100, 2) : 0;

            $estatisticasPorDisciplina[] = [
                'disciplina' => $stat->disciplina,
                'total' => $stat->total,
                'media_dia' => $mediaDia,
                'valor_total' => $stat->total_valor,
                'valor_medio' => $stat->total > 0 ? round($stat->total_valor / $stat->total, 2) : 0,
                'percentagem' => $percentagem,
                'cor' => $coresDisciplinas[$index % count($coresDisciplinas)]
            ];
        }

        // Estatísticas por dia da semana
        $explicacoesPorDiaSemana = [0, 0, 0, 0, 0, 0, 0];
        $valoresPorDiaSemana = [0, 0, 0, 0, 0, 0, 0];

        $dadosDias = (clone $query)
            ->select(
                DB::raw('DAYOFWEEK(data_explicacao) as dia_semana'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(preco) as total_valor')
            )
            ->groupBy('dia_semana')
            ->get();

        foreach ($dadosDias as $dia) {
            $indice = ($dia->dia_semana == 1) ? 6 : $dia->dia_semana - 2;
            $explicacoesPorDiaSemana[$indice] = $dia->total;
            $valoresPorDiaSemana[$indice] = $dia->total_valor ?? 0;
        }

        $diasNomes = ['Segunda', 'Terça', 'Quarta', 'Quinta', 'Sexta', 'Sábado', 'Domingo'];
        $estatisticasPorDia = [];
        $numeroSemanas = ceil($dataInicio->diffInDays($dataFim) / 7) ?: 1;

        foreach ($diasNomes as $index => $nome) {
            $total = $explicacoesPorDiaSemana[$index];
            $valor = $valoresPorDiaSemana[$index];
            $percentagem = $totalExplicacoes > 0 ? round(($total / $totalExplicacoes) * 100, 2) : 0;

            $estatisticasPorDia[] = [
                'nome' => $nome,
                'total' => $total,
                'media_semana' => round($total / $numeroSemanas, 2),
                'valor_total' => $valor,
                'percentagem' => $percentagem
            ];
        }

        // Dados para o PDF
        $dados = [
            'totalExplicacoes' => $totalExplicacoes,
            'valorTotal' => $valorTotal,
            'mediaValor' => $mediaValor,
            'disciplinaTop' => $disciplinaTop,
            'estatisticasPorDisciplina' => $estatisticasPorDisciplina,
            'estatisticasPorDia' => $estatisticasPorDia,
            'dataInicio' => $dataInicio->format('d/m/Y'),
            'dataFim' => $dataFim->format('d/m/Y'),
            'dataGeracao' => Carbon::now()->format('d/m/Y H:i'),
            'usuario' => $user->name
        ];

        // Gerar PDF
        $pdf = Pdf::loadView('estatisticas.pdf', $dados);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download('estatisticas_' . date('Y-m-d_His') . '.pdf');
    }
}