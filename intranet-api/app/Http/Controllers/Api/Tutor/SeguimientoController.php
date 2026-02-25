<?php

namespace App\Http\Controllers\Api\Tutor;

use App\Http\Controllers\Controller;

class SeguimientoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Seguimiento de tutorados']);
    }
}