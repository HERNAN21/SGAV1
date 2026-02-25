<?php

namespace App\Http\Controllers\Api\Practicas;

use App\Http\Controllers\Controller;

class EgresadoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Seguimiento de egresados']);
    }
}