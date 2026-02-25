<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FacultadDepartamento extends Model
{
    protected $table = 'facultades_departamentos';
    protected $primaryKey = 'id_facultad';
    public $timestamps = false;
}