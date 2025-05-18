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
        $user = Auth::user(); // Pega o usuário autenticado

        // Começa a query base
        $query = Fatura::query();

        // Se não for admin, filtra pela fatura do usuário
        if ($user->role !== 'admin') {
            $query->where('user_id', $user->id);
        }

        // Estatísticas gerais
        $totalFaturas = (clone $query)->count();
        $valorTotal = (clone $query)->sum('valor');
        $mediaValor = (clone $query)->avg('valor');

        // Estatísticas mensais
        $estatisticasMensais = (clone $query)
            ->select(
                DB::raw('DATE_FORMAT(data, "%Y-%m") as mes'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(valor) as total_valor')
            )
            ->groupBy('mes')
            ->orderBy('mes')
            ->get();

        // Estatísticas por fornecedor
        $estatisticasFornecedor = (clone $query)
            ->select(
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
