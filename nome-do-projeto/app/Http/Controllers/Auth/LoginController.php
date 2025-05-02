<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class LoginController extends Controller
{
    // Mostrar o formulário de login
    public function showLoginForm()
    {
        return view('auth.login');
    }
    
    // Processar login
    public function login(Request $request)
    {
        try {
            // Validação dos dados do formulário
            $request->validate([
                'email' => 'required|email',
                'password' => 'required|min:6',
            ]);
            
            // Verificar as credenciais
            $credentials = $request->only('email', 'password');
            
            if (Auth::attempt($credentials, $request->filled('remember'))) {
                // Autenticado com sucesso
                return redirect()->intended('/dashboard');
            }
            
            // Se falhar a autenticação
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['email' => 'As credenciais fornecidas não são válidas.']);
                
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro no login: ' . $e->getMessage());
            
            // Retornar mensagem de erro para o usuário
            return back()
                ->withInput($request->only('email', 'remember'))
                ->withErrors(['error' => 'Ocorreu um erro ao processar o login: ' . $e->getMessage()]);
        }
    }
    
    // Logout do usuário
    public function logout()
    {
        try {
            Auth::logout();
            
            // Limpar a sessão
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('login')->with('success', 'Logout realizado com sucesso!');
            
        } catch (\Exception $e) {
            Log::error('Erro no logout: ' . $e->getMessage());
            return redirect()->route('login');
        }
    }
}
