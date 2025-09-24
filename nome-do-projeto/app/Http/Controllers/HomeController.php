<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Página inicial pública (sem autenticação necessária)
     * Esta é a landing page que todos podem ver
     */
    public function publicHome()
    {
        return view('public.home');
    }

    /**
     * Dashboard autenticado (requer login)
     * Esta é a área privada após login
     */
    public function index()
    {
        // Aqui você pode adicionar dados específicos para usuários autenticados
        $user = auth()->user();
        
        // Exemplo de dados que podem ser úteis no dashboard
        $stats = [
            'total_explicacoes' => 0, // Buscar do banco de dados
            'explicacoes_pendentes' => 0,
            'explicacoes_concluidas' => 0,
        ];

        return view('home', compact('stats'));
    }
}