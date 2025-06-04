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
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuthenticate::class,
            'user.auth' => \App\Http\Middleware\UserAuthenticate::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
         $exceptions->renderable(function (\Illuminate\Auth\AuthenticationException $e, $request) {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }
        
        // Check if the route is for admin
        if (str_contains($request->path(), 'admin')) {
            return redirect()->guest(route('admin.login'))
                ->with('error', 'Please login to continue.');
        }
        
        return redirect()->guest(route('user.login')); });
    })->create();
