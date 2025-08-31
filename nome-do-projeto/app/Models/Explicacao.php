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
    ];

    // Remove os casts problemáticos temporariamente
    protected $casts = [
        'preco' => 'decimal:2',
    ];

    /**
     * Relacionamento com o usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
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
     * Accessor para formatação da data
     */
    public function getDataFormatadaAttribute()
    {
        try {
            return $this->data_explicacao ? $this->data_explicacao->format('d/m/Y') : '';
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
        $inicio = Carbon::createFromFormat('H:i', $this->hora_inicio);
        $fim = Carbon::createFromFormat('H:i', $this->hora_fim);
        
        return $fim->diffInMinutes($inicio);
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
     * Verificar se a explicação já passou
     */
    public function jaPassou()
    {
        $dataHora = Carbon::parse($this->data_explicacao . ' ' . $this->hora_fim);
        return $dataHora->isPast();
    }

    /**
     * Verificar se pode ser editada
     */
    public function podeSerEditada()
    {
        return !$this->jaPassou() && $this->status !== 'cancelada';
    }

    /**
     * Verificar se pode ser cancelada
     */
    public function podeSerCancelada()
    {
        return !$this->jaPassou() && in_array($this->status, ['agendada', 'confirmada']);
    }
}