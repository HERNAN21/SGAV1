<?php

namespace App\Http\Controllers\Api\Biblioteca;

use App\Http\Controllers\Controller;

class RecursoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Recursos de biblioteca']);
    }
}