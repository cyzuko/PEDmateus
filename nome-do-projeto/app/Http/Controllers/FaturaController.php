<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class FaturaController extends Controller
{
    /**
     * Mostrar lista de todas as faturas
     */
    public function index()
    {
        $faturas = DB::table('faturas')
            ->where('user_id', Auth::id())
            ->orderBy('data', 'desc')
            ->paginate(10);
        
        return view('faturas.index', compact('faturas'));
    }

    /**
     * Mostrar formulário de criação de fatura
     */
    public function create()
    {
        return view('faturas.create');
    }

    /**
     * Armazenar uma nova fatura
     */
    public function store(Request $request)
    {
        $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|max:2048',
        ]);

        // Processar upload de imagem, se houver
        $imagemPath = null;
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            $imagem = $request->file('imagem');
            $imagemNome = time() . '.' . $imagem->getClientOriginalExtension();
            $imagemPath = $imagem->storeAs('faturas', $imagemNome, 'public');
        }

        // Inserir a fatura no banco de dados
        DB::table('faturas')->insert([
            'user_id' => Auth::id(),
            'fornecedor' => $request->fornecedor,
            'data' => $request->data,
            'valor' => $request->valor,
            'imagem' => $imagemPath,
            'criado_em' => now(),
            'atualizado_em' => now(),
        ]);

        return redirect()->route('faturas.index')
            ->with('success', 'Fatura criada com sucesso!');
    }

    /**
     * Mostrar uma fatura específica
     */
    public function show($id)
    {
        $fatura = DB::table('faturas')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada!');
        }
        
        return view('faturas.show', compact('fatura'));
    }

    /**
     * Mostrar formulário de edição
     */
    public function edit($id)
    {
        $fatura = DB::table('faturas')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
        
        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada!');
        }
        
        return view('faturas.edit', compact('fatura'));
    }

    /**
     * Atualizar uma fatura
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|max:2048',
        ]);

        // Verificar se a fatura pertence ao usuário atual
        $fatura = DB::table('faturas')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada!');
        }

        // Dados para atualização
        $dados = [
            'fornecedor' => $request->fornecedor,
            'data' => $request->data,
            'valor' => $request->valor,
            'atualizado_em' => now(),
        ];

        // Processar upload de imagem, se houver
        if ($request->hasFile('imagem') && $request->file('imagem')->isValid()) {
            // Remover imagem antiga se existir
            if ($fatura->imagem && Storage::disk('public')->exists($fatura->imagem)) {
                Storage::disk('public')->delete($fatura->imagem);
            }
            
            $imagem = $request->file('imagem');
            $imagemNome = time() . '.' . $imagem->getClientOriginalExtension();
            $dados['imagem'] = $imagem->storeAs('faturas', $imagemNome, 'public');
        }

        // Atualizar a fatura
        DB::table('faturas')
            ->where('id', $id)
            ->update($dados);

        return redirect()->route('faturas.show', $id)
            ->with('success', 'Fatura atualizada com sucesso!');
    }

    /**
     * Remover uma fatura
     */
    public function destroy($id)
    {
        // Verificar se a fatura existe e pertence ao usuário atual
        $fatura = DB::table('faturas')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();
            
        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada!');
        }

        // Remover imagem se existir
        if ($fatura->imagem && Storage::disk('public')->exists($fatura->imagem)) {
            Storage::disk('public')->delete($fatura->imagem);
        }

        // Excluir a fatura
        DB::table('faturas')->where('id', $id)->delete();

        return redirect()->route('faturas.index')
            ->with('success', 'Fatura excluída com sucesso!');
    }
}