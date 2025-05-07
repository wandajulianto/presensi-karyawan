<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RedirectAuthenticatedUsers
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    // app/Http/Middleware/RedirectAuthenticatedUsers.php
    public function handle(Request $request, Closure $next)
    {
        // Skip middleware untuk route logout
        if ($request->routeIs('logout*')) {
            return $next($request);
        }

        // Jika admin sudah login
        if (Auth::guard('user')->check()) {
            if ($request->is('/') || $request->routeIs('login.*')) {
                return redirect()->route('dashboard.admin');
            }
        }
        
        // Jika karyawan sudah login
        if (Auth::guard('karyawan')->check()) {
            if ($request->is('/') || $request->is('/admin*') || $request->routeIs('login.*')) {
                return redirect()->route('dashboard');
            }
        }
        
        return $next($request);
    }
}
