<?php

namespace App\Http\Controllers\Api\Practicas;

use App\Http\Controllers\Controller;

class EmpresaController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Empresas de prácticas']);
    }
}