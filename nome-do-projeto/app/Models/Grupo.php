<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    /**
     * Usuário que criou o grupo
     */
    public function criador()
    {
        return $this->belongsTo(User::class, 'criado_por');
    }

    /**
     * Membros do grupo (relação muitos-para-muitos)
     */
    public function membros()
    {
        return $this->belongsToMany(User::class, 'grupo_membros')
            ->withPivot('admin_grupo', 'notificacoes_ativas', 'ultima_leitura')
            ->withTimestamps();
    }

    /**
     * Todas as mensagens do grupo
     */
    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }

    /**
     * Última mensagem do grupo
     */
    public function ultimaMensagem()
    {
        return $this->hasOne(Mensagem::class)
            ->latestOfMany('created_at');
    }

    /**
     * OTIMIZADO: Conta mensagens não lidas usando query direta
     */
    public function mensagensNaoLidas($userId)
    {
        return DB::table('mensagens')
            ->where('grupo_id', $this->id)
            ->where('user_id', '!=', $userId)
            ->whereNotExists(function($query) use ($userId) {
                $query->select(DB::raw(1))
                    ->from('mensagem_leituras')
                    ->whereColumn('mensagem_leituras.mensagem_id', 'mensagens.id')
                    ->where('mensagem_leituras.user_id', $userId);
            })
            ->count();
    }

    /**
     * Verifica se o grupo está ativo
     */
    public function isAtivo()
    {
        return $this->ativo;
    }

    /**
     * Verifica se um usuário é membro do grupo
     */
    public function temMembro($userId)
    {
        return $this->membros()->where('users.id', $userId)->exists();
    }
}