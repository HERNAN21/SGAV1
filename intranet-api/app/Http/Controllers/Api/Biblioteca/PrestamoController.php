<?php

namespace App\Http\Controllers\Api\Biblioteca;

use App\Http\Controllers\Controller;

class PrestamoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Préstamos de biblioteca']);
    }
}