<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
$response = $next($request);

     return $response;
        // Allowed origins
        $allowedOrigins = [
            'https://uattims-bird.nabard.org/',
            'http://uattims-bird.nabard.org/',
        ];

        $origin = $request->headers->get('Origin');

        // Check if origin is allowed
        if ($origin && !in_array($origin, $allowedOrigins)) {

            return response()->json([
                'status' => 'error',
                'message' => 'CORS blocked: Your origin is not allowed to access this resource.',
                'origin' => $origin
            ], 403);
        }

        // Handle preflight request (OPTIONS)
        if ($request->getMethod() === "OPTIONS") {
            return response('', 200)
                ->header('Access-Control-Allow-Origin', $origin ?? '*')
                ->header('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS')
                ->header('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }
        
        // Attach headers for allowed origins
        if ($origin && in_array($origin, $allowedOrigins)) {
            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Content-Type, Authorization');
        }

       
    }

}
