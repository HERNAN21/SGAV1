<?php

namespace App\Http\Controllers\Api\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CalificacionController extends Controller
{
    public function store(Request $request)
    {
        return response()->json(['message' => 'Calificaciones registradas', 'data' => $request->all()]);
    }
}