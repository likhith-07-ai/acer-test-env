<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAuthor
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            abort(403, 'Unauthorized access. Please login first.');
        }

        $user = auth()->user();
        
        // Allow author, admin, and super_admin roles
        if (!$user->isAuthor() && !$user->isAdmin() && !$user->isSuperAdmin()) {
            abort(403, 'Unauthorized access. Author privileges required.');
        }

        return $next($request);
    }
}
