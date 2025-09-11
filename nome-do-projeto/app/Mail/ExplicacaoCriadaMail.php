<?php

namespace App\Mail;

use App\Models\Explicacao;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ExplicacaoCriadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $explicacao;
    public $acao;

    public function __construct(Explicacao $explicacao, $acao = 'criada')
    {
        $this->explicacao = $explicacao;
        $this->acao = $acao;
    }

    public function build()
    {
        $subject = match($this->acao) {
            'criada' => 'Nova Explicação Registrada',
            'aprovada' => 'Explicação Aprovada',
            'rejeitada' => 'Explicação Rejeitada',
            'atualizada' => 'Explicação Atualizada',
            default => 'Notificação de Explicação'
        };

        return $this->subject($subject)
                    ->markdown('emails.explicacoes.criada');
    }
}