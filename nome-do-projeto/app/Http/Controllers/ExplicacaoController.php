<?php

namespace App\Http\Controllers;

use App\Models\Explicacao;
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

            return redirect()->route('explicacoes.index')
                            ->with('success', 'Explicação criada com sucesso e enviada para aprovação!');

        } catch (\Exception $e) {
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
        
        // Verificar se pode ser editada
        if ($explicacao->aprovacao_admin === 'aprovada' && $explicacao->status !== 'agendada') {
            return redirect()->route('explicacoes.index')
                ->with('error', 'Esta explicação não pode ser editada pois já foi aprovada e confirmada.');
        }
        
        return view('explicacoes.edit', compact('explicacao'));
    }

    /**
     * Atualizar explicação
     */
    public function update(Request $request, $id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);

        // Verificar se pode ser editada
        if ($explicacao->aprovacao_admin === 'aprovada' && $explicacao->status !== 'agendada') {
            return redirect()->route('explicacoes.index')
                ->with('error', 'Esta explicação não pode ser editada.');
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
            // Resetar aprovação se foi rejeitada antes
            if ($explicacao->aprovacao_admin === 'rejeitada') {
                $validated['aprovacao_admin'] = 'pendente';
                $validated['motivo_rejeicao'] = null;
                $validated['aprovada_por'] = null;
                $validated['data_aprovacao'] = null;
            }

            $explicacao->update($validated);

            return redirect()->route('explicacoes.index')
                            ->with('success', 'Explicação atualizada com sucesso!');

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
            
            // Só permitir eliminar se não foi aprovada ou se foi rejeitada
            if ($explicacao->aprovacao_admin === 'aprovada' && $explicacao->status !== 'agendada') {
                return redirect()->route('explicacoes.index')
                    ->with('error', 'Não é possível eliminar uma explicação aprovada.');
            }

            $explicacao->delete();

            return redirect()->route('explicacoes.index')
                            ->with('success', 'Explicação eliminada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao eliminar: ' . $e->getMessage()]);
        }
    }

    /**
     * Confirmar explicação
     */
    public function confirmar($id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
        
        if ($explicacao->aprovacao_admin !== 'aprovada') {
            return redirect()->back()->with('error', 'Só é possível confirmar explicações aprovadas pelo administrador.');
        }
        
        if ($explicacao->status !== 'agendada') {
            return redirect()->back()->with('error', 'Esta explicação já foi processada.');
        }

        $explicacao->update(['status' => 'confirmada']);

        return redirect()->back()->with('success', 'Explicação confirmada!');
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
            return redirect()->back()->with('error', 'Só é possível concluir explicações confirmadas.');
        }

        $explicacao->update(['status' => 'concluida']);

        return redirect()->back()->with('success', 'Explicação marcada como concluída!');
    }

    /**
     * Vista do calendário
     */
    public function calendario()
    {
        $explicacoes = Explicacao::where('user_id', Auth::id())
            ->whereIn('status', ['agendada', 'confirmada', 'concluida'])
            ->where('aprovacao_admin', 'aprovada')
            ->get()
            ->map(function ($explicacao) {
                return [
                    'id' => $explicacao->id,
                    'title' => $explicacao->disciplina . ' - ' . $explicacao->nome_aluno,
                    'start' => $explicacao->data_explicacao . 'T' . $explicacao->hora_inicio,
                    'end' => $explicacao->data_explicacao . 'T' . $explicacao->hora_fim,
                    'backgroundColor' => $this->getStatusColor($explicacao->status),
                    'borderColor' => $this->getStatusColor($explicacao->status),
                    'url' => route('explicacoes.show', $explicacao->id)
                ];
            });
            
        return view('explicacoes.calendario', compact('explicacoes'));
    }

    /**
     * Obter cor para o status
     */
    private function getStatusColor($status)
    {
        $colors = [
            'agendada' => '#ffc107',
            'confirmada' => '#17a2b8',
            'concluida' => '#28a745',
            'cancelada' => '#dc3545'
        ];
        
        return $colors[$status] ?? '#6c757d';
    }

    /**
     * Mostrar página de disponibilidade
     */
    public function disponibilidade(Request $request)
    {
        // Obter a semana selecionada (padrão: segunda-feira da semana atual)
        $semanaInicio = $request->get('semana', date('Y-m-d', strtotime('monday this week')));
        
        // Calcular início e fim da semana
        $inicioSemana = date('Y-m-d', strtotime($semanaInicio));
        $fimSemana = date('Y-m-d', strtotime($inicioSemana . ' +6 days'));
        
        // Buscar explicações da semana atual do usuário logado
        $explicacoes = Explicacao::where('user_id', Auth::id())
            ->whereBetween('data_explicacao', [$inicioSemana, $fimSemana])
            ->where('aprovacao_admin', 'aprovada')
            ->whereIn('status', ['agendada', 'confirmada', 'concluida'])
            ->orderBy('data_explicacao')
            ->orderBy('hora_inicio')
            ->get();

        return view('explicacoes.disponibilidade', compact('explicacoes', 'inicioSemana', 'fimSemana'));
    }
}