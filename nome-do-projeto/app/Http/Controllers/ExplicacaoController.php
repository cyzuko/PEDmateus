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
        $explicacoes = Explicacao::where('user_id', Auth::id())
                                ->orderBy('data_explicacao', 'desc')
                                ->paginate(10);

        return view('explicacoes.index', compact('explicacoes'));
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
        // Validação básica
        $request->validate([
            'disciplina' => 'required|string|max:255',
            'data_explicacao' => 'required|date',
            'hora_inicio' => 'required|string|max:5',
            'hora_fim' => 'required|string|max:5',
            'local' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'nome_aluno' => 'required|string|max:255',
            'contacto_aluno' => 'required|string|max:255',
        ]);

        try {
            // Inserção direta na base de dados
            DB::table('explicacoes')->insert([
                'user_id' => Auth::id(),
                'disciplina' => $request->disciplina,
                'data_explicacao' => $request->data_explicacao,
                'hora_inicio' => $request->hora_inicio,
                'hora_fim' => $request->hora_fim,
                'local' => $request->local,
                'preco' => $request->preco,
                'observacoes' => $request->observacoes,
                'nome_aluno' => $request->nome_aluno,
                'contacto_aluno' => $request->contacto_aluno,
                'status' => 'agendada',
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return redirect()->route('explicacoes.index')
                            ->with('success', 'Explicação criada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Mostrar detalhes de uma explicação
     */
    public function show($id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
        return view('explicacoes.show', compact('explicacao'));
    }

    /**
     * Mostrar formulário para editar explicação
     */
    public function edit($id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);
        return view('explicacoes.edit', compact('explicacao'));
    }

    /**
     * Atualizar explicação
     */
    public function update(Request $request, $id)
    {
        $explicacao = Explicacao::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'disciplina' => 'required|string|max:255',
            'data_explicacao' => 'required|date',
            'hora_inicio' => 'required|string|max:5',
            'hora_fim' => 'required|string|max:5',
            'local' => 'required|string|max:255',
            'preco' => 'required|numeric|min:0',
            'observacoes' => 'nullable|string|max:1000',
            'nome_aluno' => 'required|string|max:255',
            'contacto_aluno' => 'required|string|max:255',
        ]);

        try {
            DB::table('explicacoes')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->update([
                    'disciplina' => $request->disciplina,
                    'data_explicacao' => $request->data_explicacao,
                    'hora_inicio' => $request->hora_inicio,
                    'hora_fim' => $request->hora_fim,
                    'local' => $request->local,
                    'preco' => $request->preco,
                    'observacoes' => $request->observacoes,
                    'nome_aluno' => $request->nome_aluno,
                    'contacto_aluno' => $request->contacto_aluno,
                    'updated_at' => now(),
                ]);

            return redirect()->route('explicacoes.index')
                            ->with('success', 'Explicação atualizada com sucesso!');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Eliminar explicação
     */
    public function destroy($id)
    {
        try {
            DB::table('explicacoes')
                ->where('id', $id)
                ->where('user_id', Auth::id())
                ->delete();

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
        DB::table('explicacoes')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['status' => 'confirmada', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Explicação confirmada!');
    }

    /**
     * Cancelar explicação
     */
    public function cancelar($id)
    {
        DB::table('explicacoes')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['status' => 'cancelada', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Explicação cancelada!');
    }

    /**
     * Marcar explicação como concluída
     */
    public function concluir($id)
    {
        DB::table('explicacoes')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update(['status' => 'concluida', 'updated_at' => now()]);

        return redirect()->back()->with('success', 'Explicação marcada como concluída!');
    }

    /**
     * Vista do calendário
     */
    public function calendario()
    {
        $explicacoes = Explicacao::where('user_id', Auth::id())->get();
        return view('explicacoes.calendario', compact('explicacoes'));
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
            ->orderBy('data_explicacao')
            ->orderBy('hora_inicio')
            ->get();

        return view('explicacoes.disponibilidade', compact('explicacoes'));
    }  }