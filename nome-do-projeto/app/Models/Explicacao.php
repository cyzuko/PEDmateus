<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Explicacao extends Model
{
    use HasFactory;

    protected $table = 'explicacoes';

    protected $fillable = [
        'user_id',
        'disciplina',
        'data_explicacao',
        'hora_inicio',
        'hora_fim',
        'local',
        'preco',
        'observacoes',
        'nome_aluno',
        'contacto_aluno',
        'status',
        'aprovacao_admin',
        'aprovada_por',
        'data_aprovacao',
        'motivo_rejeicao',
    ];

    protected $casts = [
        'preco' => 'decimal:2',
        'data_aprovacao' => 'datetime',
    ];

    /**
     * Relacionamento com o usuário (professor)
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relacionamento com o admin que aprovou
     */
    public function aprovadoPor()
    {
        return $this->belongsTo(User::class, 'aprovada_por');
    }

    /**
     * Scope para explicações futuras
     */
    public function scopeFuturas($query)
    {
        return $query->where('data_explicacao', '>=', Carbon::today());
    }

    /**
     * Scope para explicações passadas
     */
    public function scopePassadas($query)
    {
        return $query->where('data_explicacao', '<', Carbon::today());
    }

    /**
     * Scope por status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scopes para aprovação admin
     */
    public function scopeAprovadas($query)
    {
        return $query->where('aprovacao_admin', 'aprovada');
    }

    public function scopePendentes($query)
    {
        return $query->where('aprovacao_admin', 'pendente');
    }

    public function scopeRejeitadas($query)
    {
        return $query->where('aprovacao_admin', 'rejeitada');
    }

    /**
     * Accessor para formatação da data
     */
    public function getDataFormatadaAttribute()
    {
        try {
            return $this->data_explicacao ? Carbon::parse($this->data_explicacao)->format('d/m/Y') : '';
        } catch (\Exception $e) {
            return $this->attributes['data_explicacao'] ?? '';
        }
    }

    /**
     * Accessor para horário completo
     */
    public function getHorarioCompletoAttribute()
    {
        return $this->hora_inicio . ' - ' . $this->hora_fim;
    }

    /**
     * Accessor para duração em minutos
     */
    public function getDuracaoMinutosAttribute()
    {
        try {
            $inicio = Carbon::createFromFormat('H:i', $this->hora_inicio);
            $fim = Carbon::createFromFormat('H:i', $this->hora_fim);
            
            return $fim->diffInMinutes($inicio);
        } catch (\Exception $e) {
            return 0;
        }
    }

    /**
     * Accessor para status formatado
     */
    public function getStatusFormatadoAttribute()
    {
        $statusLabels = [
            'agendada' => 'Agendada',
            'confirmada' => 'Confirmada',
            'concluida' => 'Concluída',
            'cancelada' => 'Cancelada',
        ];

        return $statusLabels[$this->status] ?? $this->status;
    }

    /**
     * Accessor para classe CSS do status
     */
    public function getStatusClassAttribute()
    {
        $statusClasses = [
            'agendada' => 'warning',
            'confirmada' => 'info',
            'concluida' => 'success',
            'cancelada' => 'danger',
        ];

        return $statusClasses[$this->status] ?? 'secondary';
    }

    /**
     * Accessor para aprovação admin formatada
     */
    public function getAprovacaoAdminFormatadaAttribute()
    {
        $aprovacaoLabels = [
            'pendente' => 'Pendente',
            'aprovada' => 'Aprovada',
            'rejeitada' => 'Rejeitada',
        ];

        return $aprovacaoLabels[$this->aprovacao_admin] ?? $this->aprovacao_admin;
    }

    /**
     * Accessor para classe CSS da aprovação admin
     */
    public function getAprovacaoAdminClassAttribute()
    {
        $aprovacaoClasses = [
            'pendente' => 'warning',
            'aprovada' => 'success',
            'rejeitada' => 'danger',
        ];

        return $aprovacaoClasses[$this->aprovacao_admin] ?? 'secondary';
    }

    /**
     * Verificar se a explicação já passou
     */
    public function jaPassou()
    {
        try {
            $dataHora = Carbon::parse($this->data_explicacao . ' ' . $this->hora_fim);
            return $dataHora->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Verificar se pode ser editada
     */
    public function podeSerEditada()
    {
        return !$this->jaPassou() && 
               $this->status !== 'cancelada' && 
               in_array($this->aprovacao_admin, ['pendente', 'rejeitada']);
    }

    /**
     * Verificar se pode ser cancelada
     */
    public function podeSerCancelada()
    {
        return !$this->jaPassou() && 
               in_array($this->status, ['agendada', 'confirmada']) &&
               $this->aprovacao_admin === 'aprovada';
    }

    /**
     * Verificar se pode ser eliminada
     */
    public function podeSerEliminada()
    {
        return $this->aprovacao_admin !== 'aprovada';
    }
}