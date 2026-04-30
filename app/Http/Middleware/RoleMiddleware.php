<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();

        // Admin has access to everything
        if ($user->role === 'admin') {
            return $next($request);
        }

        // Manager can access routes that include 'manager' or 'cashier' in allowed roles
        if ($user->role === 'manager' && (in_array('manager', $roles) || in_array('cashier', $roles))) {
            return $next($request);
        }

        // Check if user's role is in the allowed roles
        if (in_array($user->role, $roles)) {
            return $next($request);
        }

        // Redirect unauthorized users to their own dashboard
        if ($user->role === 'manager') {
            return redirect()->route('manager.dashboard');
        }

        if ($user->role === 'cashier') {
            return redirect()->route('cashier.dashboard');
        }

        abort(403, 'Unauthorized action.');
    }
}
