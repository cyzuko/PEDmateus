<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log; // Adicionado para logging

class FaturaController extends Controller
{
    public function index()
    {
        try {
            $faturas = Fatura::where('user_id', Auth::id())
                    ->orderBy('data', 'desc')
                    ->paginate(10);
        } catch (\Exception $e) {
            $faturas = collect([]); // Se ocorrer erro, retorna uma coleção vazia
            
            return view('faturas.index', compact('faturas'))
                ->with('error', 'Erro ao carregar faturas: Estrutura da tabela pode precisar de atualização.');
        }
        
        return view('faturas.index', compact('faturas'));
    }

    public function create()
    {
        return view('faturas.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|max:2048',
        ]);
    
        try {
            $fatura = new Fatura();
            $fatura->user_id = Auth::id();
            $fatura->fornecedor = $validated['fornecedor'];
            $fatura->data = $validated['data'];
            $fatura->valor = $validated['valor'];
    
            if ($request->hasFile('imagem')) {
                $file = $request->file('imagem');
                $path = $file->store('faturas', 'public');
                $fatura->imagem = $path;
            }
    
            $fatura->save();
    
            return redirect()->route('faturas.index')->with('success', 'Fatura registrada com sucesso!');
        } catch (\Exception $e) {
            Log::error('Erro ao salvar fatura', [
                'mensagem' => $e->getMessage(),
                'arquivo' => $e->getFile(),
                'linha' => $e->getLine()
            ]);
    
            return back()->withInput()->with('error', 'Erro ao salvar a fatura: ' . $e->getMessage());
        }
    }
   public function show($id)
    {
        // Verificar se o ID é válido
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('faturas.index')
                ->with('error', 'ID de fatura inválido.');
        }
        
        // Verificar primeiro se a fatura existe
        $fatura = Fatura::find($id);
        
        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada.');
        }
        
        // Verificar se o usuário tem permissão (é o dono da fatura)
        if ($fatura->user_id != Auth::id()) {
            // Opcional: Registrar tentativa de acesso não autorizado
            \Log::warning('Tentativa de acesso não autorizado à fatura #' . $id . ' pelo usuário #' . Auth::id());
            
            return redirect()->route('faturas.index')
                ->with('error', 'Você não tem permissão para visualizar esta fatura.');
        }
        
        // Se chegou até aqui, tudo está ok - mostrar a fatura
        return view('faturas.show', compact('fatura'));
    }



    public function edit($id)
    {
        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);
            return view('faturas.edit', compact('fatura'));
        } catch (\Exception $e) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada ou você não tem permissão para editá-la.');
        }
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|image|max:2048', // max 2MB
        ]);

        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);

            $fatura->fornecedor = $validated['fornecedor'];
            $fatura->data = $validated['data'];
            $fatura->valor = $validated['valor'];

            if ($request->hasFile('imagem')) {
                // Remover imagem antiga se existir
                if ($fatura->imagem && Storage::disk('public')->exists($fatura->imagem)) {
                    Storage::disk('public')->delete($fatura->imagem);
                }
                
                $file = $request->file('imagem');
                
                if ($file->isValid()) {
                    $path = $file->store('faturas', 'public');
                    $fatura->imagem = $path;
                }
            }

            $fatura->save();

            return redirect()->route('faturas.index')
                ->with('success', 'Fatura atualizada com sucesso!');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Erro ao atualizar a fatura: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);
            
            // Remover arquivo de imagem se existir
            if ($fatura->imagem && Storage::disk('public')->exists($fatura->imagem)) {
                Storage::disk('public')->delete($fatura->imagem);
            }
            
            $fatura->delete();
            
            return redirect()->route('faturas.index')
                ->with('success', 'Fatura removida com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('faturas.index')
                ->with('error', 'Erro ao remover a fatura: ' . $e->getMessage());
        }
    }
}
