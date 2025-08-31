<?php

namespace App\Http\Controllers;

use App\Models\Explicacao;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    /**
     * Dashboard do administrador
     */
    public function index()
    {
        // Verificação manual de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        // Estatísticas gerais
        $stats = [
            'total_explicacoes' => Explicacao::count(),
            'pendentes_aprovacao' => Explicacao::where('aprovacao_admin', 'pendente')->count(),
            'aprovadas_hoje' => Explicacao::where('aprovacao_admin', 'aprovada')
                ->whereDate('data_aprovacao', Carbon::today())->count(),
            'total_professores' => User::where('role', 'professor')->count(),
        ];

        // Explicações pendentes (últimas 10)
        $explicacoesPendentes = Explicacao::with(['user'])
            ->where('aprovacao_admin', 'pendente')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        // Explicações recentemente aprovadas/rejeitadas
        $explicacoesRecentes = Explicacao::with(['user', 'aprovadoPor'])
            ->whereIn('aprovacao_admin', ['aprovada', 'rejeitada'])
            ->orderBy('data_aprovacao', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'explicacoesPendentes', 'explicacoesRecentes'));
    }

    /**
     * Listar todas as explicações para aprovação
     */
    public function explicacoes(Request $request)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $query = Explicacao::with(['user']);

        // Filtros
        if ($request->filled('status_aprovacao')) {
            $query->where('aprovacao_admin', $request->status_aprovacao);
        }

        if ($request->filled('disciplina')) {
            $query->where('disciplina', 'LIKE', '%' . $request->disciplina . '%');
        }

        if ($request->filled('professor')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('name', 'LIKE', '%' . $request->professor . '%');
            });
        }

        if ($request->filled('data_inicio')) {
            $query->where('data_explicacao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->where('data_explicacao', '<=', $request->data_fim);
        }

        $explicacoes = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.explicacoes.index', compact('explicacoes'));
    }

    /**
     * Ver detalhes de uma explicação para aprovação
     */
    public function explicacaoShow($id)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $explicacao = Explicacao::with(['user', 'aprovadoPor'])->findOrFail($id);
        
        return view('admin.explicacoes.show', compact('explicacao'));
    }

    /**
     * Aprovar explicação
     */
    public function aprovarExplicacao(Request $request, $id)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $explicacao = Explicacao::findOrFail($id);

        if ($explicacao->aprovacao_admin !== 'pendente') {
            return redirect()->back()->with('error', 'Esta explicação já foi processada.');
        }

        DB::table('explicacoes')
            ->where('id', $id)
            ->update([
                'aprovacao_admin' => 'aprovada',
                'aprovada_por' => Auth::id(),
                'data_aprovacao' => now(),
                'motivo_rejeicao' => null,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Explicação aprovada com sucesso!');
    }

    /**
     * Rejeitar explicação
     */
    public function rejeitarExplicacao(Request $request, $id)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'motivo_rejeicao' => 'required|string|max:500'
        ]);

        $explicacao = Explicacao::findOrFail($id);

        if ($explicacao->aprovacao_admin !== 'pendente') {
            return redirect()->back()->with('error', 'Esta explicação já foi processada.');
        }

        DB::table('explicacoes')
            ->where('id', $id)
            ->update([
                'aprovacao_admin' => 'rejeitada',
                'aprovada_por' => Auth::id(),
                'data_aprovacao' => now(),
                'motivo_rejeicao' => $request->motivo_rejeicao,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Explicação rejeitada com sucesso!');
    }

    /**
     * Aprovar múltiplas explicações
     */
    public function aprovarMultiplas(Request $request)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $request->validate([
            'explicacoes' => 'required|array',
            'explicacoes.*' => 'exists:explicacoes,id'
        ]);

        $aprovadas = 0;
        foreach ($request->explicacoes as $explicacaoId) {
            $explicacao = Explicacao::find($explicacaoId);
            
            if ($explicacao && $explicacao->aprovacao_admin === 'pendente') {
                DB::table('explicacoes')
                    ->where('id', $explicacaoId)
                    ->update([
                        'aprovacao_admin' => 'aprovada',
                        'aprovada_por' => Auth::id(),
                        'data_aprovacao' => now(),
                        'motivo_rejeicao' => null,
                        'updated_at' => now()
                    ]);
                $aprovadas++;
            }
        }

        return redirect()->back()->with('success', "$aprovadas explicações aprovadas com sucesso!");
    }

    /**
     * Reverter aprovação (voltar para pendente)
     */
    public function reverterAprovacao($id)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $explicacao = Explicacao::findOrFail($id);

        if (!in_array($explicacao->aprovacao_admin, ['aprovada', 'rejeitada'])) {
            return redirect()->back()->with('error', 'Esta explicação não pode ser revertida.');
        }

        DB::table('explicacoes')
            ->where('id', $id)
            ->update([
                'aprovacao_admin' => 'pendente',
                'aprovada_por' => null,
                'data_aprovacao' => null,
                'motivo_rejeicao' => null,
                'updated_at' => now()
            ]);

        return redirect()->back()->with('success', 'Aprovação revertida com sucesso!');
    }

    /**
     * Relatório de aprovações
     */
    public function relatorioAprovacoes(Request $request)
    {
        // Verificação de admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Acesso negado.');
        }

        $dataInicio = $request->get('data_inicio', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $dataFim = $request->get('data_fim', Carbon::now()->endOfMonth()->format('Y-m-d'));

        // Estatísticas do período
        $stats = [
            'total_aprovadas' => Explicacao::where('aprovacao_admin', 'aprovada')
                ->whereBetween('data_aprovacao', [$dataInicio, $dataFim])
                ->count(),
            
            'total_rejeitadas' => Explicacao::where('aprovacao_admin', 'rejeitada')
                ->whereBetween('data_aprovacao', [$dataInicio, $dataFim])
                ->count(),
            
            'pendentes_atuais' => Explicacao::where('aprovacao_admin', 'pendente')->count(),
            
            'tempo_medio_aprovacao' => $this->calcularTempoMedioAprovacao($dataInicio, $dataFim)
        ];

        // Aprovações por dia
        $aprovacoesPorDia = Explicacao::selectRaw('DATE(data_aprovacao) as data, 
                                                  COUNT(*) as total,
                                                  SUM(CASE WHEN aprovacao_admin = "aprovada" THEN 1 ELSE 0 END) as aprovadas,
                                                  SUM(CASE WHEN aprovacao_admin = "rejeitada" THEN 1 ELSE 0 END) as rejeitadas')
            ->whereNotNull('data_aprovacao')
            ->whereBetween('data_aprovacao', [$dataInicio, $dataFim])
            ->groupBy('data')
            ->orderBy('data', 'desc')
            ->get();

        // Top professores com mais aprovações
        $topProfessores = User::select('users.*')
            ->selectRaw('COUNT(explicacoes.id) as total_aprovadas')
            ->join('explicacoes', 'users.id', '=', 'explicacoes.user_id')
            ->where('explicacoes.aprovacao_admin', 'aprovada')
            ->whereBetween('explicacoes.data_aprovacao', [$dataInicio, $dataFim])
            ->groupBy('users.id')
            ->orderBy('total_aprovadas', 'desc')
            ->limit(10)
            ->get();

        return view('admin.relatorio-aprovacoes', compact(
            'stats', 
            'aprovacoesPorDia', 
            'topProfessores',
            'dataInicio',
            'dataFim'
        ));
    }

    /**
     * Calcular tempo médio de aprovação
     */
    private function calcularTempoMedioAprovacao($dataInicio, $dataFim)
    {
        $explicacoes = Explicacao::selectRaw('
                TIMESTAMPDIFF(HOUR, created_at, data_aprovacao) as horas_para_aprovacao
            ')
            ->whereNotNull('data_aprovacao')
            ->whereBetween('data_aprovacao', [$dataInicio, $dataFim])
            ->get();

        if ($explicacoes->isEmpty()) {
            return 0;
        }

        $tempoMedio = $explicacoes->avg('horas_para_aprovacao');
        return round($tempoMedio, 1);
    }

    /**
     * API endpoint para buscar explicações pendentes (para notificações)
     */
    public function explicacoesPendentesCount()
    {
        $count = Explicacao::where('aprovacao_admin', 'pendente')->count();
        return response()->json(['count' => $count]);
    }

    /**
     * Exportar relatório de explicações
     */
    public function exportarRelatorio(Request $request)
    {
        // Esta funcionalidade pode ser implementada depois para exportar PDF/Excel
        // Por agora, retorna erro informativo
        return redirect()->back()->with('info', 'Funcionalidade de exportação será implementada em breve.');
    }
}