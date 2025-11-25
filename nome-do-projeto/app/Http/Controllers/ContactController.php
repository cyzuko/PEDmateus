<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    public function send(Request $request)
    {
        try {
            $validated = $request->validate([
                'nome' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'mensagem' => 'required|string|max:5000',
            ], [
                'nome.required' => 'O nome é obrigatório',
                'email.required' => 'O email é obrigatório',
                'email.email' => 'Email inválido',
                'mensagem.required' => 'A mensagem é obrigatória',
            ]);

            // Enviar email
            $destinatario = env('ADMIN_EMAIL', 'mateus23viana@gmail.com');
            
            Mail::send('emails.contact', $validated, function($message) use ($validated, $destinatario) {
                $message->to($destinatario)
                        ->subject('Nova Mensagem de Contacto - ' . $validated['nome'])
                        ->replyTo($validated['email'], $validated['nome']);
            });

            Log::info('Email de contacto enviado', [
                'nome' => $validated['nome'],
                'email' => $validated['email']
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    
                ]);
            }

            return back()->with('success', 'Mensagem enviada com sucesso! Entraremos em contacto em breve.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro de validação',
                    'errors' => $e->errors()
                ], 422);
            }
            return back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            Log::error('Erro ao enviar email de contacto: ' . $e->getMessage());
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erro ao enviar mensagem. Tente novamente.'
                ], 500);
            }
            return back()->withErrors(['error' => 'Erro ao enviar mensagem. Tente novamente.'])->withInput();
        }
    }
}