<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FacultadController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de facultades/departamentos']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Facultad/Departamento creado', 'data' => $request->all()], 201);
    }
}