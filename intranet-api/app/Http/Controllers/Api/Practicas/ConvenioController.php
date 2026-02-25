<?php

namespace App\Http\Controllers\Api\Practicas;

use App\Http\Controllers\Controller;

class ConvenioController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Convenios de prácticas']);
    }
}