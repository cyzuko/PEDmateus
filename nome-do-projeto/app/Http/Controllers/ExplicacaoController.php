<?php

namespace App\Http\Controllers;

use App\Models\Explicacao;
use App\Models\Disciplina;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ExplicacaoController extends Controller
{
    /**
     * Mostrar lista de explicações
     */
    public function index()
    {
        $user = auth()->user();
        
        // Buscar explicações do utilizador logado com informações de aprovação
        $explicacoes = Explicacao::with(['aprovadoPor'])
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->paginate(10);
        
        // Estatísticas para o dashboard do professor
        $stats = [
            'total' => Explicacao::where('user_id', $user->id)->count(),
            'pendentes' => Explicacao::where('user_id', $user->id)
                ->where('aprovacao_admin', 'pendente')
                ->count(),
            'aprovadas' => Explicacao::where('user_id', $user->id)
                ->where('aprovacao_admin', 'aprovada')
                ->count(),
            'rejeitadas' => Explicacao::where('user_id', $user->id)
                ->where('aprovacao_admin', 'rejeitada')
                ->count(),
        ];
        
        return view('explicacoes.index', compact('explicacoes', 'stats'));
    }

    /**
     * Mostrar formulário para criar nova explicação
     */
    public function create()
    {
        return view('explicacoes.create');
    }

    /**
     * Guardar nova explicação
     */
    public function store(Request $request)
    {
        // Validação melhorada
        $validated = $request->validate([
            'disciplina' => 'required|string|max:255',
            'data_explicacao' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'local' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'nome_aluno' => 'required|string|max:255',
            'contacto_aluno' => 'required|string|max:255',
            'enviar_email_aluno' => 'nullable|boolean',
            'enviar_email_admin' => 'nullable|boolean',
        ]);

        try {
            // Usar o modelo Eloquent em vez de query builder direto
            $explicacao = new Explicacao();
            $explicacao->user_id = Auth::id();
            $explicacao->disciplina = $validated['disciplina'];
            $explicacao->data_explicacao = $validated['data_explicacao'];
            $explicacao->hora_inicio = $validated['hora_inicio'];
            $explicacao->hora_fim = $validated['hora_fim'];
            $explicacao->local = $validated['local'];
            $explicacao->preco = $validated['preco'];
            $explicacao->observacoes = $validated['observacoes'];
            $explicacao->nome_aluno = $validated['nome_aluno'];
            $explicacao->contacto_aluno = $validated['contacto_aluno'];
            $explicacao->status = 'agendada';
            $explicacao->aprovacao_admin = 'pendente';
            $explicacao->save();

            // Carregar o relacionamento user para ter acesso aos dados do professor
            $explicacao->load('user');

            // === ENVIO DE NOTIFICAÇÕES ===
            
            // 1. Sempre notificar o professor que criou a explicação
            try {
                $user = Auth::user();
                if ($user && $user->email) {
                    \Illuminate\Support\Facades\Notification::route('mail', $user->email)
                        ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $user->email, 'criada'));
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao enviar notificação para o professor: ' . $e->getMessage());
            }

            // 2. Notificar administradores se opção selecionada
            if ($request->has('enviar_email_admin') && $request->enviar_email_admin) {
                $emailsAdmin = [
                    'mateus23viana@gmail.com',
                ];

                foreach ($emailsAdmin as $emailAdmin) {
                    if (!empty($emailAdmin) && filter_var($emailAdmin, FILTER_VALIDATE_EMAIL)) {
                        try {
                            \Illuminate\Support\Facades\Notification::route('mail', $emailAdmin)
                                ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $emailAdmin, 'criada'));
                        } catch (\Exception $e) {
                            \Log::error("Erro ao enviar notificação para admin ($emailAdmin): " . $e->getMessage());
                        }
                    }
                }
            }

            // 3. Notificar o aluno se opção selecionada e tiver email válido
            if ($request->has('enviar_email_aluno') && $request->enviar_email_aluno) {
                if (!empty($explicacao->contacto_aluno) && filter_var($explicacao->contacto_aluno, FILTER_VALIDATE_EMAIL)) {
                    try {
                        \Illuminate\Support\Facades\Notification::route('mail', $explicacao->contacto_aluno)
                            ->notify(new \App\Notifications\NovaExplicacaoNotification($explicacao, $explicacao->contacto_aluno, 'criada'));
                    } catch (\Exception $e) {
                        \Log::error('Erro ao enviar notificação para o aluno: ' . $e->getMessage());
                    }
                }
            }

            $mensagem = 'Explicação criada com sucesso e enviada para aprovação!';
            if ($request->enviar_email_aluno || $request->enviar_email_admin) {
                $mensagem .= ' Notificações foram enviadas.';
            }

            return redirect()->route('explicacoes.index')->with('success', $mensagem);

        } catch (\Exception $e) {
            \Log::error('Erro ao criar explicação: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Erro ao criar explicação: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar detalhes de uma explicação
     */
    public function show($id)
    {
        $explicacao = Explicacao::with(['aprovadoPor'])
            ->where('user_id', Auth::id())
            ->findOrFail($id);
        return view('explicacoes.show', compact('explicacao'));
    }

    /**
     * Mostrar formulário para editar explicação
     */
    public function edit($id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
        
        if ($explicacao->status === 'concluida') {
            return redirect()->route('explicacoes.index')
                ->with('error', 'Esta explicação não pode ser editada pois já foi concluída.');
        }
        
        return view('explicacoes.edit', compact('explicacao'));
    }

    /**
     * Atualizar explicação
     */
    public function update(Request $request, $id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);

        if ($explicacao->status === 'concluida') {
            return redirect()->route('explicacoes.index')
                ->with('error', 'Esta explicação não pode ser editada pois já foi concluída.');
        }

        $validated = $request->validate([
            'disciplina' => 'required|string|max:255',
            'data_explicacao' => 'required|date|after_or_equal:today',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'local' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'nome_aluno' => 'required|string|max:255',
            'contacto_aluno' => 'required|string|max:255',
        ]);

        try {
            // Se a explicação foi confirmada (aprovada), resetar para agendada para nova aprovação
            if ($explicacao->aprovacao_admin === 'aprovada' && $explicacao->status === 'confirmada') {
                $validated['status'] = 'agendada';
                $validated['aprovacao_admin'] = 'pendente';
                $validated['motivo_rejeicao'] = null;
                $validated['aprovada_por'] = null;
                $validated['data_aprovacao'] = null;
            }
            
            // Resetar aprovação se foi rejeitada antes
            if ($explicacao->aprovacao_admin === 'rejeitada') {
                $validated['aprovacao_admin'] = 'pendente';
                $validated['motivo_rejeicao'] = null;
                $validated['aprovada_por'] = null;
                $validated['data_aprovacao'] = null;
            }

            $explicacao->update($validated);

            return redirect()->route('explicacoes.index')
                ->with('success', 'Explicação atualizada com sucesso e enviada para nova aprovação!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao atualizar: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Eliminar explicação
     */
    public function destroy($id)
    {
        try {
            $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
            
            if ($explicacao->status === 'concluida') {
                return redirect()->route('explicacoes.index')
                    ->with('error', 'Não é possível eliminar uma explicação concluída.');
            }

            $explicacao->delete();

            return redirect()->route('explicacoes.index')
                ->with('success', 'Explicação eliminada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao eliminar: ' . $e->getMessage()]);
        }
    }

    /**
     * Cancelar explicação
     */
    public function cancelar($id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
        
        if (!in_array($explicacao->status, ['agendada', 'confirmada'])) {
            return redirect()->back()->with('error', 'Esta explicação não pode ser cancelada.');
        }

        $explicacao->update(['status' => 'cancelada']);

        return redirect()->back()->with('success', 'Explicação cancelada!');
    }

    /**
     * Marcar explicação como concluída
     */
    public function concluir($id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
        
        if ($explicacao->status !== 'confirmada') {
            return redirect()->back()->with('error', 'Só é possível concluir explicações confirmadas (aprovadas pelo admin).');
        }

        $explicacao->update(['status' => 'concluida']);

        return redirect()->back()->with('success', 'Explicação marcada como concluída!');
    }

    /**
     * Vista do calendário - VERSÃO SIMPLIFICADA
     */
    public function calendario(Request $request)
    {
        $user = auth()->user();
        
        // Obter mês e ano da URL ou usar valores padrão
        $mesAtual = (int) $request->get('mes', date('n'));
        $anoAtual = (int) $request->get('ano', date('Y'));
        
        // Validar valores
        if ($mesAtual < 1 || $mesAtual > 12) {
            $mesAtual = date('n');
        }
        if ($anoAtual < 2020 || $anoAtual > 2030) {
            $anoAtual = date('Y');
        }
        
        // Calcular navegação
        $mesAnterior = $mesAtual - 1;
        $anoAnterior = $anoAtual;
        if ($mesAnterior < 1) {
            $mesAnterior = 12;
            $anoAnterior = $anoAtual - 1;
        }
        
        $mesProximo = $mesAtual + 1;
        $anoProximo = $anoAtual;
        if ($mesProximo > 12) {
            $mesProximo = 1;
            $anoProximo = $anoAtual + 1;
        }
        
        // Calcular primeiro e último dia do mês
        $primeiroDiaDoMes = date('Y-m-d', mktime(0, 0, 0, $mesAtual, 1, $anoAtual));
        $ultimoDiaDoMes = date('Y-m-d', mktime(0, 0, 0, $mesAtual + 1, 0, $anoAtual));
        
        // Buscar explicações baseado no tipo de usuário
        $query = Explicacao::whereBetween('data_explicacao', [$primeiroDiaDoMes, $ultimoDiaDoMes])
            ->orderBy('data_explicacao')
            ->orderBy('hora_inicio');
        
        if ($user->is_admin) {
            // Admin vê tudo
            $explicacoes = $query->get();
            $modoVisualizacao = 'admin';
        } else {
            // Usuário normal vê:
            // 1. Todas as suas explicações (qualquer status)
            // 2. Explicações confirmadas de outros
            $explicacoes = $query->where(function($q) use ($user) {
                $q->where('user_id', $user->id)
                  ->orWhere(function($q2) use ($user) {
                      $q2->where('user_id', '!=', $user->id)
                         ->where('aprovacao_admin', 'aprovada')
                         ->whereIn('status', ['confirmada', 'concluida']);
                  });
            })->get();
            $modoVisualizacao = 'usuario';
        }
            
        return view('explicacoes.calendario', compact(
            'explicacoes', 
            'mesAtual', 
            'anoAtual',
            'mesAnterior',
            'anoAnterior', 
            'mesProximo',
            'anoProximo',
            'modoVisualizacao'
        ));
    }

    /**
     * Mostrar página de disponibilidade - VERSÃO SIMPLIFICADA
     */
    public function disponibilidade(Request $request)
    {
        $user = auth()->user();
        
        // Obter a semana selecionada
        $semanaInicio = $request->get('semana', date('Y-m-d', strtotime('monday this week')));
        
        // Calcular início e fim da semana
        $inicioSemana = date('Y-m-d', strtotime($semanaInicio));
        $fimSemana = date('Y-m-d', strtotime($inicioSemana . ' +6 days'));
        
        $modoVisualizacao = 'usuario';
        
        // Buscar explicações baseado no tipo de usuário
        $query = Explicacao::whereBetween('data_explicacao', [$inicioSemana, $fimSemana])
            ->orderBy('data_explicacao')
            ->orderBy('hora_inicio');
        
        if (isset($user->is_admin) && $user->is_admin) {
            // Admin vê todas confirmadas/concluídas
            $explicacoes = $query->where('aprovacao_admin', 'aprovada')
                ->whereIn('status', ['confirmada', 'concluida'])
                ->get();
            $modoVisualizacao = 'admin';
        } else {
            // Usuário normal vê:
            // 1. Suas explicações confirmadas/concluídas
            // 2. Explicações confirmadas/concluídas de outros
            $explicacoes = $query->where(function($q) use ($user) {
                $q->where(function($q2) use ($user) {
                    $q2->where('user_id', $user->id)
                       ->where('aprovacao_admin', 'aprovada')
                       ->whereIn('status', ['confirmada', 'concluida']);
                })
                ->orWhere(function($q2) use ($user) {
                    $q2->where('user_id', '!=', $user->id)
                       ->where('aprovacao_admin', 'aprovada')
                       ->whereIn('status', ['confirmada', 'concluida']);
                });
            })->get();
        }

        $disciplinas = Disciplina::ativas()->get();
        
        return view('explicacoes.disponibilidade', compact('explicacoes', 'inicioSemana', 'fimSemana', 'modoVisualizacao', 'disciplinas'));
    }

    /**
     * Obter cor para o status
     */
    private function getStatusColor($status, $aprovacao = null)
    {
        // Se não foi aprovada, usar cores mais apagadas
        if ($aprovacao === 'pendente') {
            return '#6c757d'; // Cinza para pendentes
        } elseif ($aprovacao === 'rejeitada') {
            return '#dc3545'; // Vermelho para rejeitadas
        }
        
        // Cores para explicações aprovadas
        $colors = [
            'agendada' => '#ffc107',     // Amarelo
            'confirmada' => '#28a745',   // Verde
            'concluida' => '#17a2b8',    // Azul
            'cancelada' => '#fd7e14'     // Laranja
        ];
        
        return $colors[$status] ?? '#6c757d';
    }
}