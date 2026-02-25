<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class UsuarioController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de usuarios']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Usuario creado', 'data' => $request->all()], 201);
    }
}