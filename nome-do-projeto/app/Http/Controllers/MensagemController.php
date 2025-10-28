<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Mensagem;
use App\Models\MensagemLeitura;
use Illuminate\Http\Request;

class MensagemController extends Controller
{
    public function index()
    {
        $grupos = auth()->user()->grupos()
            ->where('ativo', true)
            ->with(['ultimaMensagem.user'])
            ->withCount('mensagens')
            ->get()
            ->map(function ($grupo) {
                $grupo->nao_lidas = $grupo->mensagensNaoLidas(auth()->id());
                return $grupo;
            });

        return view('mensagens.index', compact('grupos'));
    }

    public function show(Grupo $grupo)
    {
        // Verificar se o usuário é membro
        if (!$grupo->membros->contains(auth()->id())) {
            abort(403, 'Você não tem acesso a este grupo.');
        }

        $mensagens = $grupo->mensagens()
            ->with(['user', 'leituras'])
            ->orderBy('created_at', 'asc')
            ->get();

        // Marcar mensagens como lidas
        $this->marcarComoLidas($grupo, auth()->id());

        return view('mensagens.show', compact('grupo', 'mensagens'));
    }

    public function store(Request $request, Grupo $grupo)
    {
        if (!$grupo->membros->contains(auth()->id())) {
            abort(403);
        }

        $validated = $request->validate([
            'conteudo' => 'required|string|max:5000',
        ]);

        $mensagem = Mensagem::create([
            'grupo_id' => $grupo->id,
            'user_id' => auth()->id(),
            'conteudo' => $validated['conteudo'],
            'tipo' => 'texto',
        ]);

        // Marcar como lida pelo autor
        MensagemLeitura::create([
            'mensagem_id' => $mensagem->id,
            'user_id' => auth()->id(),
            'lida_em' => now(),
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'mensagem' => $mensagem->load('user'),
            ]);
        }

        return back();
    }

    public function update(Request $request, Mensagem $mensagem)
    {
        if ($mensagem->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'conteudo' => 'required|string|max:5000',
        ]);

        $mensagem->update([
            'conteudo' => $validated['conteudo'],
            'editada' => true,
            'editada_em' => now(),
        ]);

        return back()->with('success', 'Mensagem editada!');
    }

    public function destroy(Mensagem $mensagem)
    {
        if ($mensagem->user_id !== auth()->id()) {
            abort(403);
        }

        $mensagem->delete();

        return back()->with('success', 'Mensagem eliminada!');
    }

    private function marcarComoLidas(Grupo $grupo, $userId)
    {
        $mensagensNaoLidas = $grupo->mensagens()
            ->where('user_id', '!=', $userId)
            ->whereDoesntHave('leituras', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->get();

        foreach ($mensagensNaoLidas as $mensagem) {
            MensagemLeitura::create([
                'mensagem_id' => $mensagem->id,
                'user_id' => $userId,
                'lida_em' => now(),
            ]);
        }
    }

    public function carregarNovas(Request $request, Grupo $grupo)
    {
        $ultimaMensagemId = $request->input('ultima_mensagem_id', 0);

        $novasMensagens = $grupo->mensagens()
            ->where('id', '>', $ultimaMensagemId)
            ->with('user')
            ->get();

        return response()->json($novasMensagens);
    }
}