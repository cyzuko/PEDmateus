<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MensagemLeitura extends Model
{
    protected $table = 'mensagem_leituras';

    protected $fillable = [
        'mensagem_id',
        'user_id',
        'lida_em'
    ];

    protected $casts = [
        'lida_em' => 'datetime',
    ];

    public function mensagem()
    {
        return $this->belongsTo(Mensagem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}