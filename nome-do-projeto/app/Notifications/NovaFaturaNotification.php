<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Fatura;
use Illuminate\Support\Facades\Log;

class NovaFaturaNotification extends Notification
{
    use Queueable;

    protected $fatura;
    protected $emailPara;
    protected $telefonePara;
    protected $acao;

    /**
     * Create a new notification instance.
     */
    public function __construct(Fatura $fatura, $emailPara = null, $telefonePara = null, $acao = 'criada')
    {
        $this->fatura = $fatura;
        $this->emailPara = $emailPara;
        $this->telefonePara = $telefonePara;
        $this->acao = $acao;
        
        Log::info('NovaFaturaNotification criada', [
            'fatura_id' => $fatura->id,
            'email_para' => $emailPara,
            'telefone_para' => $telefonePara,
            'acao' => $acao
        ]);
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        $channels = [];

        if ($this->emailPara) {
            $channels[] = 'mail';
        }

        if ($this->telefonePara) {
            $channels[] = 'sms';
        }

        Log::info('NovaFaturaNotification canais', [
            'channels' => $channels,
            'email_para' => $this->emailPara,
            'telefone_para' => $this->telefonePara
        ]);

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = $this->acao === 'criada' ? 'Nova Fatura Registrada' : 'Fatura Atualizada';
        $greeting = $this->acao === 'criada' ? 'Uma nova fatura foi registrada no sistema.' : 'Uma fatura foi atualizada no sistema.';

        return (new MailMessage)
                    ->to($this->emailPara)
                    ->subject($subject)
                    ->greeting('Olá!')
                    ->line($greeting)
                    ->line('Fornecedor: ' . $this->fatura->fornecedor)
                    ->line('Data: ' . $this->fatura->data)
                    ->line('Valor: R$ ' . number_format($this->fatura->valor, 2, ',', '.'))
                    ->action('Ver Fatura', route('faturas.show', $this->fatura->id))
                    ->line('Obrigado por usar nosso sistema!');
    }

    /**
     * Get the SMS representation of the notification.
     */
    public function toSms($notifiable)
    {
        $texto = $this->acao === 'criada' ? 'Nova fatura registrada!' : 'Fatura atualizada!';
        
        $message = "{$texto}\nFornecedor: {$this->fatura->fornecedor}\nValor: R$ "
            . number_format($this->fatura->valor, 2, ',', '.')
            . "\nData: {$this->fatura->data}";

        $smsData = [
            'to' => $this->telefonePara,
            'message' => $message
        ];

        Log::info('NovaFaturaNotification toSms', $smsData);

        return $smsData;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'fatura_id' => $this->fatura->id,
            'fornecedor' => $this->fatura->fornecedor,
            'valor' => $this->fatura->valor,
            'data' => $this->fatura->data,
        ];
    }
}