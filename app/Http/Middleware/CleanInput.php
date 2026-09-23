<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CleanInput
{
    public function handle(Request $request, Closure $next)
    {
        $input = $request->all();

        foreach ($input as $key => $value) {

            if (is_string($value)) {

                // ❌ HTML detect
                if ($value != strip_tags($value)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors([
                            $key => 'HTML / Script tags are not allowed in input fields.'
                        ]);
                }

                // ❌ XSS detect
                if (preg_match('/<script|javascript:|onerror=|onload=/i', $value)) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors([
                            $key => 'Malicious script detected in input.'
                        ]);
                }
            }
        }

        return $next($request);
    }
}