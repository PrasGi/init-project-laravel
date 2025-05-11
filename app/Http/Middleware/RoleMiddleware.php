<?php

namespace App\Http\Middleware;

use App\Helpers\ResponseHelper;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        foreach ($roles as $role){
            if ($request->user()->role->name == $role){
                return $next($request);
            }
        }

        return ResponseHelper::error(
            message: 'Unauthorized',
            errors: [
                'role' => 'You are not authorized to access this route, expected role: ' . implode(', ', $roles) . ' but got ' . $request->user()->role->name
            ]
        );
    }
}
