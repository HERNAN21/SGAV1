<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NotificacionController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Listado de notificaciones']);
    }

    public function store(Request $request)
    {
        return response()->json(['message' => 'Notificación creada', 'data' => $request->all()], 201);
    }
}