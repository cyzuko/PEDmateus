<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    // Nome da tabela no banco de dados
    protected $table = 'users'; 

    // As colunas que podem ser preenchidas via atribuição em massa
    protected $fillable = ['name', 'email', 'password'];

    // As colunas que devem ser escondidas quando a instância do modelo for convertida para array ou JSON
    protected $hidden = ['password', 'remember_token'];

    // Para o Laravel saber os timestamps, mesmo sem as migrations
    public $timestamps = true;

    // Cast de tipos para os campos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
