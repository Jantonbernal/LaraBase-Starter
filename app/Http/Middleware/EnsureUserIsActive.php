<?php

namespace App\Http\Middleware;

use App\Enums\Status;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica si el usuario autenticado está activo
        if ($request->user()?->status !== Status::ACTIVE) {
            return response()->json([
                'message' => 'Tu cuenta se encuentra desactivada. Contacta al administrador.'
            ], 403);
        }
        return $next($request);
    }
}
