<?php

namespace App\Http\Controllers\Api\Finanzas;

use App\Http\Controllers\Controller;

class ReporteController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Reportes y facturación SUNAT']);
    }
}