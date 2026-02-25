<?php

namespace App\Http\Controllers\Api\Comun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ArchivoController extends Controller
{
    public function store(Request $request)
    {
        return response()->json(['message' => 'Subida de archivo pendiente', 'data' => $request->all()]);
    }
}