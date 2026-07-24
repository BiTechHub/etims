<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
       
        // if (!Auth::guard('admin')->check()) {
        //     return redirect()->route('admin.login');
        // }

        if (!Auth::guard('admin')->check()) {
        return redirect()->route('welcome'); 
    }

        $lifetime = config('session.lifetime') * 60;

        if (session()->has('last_activity')) {

            $inactive = time() - session('last_activity');

            if ($inactive > $lifetime) {

                Auth::guard('admin')->logout();
                session()->invalidate();
                session()->regenerateToken();

                // return redirect()->route('admin.login')
                //     ->with('message', 'Session expired');
                 return redirect()->route('welcome') 
                ->with('message', 'Session expired');
            }
        }

        session(['last_activity' => time()]);

        return $next($request);
    }
}
