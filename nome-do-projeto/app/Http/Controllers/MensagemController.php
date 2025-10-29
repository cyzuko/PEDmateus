<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Mensagem;
use App\Models\MensagemLeitura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class MensagemController extends Controller
{
    public function index()
    {
        // OTIMIZADO: Eager loading e ordenação por última mensagem
        $userId = auth()->id();
        
        $grupos = auth()->user()->grupos()
            ->where('ativo', true)
            ->with([
                'ultimaMensagem.user:id,name',
                'membros:id'
            ])
            ->withCount('mensagens')
            ->get()
            ->sortByDesc(function($grupo) {
                return $grupo->ultimaMensagem ? $grupo->ultimaMensagem->created_at : $grupo->created_at;
            })
            ->map(function ($grupo) use ($userId) {
                // Calcular mensagens não lidas de forma otimizada
                $grupo->nao_lidas = Cache::remember(
                    "grupo_{$grupo->id}_nao_lidas_{$userId}",
                    30, // 30 segundos de cache
                    function() use ($grupo, $userId) {
                        return $this->contarNaoLidasOtimizado($grupo->id, $userId);
                    }
                );
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

        // OTIMIZADO: Carregar apenas as últimas 100 mensagens
        $mensagens = $grupo->mensagens()
            ->with('user:id,name')
            ->latest('id')
            ->limit(100)
            ->get()
            ->reverse()
            ->values();

        // Marcar mensagens como lidas em background
        $this->marcarComoLidasOtimizado($grupo->id, auth()->id());

        // Limpar cache de mensagens não lidas
        Cache::forget("grupo_{$grupo->id}_nao_lidas_" . auth()->id());

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
            'conteudo' => trim($validated['conteudo']),
            'tipo' => 'texto',
        ]);

        // Marcar como lida pelo autor imediatamente
        MensagemLeitura::create([
            'mensagem_id' => $mensagem->id,
            'user_id' => auth()->id(),
            'lida_em' => now(),
        ]);

        // Limpar cache de todos os membros
        foreach ($grupo->membros as $membro) {
            if ($membro->id != auth()->id()) {
                Cache::forget("grupo_{$grupo->id}_nao_lidas_{$membro->id}");
            }
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'mensagem' => $mensagem->load('user:id,name'),
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
            'conteudo' => trim($validated['conteudo']),
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

        $grupoId = $mensagem->grupo_id;
        $mensagem->delete();

        // Limpar cache
        Cache::forget("grupo_{$grupoId}_nao_lidas_" . auth()->id());

        return back()->with('success', 'Mensagem eliminada!');
    }

    /**
     * OTIMIZADO: Marcar como lidas usando query única
     */
    private function marcarComoLidasOtimizado($grupoId, $userId)
    {
        // Buscar IDs de mensagens não lidas
        $mensagensNaoLidas = DB::table('mensagens')
            ->where('grupo_id', $grupoId)
            ->where('user_id', '!=', $userId)
            ->whereNotExists(function($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('mensagem_leituras')
                    ->whereColumn('mensagem_leituras.mensagem_id', 'mensagens.id')
                    ->where('mensagem_leituras.user_id', $userId);
            })
            ->pluck('id');

        if ($mensagensNaoLidas->isEmpty()) {
            return;
        }

        // Preparar dados para inserção em batch
        $now = now();
        $dados = $mensagensNaoLidas->map(function($mensagemId) use ($userId, $now) {
            return [
                'mensagem_id' => $mensagemId,
                'user_id' => $userId,
                'lida_em' => $now,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        })->toArray();

        // Inserir todas de uma vez
        if (!empty($dados)) {
            DB::table('mensagem_leituras')->insert($dados);
        }
    }

    /**
     * OTIMIZADO: Contar não lidas com query eficiente
     */
    private function contarNaoLidasOtimizado($grupoId, $userId)
    {
        return DB::table('mensagens')
            ->where('grupo_id', $grupoId)
            ->where('user_id', '!=', $userId)
            ->whereNotExists(function($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('mensagem_leituras')
                    ->whereColumn('mensagem_leituras.mensagem_id', 'mensagens.id')
                    ->where('mensagem_leituras.user_id', $userId);
            })
            ->count();
    }

    public function carregarNovas(Request $request, Grupo $grupo)
    {
        $ultimaMensagemId = $request->input('ultima_mensagem_id', 0);

        $novasMensagens = $grupo->mensagens()
            ->where('id', '>', $ultimaMensagemId)
            ->with('user:id,name')
            ->orderBy('id', 'asc')
            ->limit(50) // Limitar a 50 mensagens por request
            ->get();

        // Marcar como lidas apenas as novas mensagens
        if ($novasMensagens->isNotEmpty()) {
            $mensagensIds = $novasMensagens
                ->where('user_id', '!=', auth()->id())
                ->pluck('id')
                ->toArray();
            
            if (!empty($mensagensIds)) {
                $now = now();
                $dados = collect($mensagensIds)->map(function($mensagemId) use ($now) {
                    return [
                        'mensagem_id' => $mensagemId,
                        'user_id' => auth()->id(),
                        'lida_em' => $now,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                })->toArray();

                // Usar insertIgnore para evitar duplicatas
                foreach ($dados as $dado) {
                    DB::table('mensagem_leituras')
                        ->insertOrIgnore($dado);
                }
            }

            // Limpar cache
            Cache::forget("grupo_{$grupo->id}_nao_lidas_" . auth()->id());
        }

        return response()->json($novasMensagens);
    }
}