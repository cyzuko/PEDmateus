<?php

namespace App\Services;

use Twilio\Rest\Client;
use Illuminate\Support\Facades\Log;
use Exception;

class SmsService
{
    protected $twilio;
    protected $from;

    public function __construct()
    {
        $sid = config('services.twilio.sid');
        $token = config('services.twilio.token');
        $this->from = config('services.twilio.from');

        // Log das configurações (sem mostrar dados sensíveis)
        Log::info('Configurações Twilio', [
            'sid_exists' => !empty($sid),
            'token_exists' => !empty($token),
            'from_exists' => !empty($this->from),
            'from_number' => $this->from
        ]);

        if ($sid && $token) {
            try {
                $this->twilio = new Client($sid, $token);
                Log::info('Cliente Twilio criado com sucesso');
            } catch (Exception $e) {
                Log::error('Erro ao criar cliente Twilio: ' . $e->getMessage());
            }
        } else {
            Log::error('Credenciais Twilio não configuradas', [
                'sid' => $sid ? 'presente' : 'ausente',
                'token' => $token ? 'presente' : 'ausente'
            ]);
        }
    }

    /**
     * Envia SMS
     */
    public function sendSms(string $to, string $message): array
    {
        try {
            if (!$this->twilio) {
                throw new Exception('Serviço SMS não configurado correctamente - Cliente Twilio não inicializado');
            }

            if (empty($this->from)) {
                throw new Exception('Número de origem (FROM) não configurado');
            }

            // Formatar número
            $originalTo = $to;
            $to = $this->formatPhoneNumber($to);
            
            Log::info('Tentando enviar SMS', [
                'to_original' => $originalTo,
                'to_formatted' => $to,
                'from' => $this->from,
                'message_length' => strlen($message)
            ]);

            // Validar número
            if (!$this->isValidPhoneNumber($originalTo)) {
                throw new Exception('Número de telefone inválido: ' . $originalTo);
            }

            // Enviar SMS
            $twilioMessage = $this->twilio->messages->create($to, [
                'from' => $this->from,
                'body' => $message
            ]);

            Log::info('SMS enviado com sucesso', [
                'to' => $to,
                'sid' => $twilioMessage->sid,
                'status' => $twilioMessage->status,
                'error_code' => $twilioMessage->errorCode,
                'error_message' => $twilioMessage->errorMessage
            ]);

            return [
                'success' => true,
                'message' => 'SMS enviado com sucesso',
                'sid' => $twilioMessage->sid,
                'status' => $twilioMessage->status
            ];

        } catch (Exception $e) {
            Log::error('Erro ao enviar SMS', [
                'to_original' => $originalTo ?? $to,
                'to_formatted' => $to ?? 'não formatado',
                'from' => $this->from,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return [
                'success' => false,
                'message' => 'Erro ao enviar SMS: ' . $e->getMessage(),
                'error_details' => $e->getMessage()
            ];
        }
    }

    /**
     * Formatar número de telefone
     */
    private function formatPhoneNumber(string $phone): string
    {
        // Remove todos os caracteres exceto dígitos e +
        $phone = preg_replace('/[^\d+]/', '', $phone);
        
        Log::info('Formatando número', ['original' => $phone]);

        // Se já tem +, retorna como está
        if (str_starts_with($phone, '+')) {
            return $phone;
        }

        // Se começar com 00, substitui por +
        if (str_starts_with($phone, '00')) {
            return '+' . substr($phone, 2);
        }

        // Para números portugueses
        if (preg_match('/^9\d{8}$/', $phone)) {
            return '+351' . $phone;
        }

        // Se começar com 351
        if (str_starts_with($phone, '351')) {
            return '+' . $phone;
        }

        // Caso padrão - assumir português
        return '+351' . $phone;
    }

    /**
     * Validar número de telefone
     */
    public function isValidPhoneNumber(string $phone): bool
    {
        $formatted = $this->formatPhoneNumber($phone);
        $isValid = preg_match('/^\+\d{10,15}$/', $formatted);
        
        Log::info('Validação do número', [
            'original' => $phone,
            'formatted' => $formatted,
            'is_valid' => $isValid
        ]);
        
        return $isValid;
    }

    /**
     * Teste de conectividade com Twilio
     */
    public function testConnection(): array
    {
        try {
            if (!$this->twilio) {
                return [
                    'success' => false,
                    'message' => 'Cliente Twilio não inicializado'
                ];
            }

            // Tenta buscar informações da conta
            $account = $this->twilio->api->accounts(config('services.twilio.sid'))->fetch();
            
            return [
                'success' => true,
                'message' => 'Conexão com Twilio OK',
                'account_status' => $account->status
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Erro na conexão: ' . $e->getMessage()
            ];
        }
    }
}