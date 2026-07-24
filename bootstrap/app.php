<?php

use App\Http\Middleware\AdminAuthMiddleware;
use App\Http\Middleware\CheckRole; // Add the CheckRole middleware
use App\Http\Middleware\AgencyMiddleware; // Import the AgencyMiddleware
use App\Http\Middleware\FacultyAuth;
use App\Http\Middleware\PreventBackHistory;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Configuration\Exceptions;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
         $middleware->validateCsrfTokens(except: [
        'ccavenue/*',
    ]);

	$middleware->append(\App\Http\Middleware\SecurityHeaders::class);

        $middleware->alias([
            'admin.auth' => AdminAuthMiddleware::class, // Register the admin.auth middleware
            'checkrole' => CheckRole::class, // Register the checkrole middleware
            'agency.auth' => AgencyMiddleware::class, // Register the agency.auth middleware
            'faculty.auth' => FacultyAuth::class,
            'prevent'=>PreventBackHistory::class,
	    'clean.input' => \App\Http\Middleware\CleanInput::class,
            'cors_middleware' => \App\Http\Middleware\CorsMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
