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
}