<?php

namespace App\Http\Controllers\Api\Comun;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Resumen de dashboard']);
    }
}