<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureSecuritySetup
{
    /**
     * Handle an incoming request.
     * Ensure the user has set up their security question.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && !auth()->user()->security_setup_completed) {
            // Allow access to security setup, logout, and password reset routes
            $allowedPaths = ['security/setup', 'logout', 'reset-password'];
            $currentPath = $request->path();
            
            $isAllowed = false;
            foreach ($allowedPaths as $path) {
                if (str_starts_with($currentPath, $path)) {
                    $isAllowed = true;
                    break;
                }
            }
            
            if (!$isAllowed) {
                return redirect()->route('security.setup');
            }
        }

        return $next($request);
    }
}
