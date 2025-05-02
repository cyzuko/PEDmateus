<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    /**
     * Mostrar dashboard com informações resumidas
     */
    public function index()
    {
        $user = Auth::user();
        
        // Buscar as faturas mais recentes do usuário (últimas 5)
        $faturas = DB::table('faturas')
            ->where('user_id', $user->id)
            ->orderBy('data', 'desc')
            ->limit(5)
            ->get();
        
        // Calcular o total de faturas do usuário
        $totalFaturas = DB::table('faturas')
            ->where('user_id', $user->id)
            ->count();
        
        // Calcular o valor total das faturas do usuário
        $valorTotal = DB::table('faturas')
            ->where('user_id', $user->id)
            ->sum('valor');
        
        return view('dashboard', compact('faturas', 'totalFaturas', 'valorTotal'));
    }
}