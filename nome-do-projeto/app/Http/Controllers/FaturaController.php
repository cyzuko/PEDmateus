<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Fatura;

class FaturaController extends Controller
{
    public function index()
    {
        $faturas = Fatura::where('user_id', Auth::id())->get();
        return view('faturas.index', compact('faturas'));
    }

    public function create()
    {
        return view('faturas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'fornecedor' => 'required|string|max:255',
            'data' => 'required|date',
            'valor' => 'required|numeric',
            'imagem' => 'nullable|image|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('imagem')) {
            $path = $request->file('imagem')->store('faturas', 'public');
        }

        Fatura::create([
            'user_id' => Auth::id(),
            'fornecedor' => $request->fornecedor,
            'data' => $request->data,
            'valor' => $request->valor,
            'imagem' => $path,
        ]);

        return redirect()->route('faturas.index')->with('success', 'Fatura adicionada com sucesso.');
    }

    public function dashboard()
    {
        $userId = Auth::id();
        $totalFaturas = Fatura::where('user_id', $userId)->count();
        $valorTotal = Fatura::where('user_id', $userId)->sum('valor');

        return view('dashboard', compact('totalFaturas', 'valorTotal'));
    }
}
