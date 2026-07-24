<?php
// app/Http/Middleware/CheckModulePermission.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckModulePermission
{
    public function handle(Request $request, Closure $next, string $module, string $action)
    {
        $admin = auth('admin')->user();

        if (! $admin || ! $admin->hasAccess($module, $action)) {
            abort(403, 'Aapko is action ki permission nahi hai.');
        }

        return $next($request);
    }
}