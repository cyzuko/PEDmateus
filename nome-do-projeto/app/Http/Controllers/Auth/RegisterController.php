<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Events\Registered;
use App\Models\User;

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
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
            ]);
            
            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }
            
            // Criar o usuário
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
            
            // Registrar no log
            Log::info('Usuário registrado: ' . $user->email);
            
            // Disparar evento de registro
            event(new Registered($user));
            
            // Autenticar o usuário
            auth()->login($user);
            
            // Redirecionar para o dashboard
            return redirect()->route('dashboard')
                ->with('success', 'Registro realizado com sucesso!');
                
        } catch (\Exception $e) {
            // Log do erro
            Log::error('Erro no registro: ' . $e->getMessage());
            
            // Retornar mensagem de erro para o usuário
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao registrar: ' . $e->getMessage()]);
        }
    }
}
