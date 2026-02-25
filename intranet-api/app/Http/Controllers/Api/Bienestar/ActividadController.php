<?php

namespace App\Http\Controllers\Api\Bienestar;

use App\Http\Controllers\Controller;

class ActividadController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Actividades de bienestar']);
    }
}