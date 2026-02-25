<?php

namespace App\Http\Controllers\Api\Estudiante;

use App\Http\Controllers\Controller;

class HorarioController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Horario del estudiante']);
    }
}