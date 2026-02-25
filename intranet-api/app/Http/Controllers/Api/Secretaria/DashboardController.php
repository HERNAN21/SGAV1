<?php

namespace App\Http\Controllers\Api\Secretaria;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Dashboard Secretaría']);
    }
}