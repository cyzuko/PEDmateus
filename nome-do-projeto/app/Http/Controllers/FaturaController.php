<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

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
        // Validação para os dados do formulário
        $validated = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|string', // Aceita base64 para imagem
        ]);
    
        try {
            $fatura = new Fatura();
            $fatura->user_id = Auth::id();
            $fatura->fornecedor = $validated['fornecedor'];
            $fatura->data = $validated['data'];
            $fatura->valor = $validated['valor'];
    
            // Verificar se foi enviada uma imagem no formato base64
            if ($request->has('imagem') && !empty($validated['imagem'])) {
                $imageData = $validated['imagem'];

                // Remove o prefixo "data:image/png;base64,"
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = base64_decode($imageData);

                // Gerar um nome único para a imagem
                $imageName = 'fatura_' . time() . '.png';

                // Salvar a imagem no diretório 'faturas' dentro de 'storage/app/public'
                Storage::disk('public')->put('faturas/' . $imageName, $imageData);

                // Armazenar o caminho da imagem no banco de dados
                $fatura->imagem = 'faturas/' . $imageName;
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
        if (!is_numeric($id) || $id <= 0) {
            return redirect()->route('faturas.index')
                ->with('error', 'ID de fatura inválido.');
        }

        $fatura = Fatura::find($id);

        if (!$fatura) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada.');
        }

        if ($fatura->user_id != Auth::id()) {
            \Log::warning('Tentativa de acesso não autorizado à fatura #' . $id . ' pelo usuário #' . Auth::id());
            
            return redirect()->route('faturas.index')
                ->with('error', 'Você não tem permissão para visualizar esta fatura.');
        }

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
        // Validação para os dados do formulário
        $validated = $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric|min:0',
            'imagem' => 'nullable|string', // Aceita base64 para imagem
        ]);

        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);

            $fatura->fornecedor = $validated['fornecedor'];
            $fatura->data = $validated['data'];
            $fatura->valor = $validated['valor'];

            // Verificar se a imagem foi enviada em base64
            if ($request->has('imagem') && !empty($validated['imagem'])) {
                // Remover imagem antiga, se houver
                if ($fatura->imagem && Storage::disk('public')->exists($fatura->imagem)) {
                    Storage::disk('public')->delete($fatura->imagem);
                }

                $imageData = $validated['imagem'];

                // Remove o prefixo "data:image/png;base64,"
                $imageData = str_replace('data:image/png;base64,', '', $imageData);
                $imageData = base64_decode($imageData);

                // Gerar um nome único para a nova imagem
                $imageName = 'fatura_' . time() . '.png';

                // Salvar a nova imagem no diretório 'faturas' dentro de 'storage/app/public'
                Storage::disk('public')->put('faturas/' . $imageName, $imageData);

                // Atualizar o caminho da imagem no banco de dados
                $fatura->imagem = 'faturas/' . $imageName;
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
            
            // Remover imagem se existir
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
