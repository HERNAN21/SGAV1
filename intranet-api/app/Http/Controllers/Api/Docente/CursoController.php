<?php

namespace App\Http\Controllers\Api\Docente;

use App\Http\Controllers\Controller;

class CursoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Cursos del docente']);
    }
}