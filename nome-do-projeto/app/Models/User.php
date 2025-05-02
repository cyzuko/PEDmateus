<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;  // Altere isso
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable // Estenda esta classe
{
    use Notifiable;

    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}
