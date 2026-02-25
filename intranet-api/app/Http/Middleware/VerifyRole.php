<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();
        $roles = array_values(array_filter(array_map('trim', $roles)));

        $authorized = false;

        if ($user && method_exists($user, 'hasAnyRoleCatalogo')) {
            $authorized = $user->hasAnyRoleCatalogo($roles);
        }

        if (!$authorized && $user && method_exists($user, 'hasAnyRole')) {
            $authorized = $user->hasAnyRole($roles);
        }

        if (!$user || !$authorized) {
            return response()->json(['message' => 'No autorizado por rol'], 403);
        }

        return $next($request);
    }
}