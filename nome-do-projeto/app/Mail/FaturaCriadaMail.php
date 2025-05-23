<?php

namespace App\Mail;

use App\Models\Fatura;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class FaturaCriadaMail extends Mailable
{
    use Queueable, SerializesModels;

    public $fatura;

    public function __construct(Fatura $fatura)
    {
        $this->fatura = $fatura;
    }

    public function build()
    {
        return $this->subject('Nova Fatura Criada')
                    ->markdown('emails.faturas.criada');
    }
}