<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sede extends Model
{
    protected $table = 'sedes';
    protected $primaryKey = 'id_sede';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'id_institucion',
        'nombre',
        'direccion',
        'telefono',
        'director_sede',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];
}