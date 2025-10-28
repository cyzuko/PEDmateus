<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    protected $fillable = [
        'nome',
        'descricao',
        'icone',
        'cor',
        'ativo',
        'criado_por'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    public function membros()
    {
        return $this->belongsToMany(User::class, 'grupo_membros')
            ->withPivot('admin_grupo', 'notificacoes_ativas', 'ultima_leitura')
            ->withTimestamps();
    }

    public function mensagens()
    {
        return $this->hasMany(Mensagem::class)->orderBy('created_at', 'desc');
    }

    public function ultimaMensagem()
    {
        return $this->hasOne(Mensagem::class)->latestOfMany();
    }

    public function mensagensNaoLidas($userId)
    {
        return $this->mensagens()
            ->where('user_id', '!=', $userId)
            ->whereDoesntHave('leituras', function ($query) use ($userId) {
                $query->where('user_id', $userId);
            })
            ->count();
    }
}