<?php

namespace App\Http\Controllers\Api\Secretaria;

use App\Http\Controllers\Controller;

class AdmisionController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de admisiones']);
    }
}