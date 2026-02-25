<?php

namespace App\Http\Controllers\Api\Tutor;

use App\Http\Controllers\Controller;

class TutoriadoController extends Controller
{
    public function index()
    {
        return response()->json(['message' => 'Tutorados']);
    }
}