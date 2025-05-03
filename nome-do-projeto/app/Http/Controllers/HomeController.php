<?php
// app/Http/Controllers/HomeController.php

namespace App\Http\Controllers;

use App\Models\Fatura;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        try {
            // Tentativa de obter as faturas
            $faturas = Fatura::where('user_id', Auth::id())
                        ->orderBy('data', 'desc')
                        ->take(5)
                        ->get();
        } catch (\Exception $e) {
            // Em caso de erro, inicializa com uma coleção vazia
            $faturas = collect([]);
        }
                    
        return view('home', compact('faturas'));
    }
}