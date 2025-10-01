<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Explicacao;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstatisticasController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Query base
        $query = Explicacao::query();

        // Se não for admin, filtra pelas explicações do usuário
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Estatísticas gerais
        $totalExplicacoes = (clone $query)->count();
        $valorTotal = (clone $query)->sum('preco');
        $mediaValor = (clone $query)->avg('preco');

        // Estatísticas mensais
        $estatisticasMensais = (clone $query)
            ->select(
                DB::raw('DATE_FORMAT(data_explicacao, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(preco) as total_valor')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Estatísticas por disciplina (equivalente a fornecedor)
        $estatisticasDisciplina = (clone $query)
            ->select(
                'disciplina',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(preco) as total_valor')
            )
            ->groupBy('disciplina')
            ->orderBy('total_valor', 'desc')
            ->get();

        return view('estatisticas.index', compact(
            'totalExplicacoes',
            'valorTotal',
            'mediaValor',
            'estatisticasMensais',
            'estatisticasDisciplina'
        ));
    }
}