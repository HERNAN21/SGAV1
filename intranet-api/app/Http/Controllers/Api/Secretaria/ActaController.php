<?php

namespace App\Http\Controllers\Api\Secretaria;

use App\Http\Controllers\Controller;

class ActaController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de actas']);
    }
}