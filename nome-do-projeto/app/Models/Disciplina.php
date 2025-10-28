<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Disciplina extends Model
{
    protected $fillable = [
        'nome',
        'disciplina',
        'emoji',
        'capacidade',
        'hora_inicio',
        'hora_fim',
        'cor_badge',
        'sala',
        'ativa',
        'ordem'
    ];

    protected $casts = [
        'ativa' => 'boolean',
        'capacidade' => 'integer',
        'ordem' => 'integer'
    ];

    public function explicacoes()
    {
        return $this->hasMany(Explicacao::class, 'disciplina', 'nome');
    }

    // Scope para buscar apenas disciplinas ativas
    public function scopeAtivas($query)
    {
        return $query->where('ativa', true)->orderBy('ordem');
    }
    
    // Helper para verificar se um horário está dentro do range da disciplina
    public function horarioDisponivel($hora)
    {
        $horaCheck = strtotime($hora);
        $inicio = strtotime($this->hora_inicio);
        $fim = strtotime($this->hora_fim);
        
        return $horaCheck >= $inicio && $horaCheck <= $fim;
    }
}