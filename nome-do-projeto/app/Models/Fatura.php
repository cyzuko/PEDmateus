<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    /**
     * Nome da tabela
     */
    protected $table = 'faturas';

    /**
     * Colunas para datas
     */
    protected $dates = [
        'data',
    ];

    /**
     * Nome das colunas de timestamp
     */
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    /**
     * Atributos que podem ser preenchidos em massa
     */
    protected $fillable = [
        'user_id',
        'fornecedor',
        'data',
        'valor',
        'imagem',
    ];

    /**
     * Relacionamento com usuário
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}