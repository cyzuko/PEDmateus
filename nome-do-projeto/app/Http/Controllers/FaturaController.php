<?php

namespace App\Http\Controllers;

use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FaturaController extends Controller
{
    // Remova o construtor com middleware, isso é feito nas rotas
    // public function __construct()
    // {
    //     $this->middleware('auth');
    // }

    public function index()
    {
        try {
            $faturas = Fatura::where('user_id', Auth::id())
                    ->orderBy('data', 'desc')
                    ->paginate(10);
        } catch (\Exception $e) {
            // Se ocorrer um erro, retornar uma coleção vazia
            $faturas = collect([]);
            
            // Adicionar uma mensagem de erro
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
            'imagem' => 'nullable|image|max:2048', // max 2MB
        ]);

        try {
            // Verificar se a tabela tem a coluna user_id
            if (!Schema::hasColumn('faturas', 'user_id')) {
                // Se não tem, tentar adicionar
                DB::statement('ALTER TABLE faturas ADD COLUMN user_id BIGINT UNSIGNED NOT NULL AFTER id');
                DB::statement('ALTER TABLE faturas ADD CONSTRAINT faturas_user_id_foreign FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE');
            }
            
            // Inserir diretamente usando DB Query Builder para maior controle
            $data = [
                'user_id' => Auth::id(),
                'fornecedor' => $validated['fornecedor'],
                'data' => $validated['data'],
                'valor' => $validated['valor'],
            ];
            
            if ($request->hasFile('imagem')) {
                $path = $request->file('imagem')->store('faturas', 'public');
                $data['imagem'] = $path;
            }
            
            // Adicionar timestamps manualmente
            $now = now();
            $data['criado_em'] = $now;
            $data['atualizado_em'] = $now;
            
            // Inserir usando Query Builder ao invés do Eloquent
            DB::table('faturas')->insert($data);
            
            return redirect()->route('faturas.index')
                ->with('success', 'Fatura registrada com sucesso!');
            
        } catch (\Exception $e) {
            // Capturar e lidar com exceções
            return back()->withInput()
                ->with('error', 'Erro ao salvar a fatura: ' . $e->getMessage() .
                 ' Por favor, verifique a estrutura da tabela no banco de dados.');
        }
    }

    public function show($id)
    {
        try {
            $fatura = Fatura::where('user_id', Auth::id())->findOrFail($id);
            return view('faturas.show', compact('fatura'));
        } catch (\Exception $e) {
            return redirect()->route('faturas.index')
                ->with('error', 'Fatura não encontrada ou você não tem permissão para visualizá-la.');
        }
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
                
                $path = $request->file('imagem')->store('faturas', 'public');
                $fatura->imagem = $path;
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