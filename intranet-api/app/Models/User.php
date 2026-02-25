<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    protected $table = 'usuarios';
    protected $primaryKey = 'id_usuario';
    public const CREATED_AT = 'fecha_registro';
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'documento',
        'nombres',
        'apellidos',
        'email',
        'password',
        'telefono',
        'direccion',
        'estado',
    ];

    protected $hidden = ['password'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'estado' => 'boolean',
        'es_activo' => 'boolean',
        'last_login' => 'datetime',
        'bloqueado_hasta' => 'datetime',
    ];

    public function rolesCatalogo(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'usuario_roles',
            'id_usuario',
            'id_rol',
            'id_usuario',
            'id_rol'
        );
    }

    public function hasAnyRoleCatalogo(array $roles): bool
    {
        if (empty($roles)) {
            return false;
        }

        return $this->rolesCatalogo()
            ->whereIn('nombre', $roles)
            ->exists();
    }

    public function getPrimaryRoleAttribute(): ?string
    {
        return $this->rolesCatalogo()->value('nombre');
    }
}