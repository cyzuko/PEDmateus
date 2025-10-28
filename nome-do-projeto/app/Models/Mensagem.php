<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Mensagem extends Model
{
    use SoftDeletes;

    protected $table = 'mensagens';

    protected $fillable = [
        'grupo_id',
        'user_id',
        'conteudo',
        'tipo',
        'arquivo_url',
        'arquivo_nome',
        'editada',
        'editada_em'
    ];

    protected $casts = [
        'editada' => 'boolean',
        'editada_em' => 'datetime',
    ];

    public function grupo()
    {
        return $this->belongsTo(Grupo::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leituras()
    {
        return $this->hasMany(MensagemLeitura::class);
    }

    public function leitoPor($userId)
    {
        return $this->leituras()->where('user_id', $userId)->exists();
    }
}
