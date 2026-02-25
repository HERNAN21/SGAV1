<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SedeController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de sedes']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Sede creada', 'data' => $request->all()], 201);
    }
}