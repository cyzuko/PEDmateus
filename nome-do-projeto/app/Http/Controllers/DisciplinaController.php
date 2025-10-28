<?php

    namespace App\Http\Controllers;

    use App\Models\Disciplina;
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
            });
        }

        public function index()
        {
            $disciplinas = Disciplina::orderBy('ordem')->orderBy('nome')->get();
            return view('disciplinas.index', compact('disciplinas'));
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
            ], [
                'nome.unique' => 'Já existe uma disciplina com este nome.',
                'sala.required' => 'A sala é obrigatória.',
                'hora_fim.after' => 'A hora de fim deve ser posterior à hora de início.',
            ]);

            // Pegar a maior ordem e adicionar 1
            $validated['ordem'] = Disciplina::max('ordem') + 1;
            $validated['ativa'] = true;
            $validated['disciplina'] = $validated['nome']; // Preencher o campo disciplina também

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
            ], [
                'nome.unique' => 'Já existe uma disciplina com este nome.',
                'sala.required' => 'A sala é obrigatória.',
                'hora_fim.after' => 'A hora de fim deve ser posterior à hora de início.',
            ]);

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