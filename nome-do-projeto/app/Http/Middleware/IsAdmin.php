<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->tipo_utilizador === 'admin') {
            return $next($request);
        }

        return redirect()->route('home')->with('error', 'Acesso negado. Apenas administradores podem aceder a esta área.');
    }
}