<?php

namespace App\Http\Controllers\Api\Bienestar;

use App\Http\Controllers\Controller;

class PsicologiaController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Atenciones psicológicas']);
    }
}