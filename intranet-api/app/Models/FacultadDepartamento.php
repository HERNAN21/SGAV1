<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultadDepartamento extends Model
{
    protected $table = 'facultades_departamentos';
    protected $primaryKey = 'id_facultad';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_sede',
        'id_tipo_facultad',
        'nombre',
        'director',
        'telefono',
        'email',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}