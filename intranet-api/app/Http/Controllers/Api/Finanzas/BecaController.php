<?php

namespace App\Http\Controllers\Api\Finanzas;

use App\Http\Controllers\Controller;

class BecaController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de becas']);
    }
}