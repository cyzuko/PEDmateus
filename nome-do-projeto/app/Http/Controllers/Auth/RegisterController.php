<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    // Mostrar o formulário de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Processar o registro do usuário
    public function register(Request $request)
    {
        try {
            // Validar os dados recebidos
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Criar o usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // Logar o usuário imediatamente após o registro
            auth()->login($user);

            // Redirecionar para o dashboard
            return redirect()->route('dashboard')->with('success', 'Usuário registrado com sucesso!');
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro ao registrar usuário: ' . $e->getMessage());

            // Retornar erro
            return back()->withErrors(['error' => 'Erro ao registrar usuário: ' . $e->getMessage()]);
        }
    }
}
