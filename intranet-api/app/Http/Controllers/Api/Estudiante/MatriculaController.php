<?php

namespace App\Http\Controllers\Api\Estudiante;

use App\Http\Controllers\Controller;

class MatriculaController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Matrículas del estudiante']);
    }
}