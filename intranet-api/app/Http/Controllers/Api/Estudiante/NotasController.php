<?php

namespace App\Http\Controllers\Api\Estudiante;

use App\Http\Controllers\Controller;

class NotasController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Notas del estudiante']);
    }
}