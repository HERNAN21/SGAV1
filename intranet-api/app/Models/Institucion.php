<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Institucion extends Model
{
    protected $table = 'instituciones';
    protected $primaryKey = 'id_institucion';
    public const CREATED_AT = 'created_at';
    public const UPDATED_AT = 'updated_at';

    protected $fillable = [
        'nombre',
        'id_tipo_institucion',
        'ruc',
        'direccion',
        'telefono',
        'email',
        'sitio_web',
        'fecha_creacion',
        'rector_director',
        'logo_url',
        'estado',
    ];

    protected $casts = [
        'estado' => 'boolean',
        'fecha_creacion' => 'date',
    ];
}