<?php

namespace App\Http\Controllers\Api\Bienestar;

use App\Http\Controllers\Controller;

class ServicioController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Servicios de bienestar']);
    }
}