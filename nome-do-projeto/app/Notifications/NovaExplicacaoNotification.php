<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Explicacao;
use Illuminate\Support\Facades\Log;

class NovaExplicacaoNotification extends Notification
{
    use Queueable;

    protected $explicacao;
    protected $emailPara;
    protected $acao;

    /**
     * Create a new notification instance.
     */
    public function __construct(Explicacao $explicacao, $emailPara = null, $acao = 'criada')
    {
        $this->explicacao = $explicacao;
        $this->emailPara = $emailPara;
        $this->acao = $acao;
        
        Log::info('NovaExplicacaoNotification criada', [
            'explicacao_id' => $explicacao->id,
            'email_para' => $emailPara,
            'acao' => $acao,
            'disciplina' => $explicacao->disciplina,
            'professor' => $explicacao->user->name ?? 'N/A'
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

        Log::info('NovaExplicacaoNotification canais', [
            'channels' => $channels,
            'email_para' => $this->emailPara
        ]);

        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = match($this->acao) {
            'criada' => 'Nova Explicação Registrada',
            'aprovada' => 'Explicação Aprovada',
            'rejeitada' => 'Explicação Rejeitada',
            'atualizada' => 'Explicação Atualizada',
            default => 'Notificação de Explicação'
        };

        $greeting = match($this->acao) {
            'criada' => 'Uma nova explicação foi registrada no sistema e está pendente de aprovação.',
            'aprovada' => 'A explicação foi aprovada pelo administrador.',
            'rejeitada' => 'A explicação foi rejeitada pelo administrador.',
            'atualizada' => 'Uma explicação foi atualizada no sistema.',
            default => 'Houve uma atualização numa explicação.'
        };

        $mailMessage = (new MailMessage)
            ->subject($subject)
            ->greeting('Olá!')
            ->line($greeting)
            ->line('**Detalhes da Explicação:**')
            ->line('• Disciplina: ' . $this->explicacao->disciplina)
            ->line('• Professor: ' . ($this->explicacao->user->name ?? 'N/A'))
            ->line('• Aluno: ' . $this->explicacao->nome_aluno)
            ->line('• Data: ' . \Carbon\Carbon::parse($this->explicacao->data_explicacao)->format('d/m/Y'))
            ->line('• Horário: ' . substr($this->explicacao->hora_inicio, 0, 5) . ' às ' . substr($this->explicacao->hora_fim, 0, 5))
            ->line('• Local: ' . $this->explicacao->local)
            ->line('• Preço: €' . number_format($this->explicacao->preco, 2, ',', '.'))
            ->line('• Status: ' . ucfirst($this->explicacao->status))
            ->line('• Aprovação: ' . ucfirst($this->explicacao->aprovacao_admin));

        // Adicionar informações específicas baseadas na ação
        if ($this->acao === 'rejeitada' && $this->explicacao->motivo_rejeicao) {
            $mailMessage->line('• Motivo da Rejeição: ' . $this->explicacao->motivo_rejeicao);
        }

        if ($this->explicacao->observacoes) {
            $mailMessage->line('• Observações: ' . $this->explicacao->observacoes);
        }

        $mailMessage->action('Ver Explicação', route('explicacoes.show', $this->explicacao->id))
            ->line('Obrigado por usar o sistema de Explicações!');

        return $mailMessage;
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'explicacao_id' => $this->explicacao->id,
            'disciplina' => $this->explicacao->disciplina,
            'professor' => $this->explicacao->user->name ?? 'N/A',
            'aluno' => $this->explicacao->nome_aluno,
            'data_explicacao' => $this->explicacao->data_explicacao,
            'preco' => $this->explicacao->preco,
            'status' => $this->explicacao->status,
            'aprovacao_admin' => $this->explicacao->aprovacao_admin,
            'acao' => $this->acao
        ];
    }
}