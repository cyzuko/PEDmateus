<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Fatura extends Model
{
    use HasFactory;

    protected $table = 'faturas';
    
    protected $fillable = [
        'user_id',
        'fornecedor',
        'nif', // Adicionado NIF
        'data',
        'valor',
        'imagem'
    ];

    // Define nomes personalizados para os timestamps
    const CREATED_AT = 'criado_em';
    const UPDATED_AT = 'atualizado_em';

    protected $dates = [
        'data',
        'criado_em',
        'atualizado_em'
    ];

    protected $casts = [
        'data' => 'date',
        'valor' => 'decimal:2'
    ];

    // Relacionamento com o usuário
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    // Formatar data para exibição
    public function getFormattedDataAttribute()
    {
        return Carbon::parse($this->data)->format('d/m/Y');
    }

    // Formatar valor para exibição
    public function getFormattedValorAttribute()
    {
        return number_format($this->valor, 2, ',', '.');
    }
    
    // Formatar NIF para exibição (opcional)
    public function getFormattedNifAttribute()
    {
        if (empty($this->nif)) {
            return 'Não informado';
        }
        
        // Para Portugal, formata o NIF com espaços a cada 3 dígitos
        // Ex: 123 456 789
        return trim(chunk_split($this->nif, 3, ' '));
    }
}