<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'author' => \App\Http\Middleware\EnsureAuthor::class,
            'reviewer' => \App\Http\Middleware\EnsureReviewer::class,
            'super_admin' => \App\Http\Middleware\EnsureSuperAdmin::class,
            'permission' => \App\Http\Middleware\CheckPermission::class,
        ]);
        
        // Configure RedirectIfAuthenticated to redirect admins to admin dashboard
        \Illuminate\Auth\Middleware\RedirectIfAuthenticated::redirectUsing(function ($request) {
            $user = $request->user();
            if ($user && ($user->isAdmin() || $user->isSuperAdmin())) {
                return route('admin.dashboard');
            }
            return '/';
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
