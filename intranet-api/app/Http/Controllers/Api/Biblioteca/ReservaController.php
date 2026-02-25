<?php

namespace App\Http\Controllers\Api\Biblioteca;

use App\Http\Controllers\Controller;

class ReservaController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Reservas de biblioteca']);
    }
}