<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, Notifiable;

    // Colunas que podem ser preenchidas
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // Colunas a serem ocultadas (segurança)
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // Campos que devem ser convertidos automaticamente para tipos específicos
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
