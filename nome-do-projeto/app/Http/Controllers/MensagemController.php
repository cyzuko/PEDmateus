<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Mensagem;
use App\Models\MensagemLeitura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class MensagemController extends Controller
{
    public function index()
    {
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
                $grupo->nao_lidas = Cache::remember(
                    "grupo_{$grupo->id}_nao_lidas_{$userId}",
                    30,
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
        if (!$grupo->membros->contains(auth()->id())) {
            abort(403, 'Você não tem acesso a este grupo.');
        }

        $mensagens = $grupo->mensagens()
            ->with('user:id,name')
            ->latest('id')
            ->limit(100)
            ->get()
            ->reverse()
            ->values();

        $this->marcarComoLidasOtimizado($grupo->id, auth()->id());
        Cache::forget("grupo_{$grupo->id}_nao_lidas_" . auth()->id());

        return view('mensagens.show', compact('grupo', 'mensagens'));
    }
public function store(Request $request, Grupo $grupo)
{
    \Log::info('=== INÍCIO DO STORE ===');
    \Log::info('Grupo ID: ' . $grupo->id);
    \Log::info('User ID: ' . auth()->id());
    \Log::info('Tem imagem? ' . ($request->hasFile('imagem') ? 'SIM' : 'NÃO'));
    \Log::info('Conteúdo: ' . $request->input('conteudo'));
    
    try {
        if (!$grupo->membros->contains(auth()->id())) {
            \Log::error('Usuário não é membro do grupo');
            abort(403);
        }
        \Log::info('Validação de membro: OK');

        $validated = $request->validate([
            'conteudo' => 'nullable|string|max:5000',
            'imagem' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
        ]);
        \Log::info('Validação de campos: OK');

        if (empty($validated['conteudo']) && !$request->hasFile('imagem')) {
            \Log::error('Sem conteúdo e sem imagem');
            return response()->json([
                'success' => false,
                'message' => 'Envie uma mensagem ou imagem.'
            ], 422);
        }
        \Log::info('Validação de conteúdo: OK');

        $tipo = 'texto';
        $arquivoUrl = null;
        $arquivoNome = null;

        if ($request->hasFile('imagem')) {
            \Log::info('Processando upload de imagem...');
            
            $imagem = $request->file('imagem');
            \Log::info('Arquivo recebido: ' . $imagem->getClientOriginalName());
            
            // Verificar se a imagem é válida
            if (!$imagem->isValid()) {
                \Log::error('Imagem inválida');
                throw new \Exception('Arquivo de imagem inválido');
            }
            \Log::info('Validação de imagem: OK');
            
            $nomeOriginal = $imagem->getClientOriginalName();
            $nomeArquivo = time() . '_' . uniqid() . '.' . $imagem->getClientOriginalExtension();
            \Log::info('Nome do arquivo: ' . $nomeArquivo);
            
            // Criar pasta se não existir
            if (!Storage::disk('public')->exists('mensagens')) {
                \Log::info('Criando pasta mensagens...');
                Storage::disk('public')->makeDirectory('mensagens');
            }
            \Log::info('Pasta mensagens: OK');
            
            \Log::info('Salvando arquivo...');
            $path = $imagem->storeAs('mensagens', $nomeArquivo, 'public');
            \Log::info('Path salvo: ' . $path);
            
            if (!$path) {
                \Log::error('Erro ao salvar arquivo - path vazio');
                throw new \Exception('Erro ao salvar arquivo');
            }
            
            $tipo = 'imagem';
            $arquivoUrl = $path;
            $arquivoNome = $nomeOriginal;
            \Log::info('Upload concluído: ' . $path);
        }

        \Log::info('Criando mensagem no banco...');
        $mensagem = Mensagem::create([
            'grupo_id' => $grupo->id,
            'user_id' => auth()->id(),
            'conteudo' => $validated['conteudo'] ? trim($validated['conteudo']) : null,
            'tipo' => $tipo,
            'arquivo_url' => $arquivoUrl,
            'arquivo_nome' => $arquivoNome,
        ]);
        \Log::info('Mensagem criada com ID: ' . $mensagem->id);

        \Log::info('Criando leitura...');
        MensagemLeitura::create([
            'mensagem_id' => $mensagem->id,
            'user_id' => auth()->id(),
            'lida_em' => now(),
        ]);
        \Log::info('Leitura criada: OK');

        \Log::info('Limpando cache...');
        foreach ($grupo->membros as $membro) {
            if ($membro->id != auth()->id()) {
                Cache::forget("grupo_{$grupo->id}_nao_lidas_{$membro->id}");
            }
        }
        \Log::info('Cache limpo: OK');

        if ($request->ajax()) {
            \Log::info('Retornando JSON de sucesso');
            return response()->json([
                'success' => true,
                'mensagem' => $mensagem->load('user:id,name'),
            ]);
        }

        \Log::info('Retornando back()');
        return back();
        
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Erro de validação: ' . json_encode($e->errors()));
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        }
        return back()->withErrors($e->errors());
        
    } catch (\Exception $e) {
        \Log::error('ERRO GERAL: ' . $e->getMessage());
        \Log::error('Linha: ' . $e->getLine());
        \Log::error('Arquivo: ' . $e->getFile());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao enviar mensagem: ' . $e->getMessage()
            ], 500);
        }
        return back()->withErrors(['mensagem' => 'Erro ao enviar mensagem: ' . $e->getMessage()]);
    }
}

    public function update(Request $request, Mensagem $mensagem)
    {
        if ($mensagem->user_id !== auth()->id()) {
            abort(403);
        }

        // Não permitir edição de mensagens com imagem
        if ($mensagem->tipo === 'imagem') {
            return back()->withErrors(['mensagem' => 'Mensagens com imagem não podem ser editadas.']);
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

        // Deletar arquivo se existir
        if ($mensagem->arquivo_url) {
            Storage::disk('public')->delete($mensagem->arquivo_url);
        }

        $mensagem->delete();

        Cache::forget("grupo_{$grupoId}_nao_lidas_" . auth()->id());

        return back()->with('success', 'Mensagem eliminada!');
    }

    private function marcarComoLidasOtimizado($grupoId, $userId)
    {
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

        if (!empty($dados)) {
            DB::table('mensagem_leituras')->insert($dados);
        }
    }

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
            ->limit(50)
            ->get();

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

                foreach ($dados as $dado) {
                    DB::table('mensagem_leituras')->insertOrIgnore($dado);
                }
            }

            Cache::forget("grupo_{$grupo->id}_nao_lidas_" . auth()->id());
        }

        return response()->json($novasMensagens);
    }
}