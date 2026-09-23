<?php

use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\CheckRole; // Add the CheckRole middleware
use App\Http\Middleware\AgencyMiddleware; // Import the AgencyMiddleware
use App\Http\Middleware\FacultyAuth;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;
use App\Http\Middleware\CheckModuleAccess;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        $middleware->validateCsrfTokens(except: [
            'ccavenue/*',
        ]);
    
        // ✅ ADD THIS LINE (GLOBAL SECURITY HEADERS)
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
    
        // ✅ YOUR ROUTE MIDDLEWARES
        $middleware->alias([
            'admin.auth' => \App\Http\Middleware\AdminAuthMiddleware::class,
            'checkrole' => \App\Http\Middleware\CheckRole::class,
            'agency.auth' => \App\Http\Middleware\AgencyMiddleware::class,
            'faculty.auth' => \App\Http\Middleware\FacultyAuth::class,
            'prevent' => \App\Http\Middleware\PreventBackHistory::class,
            'module.access' => \App\Http\Middleware\CheckModuleAccess::class, 

        ]);
    
    })

    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
