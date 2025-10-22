<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Adgang nægtet. Kun administratorer har adgang.');
        }

        // Get the effective role (view_as if set, otherwise actual role)
        $effectiveRole = session('view_as', $user->role);

        // Only allow admin routes if the effective role is admin
        if ($effectiveRole !== 'admin') {
            return redirect()->route('dashboard')
                ->with('warning', 'Du kan ikke se denne side som ' . $effectiveRole);
        }

        // Also check if the actual user is an admin (to prevent non-admins from using view_as)
        if ($user->role !== 'admin') {
            abort(403, 'Adgang nægtet. Kun administratorer har adgang.');
        }

        return $next($request);
    }
}
