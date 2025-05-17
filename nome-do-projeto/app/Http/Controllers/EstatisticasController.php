<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fatura;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstatisticasController extends Controller
{
    public function index()
    {
        $userId = Auth::id(); // Pega o ID do usuário autenticado

        // Estatísticas gerais (apenas do usuário)
        $totalFaturas = Fatura::where('user_id', $userId)->count();
        $valorTotal = Fatura::where('user_id', $userId)->sum('valor');
        $mediaValor = Fatura::where('user_id', $userId)->avg('valor');

        // Estatísticas mensais (apenas do usuário)
        $estatisticasMensais = Fatura::select(
            DB::raw('DATE_FORMAT(data, "%Y-%m") as mes'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(valor) as total_valor')
        )
        ->where('user_id', $userId)
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        // Estatísticas por fornecedor (apenas do usuário)
        $estatisticasFornecedor = Fatura::select(
            'fornecedor',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(valor) as total_valor')
        )
        ->where('user_id', $userId)
        ->groupBy('fornecedor')
        ->orderBy('total_valor', 'desc')
        ->get();

        return view('estatisticas.index', compact(
            'totalFaturas', 
            'valorTotal', 
            'mediaValor', 
            'estatisticasMensais', 
            'estatisticasFornecedor'
        ));
    }
}
