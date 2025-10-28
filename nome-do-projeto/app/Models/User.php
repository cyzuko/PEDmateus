<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Adicionado para controle de acesso (admin/user)
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Get the explicacoes for the user.
     */
    public function explicacoes()
    {
        return $this->hasMany(Explicacao::class);
    }

    /**
     * Relacionamento com grupos (muitos-para-muitos)
     * Um usuário pode estar em vários grupos
     */
    public function grupos()
    {
        return $this->belongsToMany(Grupo::class, 'grupo_membros')
            ->withPivot('admin_grupo', 'notificacoes_ativas', 'ultima_leitura')
            ->withTimestamps();
    }

    /**
     * Relacionamento com mensagens enviadas pelo usuário
     */
    public function mensagens()
    {
        return $this->hasMany(Mensagem::class);
    }

    /**
     * Relacionamento com grupos criados pelo usuário (como admin)
     */
    public function gruposCriados()
    {
        return $this->hasMany(Grupo::class, 'criado_por');
    }

    /**
     * Verifica se o usuário é administrador.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Verifica se o usuário é professor.
     *
     * @return bool
     */
    public function isProfessor()
    {
        return $this->role === 'professor';
    }

    /**
     * Verifica se o usuário é aluno.
     *
     * @return bool
     */
    public function isAluno()
    {
        return $this->role === 'aluno';
    }

    /**
     * Obtém o total de mensagens não lidas em todos os grupos do usuário
     *
     * @return int
     */
    public function getTotalMensagensNaoLidasAttribute()
    {
        return $this->grupos()
            ->where('ativo', true)
            ->get()
            ->sum(function($grupo) {
                return $grupo->mensagensNaoLidas($this->id);
            });
    }

    /**
     * Verifica se o usuário pertence a um determinado grupo
     *
     * @param int $grupoId
     * @return bool
     */
    public function pertenceAoGrupo($grupoId)
    {
        return $this->grupos()->where('grupos.id', $grupoId)->exists();
    }

    /**
     * Verifica se o usuário é admin de um determinado grupo
     *
     * @param int $grupoId
     * @return bool
     */
    public function isAdminDoGrupo($grupoId)
    {
        return $this->grupos()
            ->where('grupos.id', $grupoId)
            ->wherePivot('admin_grupo', true)
            ->exists();
    }
}