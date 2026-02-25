<?php

namespace App\Http\Controllers\Api\Finanzas;

use App\Http\Controllers\Controller;

class PagoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de pagos y boletas']);
    }
}