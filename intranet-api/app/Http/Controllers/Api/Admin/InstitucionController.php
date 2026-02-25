<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institucion;
use Illuminate\Http\Request;

class InstitucionController extends Controller
{
    public function index()
    {
        return response()->json(Institucion::query()->paginate(15));
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Pendiente implementación', 'data' => $request->all()], 201);
    }
}