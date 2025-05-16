<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fatura;
use Illuminate\Support\Facades\DB;

class EstatisticasController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $totalFaturas = Fatura::count();
        $valorTotal = Fatura::sum('valor');
        $mediaValor = Fatura::avg('valor');

        // Estatísticas mensais agrupadas por data (ano-mês)
        $estatisticasMensais = Fatura::select(
            DB::raw('DATE_FORMAT(data, "%Y-%m") as mes'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(valor) as total_valor')
        )
        ->groupBy('mes')
        ->orderBy('mes')
        ->get();

        // Estatísticas por fornecedor
        $estatisticasFornecedor = Fatura::select(
            'fornecedor',
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(valor) as total_valor')
        )
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
