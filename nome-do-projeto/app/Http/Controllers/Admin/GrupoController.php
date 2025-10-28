<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;

class GrupoController extends Controller
{
    public function index()
    {
        // Verificar se é admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        $grupos = Grupo::with(['criador', 'membros', 'ultimaMensagem'])
            ->withCount('membros')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('admin.grupos.index', compact('grupos'));
    }

    public function create()
{
    // Verificar se é admin
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
    }

    // ALTERADO: Incluir todos os usuários (inclusive admins)
    $usuarios = User::orderBy('name')->get();

    return view('admin.grupos.create', compact('usuarios'));
}

    public function store(Request $request)
{
    // Verificar se é admin
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
    }

    $validated = $request->validate([
        'nome' => 'required|string|max:255',
        'descricao' => 'nullable|string|max:1000',
        'icone' => 'required|string|max:50',
        'cor' => 'required|string|max:20',
        'membros' => 'required|array|min:2',
        'membros.*' => 'exists:users,id',
        'adicionar_me' => 'nullable|boolean', // NOVO
    ]);

    $grupo = Grupo::create([
        'nome' => $validated['nome'],
        'descricao' => $validated['descricao'],
        'icone' => $validated['icone'],
        'cor' => $validated['cor'],
        'criado_por' => auth()->id(),
    ]);

    // NOVO: Adicionar o admin automaticamente se marcado
    $membros = $validated['membros'];
    if ($request->has('adicionar_me') && !in_array(auth()->id(), $membros)) {
        $membros[] = auth()->id();
    }

    // Adicionar membros
    foreach ($membros as $userId) {
        $grupo->membros()->attach($userId, [
            'admin_grupo' => false,
            'notificacoes_ativas' => true,
        ]);
    }

    return redirect()->route('admin.grupos.index')
        ->with('success', 'Grupo criado com sucesso!');
}

    public function edit(Grupo $grupo)
{
    // Verificar se é admin
    if (auth()->user()->role !== 'admin') {
        abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
    }

    $grupo->load('membros');
    
    // ALTERADO: Incluir todos os usuários (inclusive admins)
    $usuarios = User::orderBy('name')->get();

    return view('admin.grupos.edit', compact('grupo', 'usuarios'));
}

    public function update(Request $request, Grupo $grupo)
    {
        // Verificar se é admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'icone' => 'required|string|max:50',
            'cor' => 'required|string|max:20',
            'ativo' => 'boolean',
            'membros' => 'required|array|min:2',
            'membros.*' => 'exists:users,id',
        ]);

        $grupo->update([
            'nome' => $validated['nome'],
            'descricao' => $validated['descricao'],
            'icone' => $validated['icone'],
            'cor' => $validated['cor'],
            'ativo' => $request->has('ativo'),
        ]);

        $grupo->membros()->sync($validated['membros']);

        return redirect()->route('admin.grupos.index')
            ->with('success', 'Grupo atualizado com sucesso!');
    }

    public function destroy(Grupo $grupo)
    {
        // Verificar se é admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        $grupo->delete();

        return redirect()->route('admin.grupos.index')
            ->with('success', 'Grupo eliminado com sucesso!');
    }

    public function toggleAtivo(Grupo $grupo)
    {
        // Verificar se é admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        $grupo->update(['ativo' => !$grupo->ativo]);

        return back()->with('success', 'Estado do grupo alterado com sucesso!');
    }
}