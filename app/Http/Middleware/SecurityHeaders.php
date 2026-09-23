<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecurityHeaders
{
    public function handle($request, Closure $next)
   {
    $response = $next($request);

    $response->headers->set('Strict-Transport-Security', 'max-age=63072000; includeSubDomains; preload');
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'no-referrer-when-downgrade');

    $response->headers->set('Content-Security-Policy',
        "img-src 'self' data:;connect-src 'self'; frame-ancestors 'self';"
    );


    $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');
    $response->headers->set('X-Permitted-Cross-Domain-Policies', 'none');
    $response->headers->set('Cross-Origin-Opener-Policy', 'same-origin');
    $response->headers->set('Cross-Origin-Embedder-Policy', 'require-corp');
    $response->headers->set('Cross-Origin-Resource-Policy', 'same-origin');

    return $response;
  }
}
