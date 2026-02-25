<?php

namespace App\Http\Controllers\Api\Docente;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function store(Request $request)
    {
        return response()->json(['message' => 'Asistencia registrada', 'data' => $request->all()]);
    }
}