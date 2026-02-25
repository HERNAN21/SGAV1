<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials)) {
            return response()->json(['message' => 'Credenciales inválidas'], 401);
        }

        $user = $request->user();

        if (!$user->estado || !$user->es_activo) {
            return response()->json(['message' => 'Usuario inactivo'], 403);
        }

        $token = $user->createToken('spa-token')->plainTextToken;

        return response()->json([
            'token' => $token,
            'user' => [
                ...$user->toArray(),
                'roles' => $user->rolesCatalogo()->pluck('nombre')->values(),
                'rol_principal' => $user->primary_role,
            ],
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()?->delete();

        return response()->json(['message' => 'Sesión cerrada']);
    }

    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            ...$user->toArray(),
            'roles' => $user->rolesCatalogo()->pluck('nombre')->values(),
            'rol_principal' => $user->primary_role,
        ]);
    }
}