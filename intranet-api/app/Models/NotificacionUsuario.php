<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NotificacionUsuario extends Model
{
    protected $table = 'notificacion_usuarios';
    protected $primaryKey = 'id_notificacion_usuario';
    public $timestamps = false;
}