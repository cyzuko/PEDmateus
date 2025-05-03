<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    // Define nome da tabela explicitamente
    protected $table = 'faturas';

    // Define colunas que podem ser atribuídas em massa
    protected $fillable = [
        'user_id',
        'fornecedor',
        'data',
        'valor',
        'imagem'
    ];

    // Define as colunas de data para trabalhar com Carbon
    protected $dates = [
        'data',
        'criado_em',
        'atualizado_em'
    ];

    // Mapeamento para os nomes reais das colunas na tabela
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    /**
     * Get the user that owns the fatura.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}