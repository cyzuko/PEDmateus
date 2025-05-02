<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fatura extends Model
{
    use HasFactory;

    // Nome da tabela
    protected $table = 'faturas';

    // Desativar timestamps automáticos, caso não queira usar as colunas created_at/updated_at
    public $timestamps = false;

    // Colunas que podem ser preenchidas
    protected $fillable = [
        'user_id',
        'fornecedor',
        'data',
        'valor',
        'imagem',
    ];

    // Relacionamento com o usuário (usuário que criou a fatura)
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
