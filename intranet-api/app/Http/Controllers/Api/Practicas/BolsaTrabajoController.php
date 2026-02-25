<?php

namespace App\Http\Controllers\Api\Practicas;

use App\Http\Controllers\Controller;

class BolsaTrabajoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Bolsa de trabajo']);
    }
}