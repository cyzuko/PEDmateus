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
        abort_unless(auth()->check() && auth()->user()->role === 'admin', 403);

        // Estatísticas gerais
        $stats = [
            'total_explicacoes' => Explicacao::count(),
            'pendentes_aprovacao' => Explicacao::where('aprovacao_admin', 'pendente')->count(),
            'aprovadas_hoje' => Explicacao::where('aprovacao_admin', 'aprovada')
                ->whereDate('data_aprovacao', Carbon::today())->count(),
            'rejeitadas_hoje' => Explicacao::where('aprovacao_admin', 'rejeitada')
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
        $explicacao = Explicacao::with(['user', 'aprovadoPor'])->findOrFail($id);
        
        return view('admin.explicacoes.show', compact('explicacao'));
    }

    /**
     * Aprovar explicação
     */
    public function aprovarExplicacao(Request $request, $id)
    {
        $explicacao = Explicacao::with('user')->findOrFail($id);

        if ($explicacao->aprovacao_admin !== 'pendente') {
            return redirect()->back()->with('error', 'Esta explicação já foi processada.');
        }

        try {
            // ATUALIZAR AMBOS OS CAMPOS
            DB::table('explicacoes')
                ->where('id', $id)
                ->update([
                    'aprovacao_admin' => 'aprovada',
                    'status' => 'confirmada',
                    'aprovada_por' => Auth::id(),
                    'data_aprovacao' => now(),
                    'motivo_rejeicao' => null,
                    'updated_at' => now()
                ]);

            // Recarregar a explicação com os dados atualizados
            $explicacao->refresh();

            // === ENVIAR NOTIFICAÇÃO APENAS PARA O ALUNO (CRIADOR DA EXPLICAÇÃO) ===
            try {
                if ($explicacao->user && $explicacao->user->email) {
                    \Illuminate\Support\Facades\Notification::route('mail', $explicacao->user->email)
                        ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $explicacao->user->email, 'aprovada'));
                    
                    \Log::info('Notificação de aprovação enviada para o aluno', [
                        'aluno' => $explicacao->user->name,
                        'email' => $explicacao->user->email,
                        'explicacao_id' => $id
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar notificação de aprovação para o aluno: ' . $e->getMessage());
            }

            // Log da aprovação
            \Log::info("Explicação aprovada e confirmada automaticamente", [
                'explicacao_id' => $id,
                'aprovada_por' => Auth::user()->name,
                'aluno' => $explicacao->user->name ?? 'N/A',
                'disciplina' => $explicacao->disciplina,
                'data' => $explicacao->data_explicacao
            ]);

            return redirect()->back()->with('success', 'Explicação aprovada e confirmada! Notificação enviada para o aluno.');
        } catch (\Exception $e) {
            \Log::error('Erro ao aprovar explicação: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erro ao aprovar explicação: ' . $e->getMessage());
        }
    }

    /**
     * Rejeitar explicação
     */
    public function rejeitarExplicacao(Request $request, $id)
    {
        $request->validate([
            'motivo_rejeicao' => 'required|string|min:10|max:500'
        ], [
            'motivo_rejeicao.required' => 'O motivo da rejeição é obrigatório.',
            'motivo_rejeicao.min' => 'O motivo deve ter pelo menos 10 caracteres.',
            'motivo_rejeicao.max' => 'O motivo não pode ultrapassar 500 caracteres.'
        ]);

        $explicacao = Explicacao::with('user')->findOrFail($id);

        if ($explicacao->aprovacao_admin !== 'pendente') {
            return redirect()->back()->with('error', 'Esta explicação já foi processada.');
        }

        try {
            DB::table('explicacoes')
                ->where('id', $id)
                ->update([
                    'aprovacao_admin' => 'rejeitada',
                    'aprovada_por' => Auth::id(),
                    'data_aprovacao' => now(),
                    'motivo_rejeicao' => $request->motivo_rejeicao,
                    'updated_at' => now()
                ]);

            // Recarregar a explicação
            $explicacao->refresh();

            // === ENVIAR NOTIFICAÇÃO DE REJEIÇÃO APENAS PARA O ALUNO ===
            try {
                if ($explicacao->user && $explicacao->user->email) {
                    \Illuminate\Support\Facades\Notification::route('mail', $explicacao->user->email)
                        ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $explicacao->user->email, 'rejeitada'));
                    
                    \Log::info('Notificação de rejeição enviada para o aluno', [
                        'aluno' => $explicacao->user->name,
                        'email' => $explicacao->user->email,
                        'motivo' => $request->motivo_rejeicao
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar notificação de rejeição: ' . $e->getMessage());
            }

            // Log da atividade
            \Log::info("Explicação rejeitada", [
                'explicacao_id' => $id,
                'rejeitada_por' => Auth::user()->name,
                'motivo' => $request->motivo_rejeicao
            ]);

            return redirect()->back()->with('success', 'Explicação rejeitada com sucesso! O aluno foi notificado por email.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao rejeitar explicação: ' . $e->getMessage());
        }
    }

    /**
     * Aprovar múltiplas explicações
     */
    public function aprovarMultiplas(Request $request)
    {
        $request->validate([
            'explicacoes' => 'required|array|min:1',
            'explicacoes.*' => 'exists:explicacoes,id'
        ]);

        $aprovadas = 0;
        $errors = [];
        $notificacoesEnviadas = 0;

        try {
            DB::beginTransaction();

            foreach ($request->explicacoes as $explicacaoId) {
                $explicacao = Explicacao::with('user')->find($explicacaoId);
                
                if ($explicacao && $explicacao->aprovacao_admin === 'pendente') {
                    DB::table('explicacoes')
                        ->where('id', $explicacaoId)
                        ->update([
                            'aprovacao_admin' => 'aprovada',
                            'status' => 'confirmada',
                            'aprovada_por' => Auth::id(),
                            'data_aprovacao' => now(),
                            'motivo_rejeicao' => null,
                            'updated_at' => now()
                        ]);
                    
                    $aprovadas++;

                    // Recarregar e enviar notificação apenas para o aluno
                    $explicacao->refresh();
                    
                    try {
                        if ($explicacao->user && $explicacao->user->email) {
                            \Illuminate\Support\Facades\Notification::route('mail', $explicacao->user->email)
                                ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $explicacao->user->email, 'aprovada'));
                            $notificacoesEnviadas++;
                        }
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar notificação para explicação {$explicacaoId}: " . $e->getMessage());
                    }
                } else {
                    $errors[] = "Explicação ID {$explicacaoId} já foi processada";
                }
            }

            DB::commit();

            $message = "$aprovadas explicações aprovadas com sucesso! $notificacoesEnviadas notificações enviadas aos alunos.";
            
            if (!empty($errors)) {
                $message .= " Avisos: " . implode(', ', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao aprovar explicações: ' . $e->getMessage());
        }
    }

    /**
     * Reverter aprovação (voltar para pendente)
     */
    public function reverterAprovacao($id)
    {
        $explicacao = Explicacao::findOrFail($id);

        if (!in_array($explicacao->aprovacao_admin, ['aprovada', 'rejeitada'])) {
            return redirect()->back()->with('error', 'Esta explicação não pode ser revertida.');
        }

        try {
            DB::table('explicacoes')
                ->where('id', $id)
                ->update([
                    'aprovacao_admin' => 'pendente',
                    'aprovada_por' => null,
                    'data_aprovacao' => null,
                    'motivo_rejeicao' => null,
                    'updated_at' => now()
                ]);

            return redirect()->back()->with('success', 'Aprovação revertida com sucesso! A explicação voltou para pendente.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erro ao reverter aprovação: ' . $e->getMessage());
        }
    }

    /**
     * Relatório de aprovações
     */
    public function relatorioAprovacoes(Request $request)
    {
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

        // Top alunos com mais aprovações
        $topProfessores = User::select('users.*')
            ->selectRaw('COUNT(explicacoes.id) as total_aprovadas')
            ->join('explicacoes', 'users.id', '=', 'explicacoes.user_id')
            ->where('explicacoes.aprovacao_admin', 'aprovada')
            ->whereBetween('explicacoes.data_aprovacao', [$dataInicio, $dataFim])
            ->groupBy('users.id')
            ->orderBy('total_aprovadas', 'desc')
            ->limit(10)
            ->get();

        // Motivos de rejeição mais comuns
        $motivosRejeicao = Explicacao::select('motivo_rejeicao')
            ->selectRaw('COUNT(*) as total')
            ->where('aprovacao_admin', 'rejeitada')
            ->whereBetween('data_aprovacao', [$dataInicio, $dataFim])
            ->whereNotNull('motivo_rejeicao')
            ->groupBy('motivo_rejeicao')
            ->orderBy('total', 'desc')
            ->limit(10)
            ->get();

        return view('admin.relatorio-aprovacoes', compact(
            'stats', 
            'aprovacoesPorDia', 
            'topProfessores',
            'motivosRejeicao',
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
        try {
            $count = Explicacao::where('aprovacao_admin', 'pendente')->count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json(['count' => 0, 'error' => $e->getMessage()], 500);
        }
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

    /**
     * Estatísticas do dashboard em tempo real
     */
    public function estatisticasAoVivo()
    {
        try {
            $stats = [
                'total_explicacoes' => Explicacao::count(),
                'pendentes_aprovacao' => Explicacao::where('aprovacao_admin', 'pendente')->count(),
                'aprovadas_hoje' => Explicacao::where('aprovacao_admin', 'aprovada')
                    ->whereDate('data_aprovacao', Carbon::today())->count(),
                'rejeitadas_hoje' => Explicacao::where('aprovacao_admin', 'rejeitada')
                    ->whereDate('data_aprovacao', Carbon::today())->count(),
                'total_professores' => User::where('role', 'professor')->count(),
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Buscar explicações com filtros via AJAX
     */
    public function buscarExplicacoes(Request $request)
    {
        try {
            $query = Explicacao::with(['user']);

            // Aplicar filtros
            if ($request->filled('status')) {
                $query->where('aprovacao_admin', $request->status);
            }

            if ($request->filled('disciplina')) {
                $query->where('disciplina', 'LIKE', '%' . $request->disciplina . '%');
            }

            if ($request->filled('data_inicio')) {
                $query->where('data_explicacao', '>=', $request->data_inicio);
            }

            if ($request->filled('data_fim')) {
                $query->where('data_explicacao', '<=', $request->data_fim);
            }

            $explicacoes = $query->orderBy('created_at', 'desc')
                ->limit(50)
                ->get();

            return response()->json([
                'success' => true,
                'explicacoes' => $explicacoes,
                'total' => $explicacoes->count()
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Rejeitar múltiplas explicações
     */
    public function rejeitarMultiplas(Request $request)
    {
        $request->validate([
            'explicacoes' => 'required|array|min:1',
            'explicacoes.*' => 'exists:explicacoes,id',
            'motivo_rejeicao' => 'required|string|min:10|max:500'
        ]);

        $rejeitadas = 0;
        $errors = [];
        $notificacoesEnviadas = 0;

        try {
            DB::beginTransaction();

            foreach ($request->explicacoes as $explicacaoId) {
                $explicacao = Explicacao::with('user')->find($explicacaoId);
                
                if ($explicacao && $explicacao->aprovacao_admin === 'pendente') {
                    DB::table('explicacoes')
                        ->where('id', $explicacaoId)
                        ->update([
                            'aprovacao_admin' => 'rejeitada',
                            'aprovada_por' => Auth::id(),
                            'data_aprovacao' => now(),
                            'motivo_rejeicao' => $request->motivo_rejeicao,
                            'updated_at' => now()
                        ]);
                    
                    $rejeitadas++;

                    // Recarregar e enviar notificação apenas para o aluno
                    $explicacao->refresh();
                    
                    try {
                        if ($explicacao->user && $explicacao->user->email) {
                            \Illuminate\Support\Facades\Notification::route('mail', $explicacao->user->email)
                                ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $explicacao->user->email, 'rejeitada'));
                            $notificacoesEnviadas++;
                        }
                    } catch (\Exception $e) {
                        \Log::error("Erro ao enviar notificação de rejeição para explicação {$explicacaoId}: " . $e->getMessage());
                    }
                } else {
                    $errors[] = "Explicação ID {$explicacaoId} já foi processada";
                }
            }

            DB::commit();

            $message = "$rejeitadas explicações rejeitadas com sucesso! $notificacoesEnviadas notificações enviadas aos alunos.";
            
            if (!empty($errors)) {
                $message .= " Avisos: " . implode(', ', $errors);
            }

            return redirect()->back()->with('success', $message);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Erro ao rejeitar explicações: ' . $e->getMessage());
        }
    }

    /**
     * Histórico de ações do admin
     */
    public function historicoAcoes(Request $request)
    {
        $query = Explicacao::with(['user', 'aprovadoPor'])
            ->whereNotNull('data_aprovacao');

        if ($request->filled('acao')) {
            $query->where('aprovacao_admin', $request->acao);
        }

        if ($request->filled('admin')) {
            $query->where('aprovada_por', $request->admin);
        }

        if ($request->filled('data_inicio')) {
            $query->whereDate('data_aprovacao', '>=', $request->data_inicio);
        }

        if ($request->filled('data_fim')) {
            $query->whereDate('data_aprovacao', '<=', $request->data_fim);
        }

        $historico = $query->orderBy('data_aprovacao', 'desc')->paginate(20);

        return view('admin.historico-acoes', compact('historico'));
    }
}