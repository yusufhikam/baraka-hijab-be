<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {

        if ($request->user()->role !== $role) {
            if ($request->expectsJson()) {
                return response()->json(['message', 'Unauthorized'], 401);
            }

            Auth::login();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            if ($role === 'admin') {
                return redirect('/admin/login')->with('error', 'You are not authorized to access this page.');
            } else {
                return redirect('/login')->with('error', 'You are not authorized to access this page.');
            }
        }
        return $next($request);
    }
}