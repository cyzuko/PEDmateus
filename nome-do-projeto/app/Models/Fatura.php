<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    // Nome da tabela
    protected $table = 'faturas';

    // Desativar timestamps do Laravel se você estiver usando campos personalizados
    public $timestamps = false;

    // Colunas que podem ser preenchidas
    protected $fillable = [
        'user_id',
        'fornecedor',
        'data',
        'valor',
        'imagem',
    ];

    // Definir os nomes personalizados dos timestamps se você os estiver usando
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    // Relação com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}