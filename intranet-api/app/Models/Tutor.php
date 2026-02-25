<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tutor extends Model
{
    protected $table = 'tutor_estudiantes';
    public $timestamps = false;
    protected $guarded = [];
}