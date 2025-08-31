<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verificar se o usuário está autenticado
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Precisa de fazer login para aceder a esta área.');
        }

        // Verificar se o usuário tem role de admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Acesso negado. Apenas administradores podem aceder a esta área.');
        }

        return $next($request);
    }
}