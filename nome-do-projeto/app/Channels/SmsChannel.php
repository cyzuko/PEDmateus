<?php

namespace App\Channels;

use Illuminate\Notifications\Notification;
use App\Services\SmsService;
use Illuminate\Support\Facades\Log;

class SmsChannel
{
    protected $sms;

    public function __construct(SmsService $sms)
    {
        $this->sms = $sms;
        Log::info('SmsChannel: Construtor inicializado');
    }

    public function send($notifiable, Notification $notification)
    {
        Log::info('SmsChannel: Iniciando envio', [
            'notifiable' => get_class($notifiable),
            'notification' => get_class($notification),
            'notifiable_id' => method_exists($notifiable, 'getKey') ? $notifiable->getKey() : 'N/A'
        ]);

        if (!method_exists($notification, 'toSms')) {
            Log::error('SmsChannel: Método toSms não encontrado', [
                'notification' => get_class($notification),
                'available_methods' => get_class_methods($notification)
            ]);
            return false;
        }

        try {
            $messageData = $notification->toSms($notifiable);

            Log::info('SmsChannel: Dados da mensagem obtidos', [
                'messageData' => $messageData,
                'to_exists' => isset($messageData['to']),
                'message_exists' => isset($messageData['message']),
                'to_value' => $messageData['to'] ?? 'N/A',
                'message_length' => isset($messageData['message']) ? strlen($messageData['message']) : 0
            ]);

            if (empty($messageData['to']) || empty($messageData['message'])) {
                Log::error('SmsChannel: Dados SMS incompletos', [
                    'to' => $messageData['to'] ?? 'vazio',
                    'message' => !empty($messageData['message']) ? 'presente' : 'vazio',
                    'message_length' => strlen($messageData['message'] ?? ''),
                    'full_data' => $messageData
                ]);
                return false;
            }

            // Verificar se o SmsService está disponível
            if (!$this->sms) {
                Log::error('SmsChannel: SmsService não disponível');
                return false;
            }

            $result = $this->sms->sendSms($messageData['to'], $messageData['message']);

            Log::info('SmsChannel: Resultado do envio', [
                'result' => $result,
                'success' => isset($result['success']) ? $result['success'] : false
            ]);

            return $result;

        } catch (\Exception $e) {
            Log::error('SmsChannel: Erro no envio', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }
}