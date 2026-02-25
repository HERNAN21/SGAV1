<?php

namespace App\Http\Controllers\Api\Comun;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerfilController extends Controller
{
    public function show(Request $request)
    {
        return response()->json($request->user());
    }
}