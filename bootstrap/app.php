<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin*')) {
                return route('login.admin');
            }
            return route('login');
        });
        
        $middleware->appendToGroup('web', \App\Http\Middleware\RedirectAuthenticatedUsers::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
