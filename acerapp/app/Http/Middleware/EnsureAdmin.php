<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
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

        // Super admin has all access
        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        // Admin has all access
        if ($user->isAdmin()) {
            return $next($request);
        }

        // Check if user has any admin permissions (dashboard, documents, policies, etc.)
        // This allows authors/reviewers with proper permissions to access admin panel
        $hasAdminAccess = $user->hasAnyPermission([
            'dashboard.view',
            'documents.view',
            'documents.create',
            'documents.edit',
            'policies.view',
            'policies.create',
            'policies.edit',
            'research-articles.view',
            'research-articles.create',
            'research-articles.edit',
            'press-releases.view',
            'press-releases.create',
            'press-releases.edit',
        ]);

        if (!$hasAdminAccess) {
            abort(403, 'Unauthorized access. Admin privileges required.');
        }

        return $next($request);
    }
}
