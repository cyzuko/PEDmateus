<?php

namespace App\Http\Controllers;

use App\Models\Disciplina;
use App\Models\Explicacao;
use Illuminate\Http\Request;

class DisciplinaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!auth()->check() || auth()->user()->role !== 'admin') {
                abort(403, 'Acesso negado. Apenas administradores podem gerir disciplinas.');
            }
            return $next($request);
        })->except(['disponibilidade']);
    }

    public function index()
    {
        $disciplinas = Disciplina::orderBy('ordem')->orderBy('nome')->get();
        return view('disciplinas.index', compact('disciplinas'));
    }

    /**
     * Exibir a grade de disponibilidade de horários
     * Método público acessível a todos os usuários autenticados
     */
    public function disponibilidade(Request $request)
    {
        $user = auth()->user();
        
        // Buscar disciplinas ativas ordenadas
        $disciplinas = Disciplina::where('ativa', true)
            ->orderBy('ordem')
            ->orderBy('nome')
            ->get();
        
        // Buscar explicações baseado no role do usuário
        if ($user->role === 'admin') {
            // ========================================
            // ADMIN: Vê TODAS as explicações
            // ========================================
            $explicacoes = Explicacao::whereIn('status', ['confirmada', 'pendente', 'concluida'])
                ->orderBy('data_explicacao')
                ->orderBy('hora_inicio')
                ->get();
            
            $modoVisualizacao = 'admin';
            
        } else {
            // ========================================
            // USERS NORMAIS: Veem explicações com regras específicas
            // ========================================
            
            $explicacoes = Explicacao::where(function($query) use ($user) {
                // 1. TODAS as suas próprias explicações (qualquer status)
                $query->where('user_id', $user->id)
                    // OU
                    // 2. Explicações de OUTROS alunos que estejam CONFIRMADAS ou CONCLUÍDAS
                    ->orWhere(function($q) use ($user) {
                        $q->where('user_id', '!=', $user->id)
                          ->whereIn('status', ['confirmada', 'concluida']);
                    });
            })
            ->orderBy('data_explicacao')
            ->orderBy('hora_inicio')
            ->get();
            
            $modoVisualizacao = 'user';
        }
        
        // Debug (remova em produção)
        \Log::info('Disponibilidade carregada', [
            'user_id' => $user->id,
            'role' => $user->role,
            'total_explicacoes' => $explicacoes->count(),
            'minhas_explicacoes' => $explicacoes->where('user_id', $user->id)->count(),
            'outros_alunos' => $explicacoes->where('user_id', '!=', $user->id)->count(),
        ]);
        
        'explicacoes.disponibilidade', compact('disciplinas', 'explicacoes', 'modoVisualizacao'));return view(
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:disciplinas,nome',
            'emoji' => 'required|string|max:10',
            'capacidade' => 'required|integer|min:1|max:20',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'cor_badge' => 'nullable|string|max:20',
            'sala' => 'required|string|max:100',
            'horarios_json' => 'nullable|json',
        ], [
            'nome.unique' => 'Já existe uma disciplina com este nome.',
            'sala.required' => 'A sala é obrigatória.',
            'hora_fim.after' => 'A hora de fim deve ser posterior à hora de início.',
        ]);

        // Validar horários JSON se fornecido
        if (!empty($validated['horarios_json'])) {
            $horarios = json_decode($validated['horarios_json'], true);
            if (empty($horarios)) {
                return back()->withInput()->with('error', 'Deve selecionar pelo menos um dia da semana com horário.');
            }
        }

        // Pegar a maior ordem e adicionar 1
        $validated['ordem'] = Disciplina::max('ordem') + 1;
        $validated['ativa'] = true;
        $validated['disciplina'] = $validated['nome'];

        Disciplina::create($validated);

        return redirect()->route('disciplinas.index')->with('success', 'Disciplina criada com sucesso!');
    }

    public function update(Request $request, Disciplina $disciplina)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255|unique:disciplinas,nome,' . $disciplina->id,
            'emoji' => 'required|string|max:10',
            'capacidade' => 'required|integer|min:1|max:20',
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fim' => 'required|date_format:H:i|after:hora_inicio',
            'cor_badge' => 'nullable|string|max:20',
            'sala' => 'required|string|max:100',
            'horarios_json' => 'nullable|json',
        ], [
            'nome.unique' => 'Já existe uma disciplina com este nome.',
            'sala.required' => 'A sala é obrigatória.',
            'hora_fim.after' => 'A hora de fim deve ser posterior à hora de início.',
        ]);

        // Validar horários JSON se fornecido
        if (!empty($validated['horarios_json'])) {
            $horarios = json_decode($validated['horarios_json'], true);
            if (empty($horarios)) {
                return back()->withInput()->with('error', 'Deve selecionar pelo menos um dia da semana com horário.');
            }
        }

        $validated['disciplina'] = $validated['nome'];
        $disciplina->update($validated);

        return redirect()->route('disciplinas.index')->with('success', 'Disciplina atualizada com sucesso!');
    }

    public function toggleAtiva(Disciplina $disciplina)
    {
        $disciplina->update(['ativa' => !$disciplina->ativa]);
        
        $status = $disciplina->ativa ? 'ativada' : 'desativada';
        return redirect()->route('disciplinas.index')->with('success', "Disciplina {$status} com sucesso!");
    }

    public function destroy(Disciplina $disciplina)
    {
        // Verificar se há explicações associadas
        if ($disciplina->explicacoes()->count() > 0) {
            return redirect()->route('disciplinas.index')
                ->with('error', 'Não é possível remover esta disciplina pois existem explicações associadas.');
        }

        $disciplina->delete();
        return redirect()->route('disciplinas.index')->with('success', 'Disciplina removida com sucesso!');
    }
}