<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsCreatorOrAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            abort(403, 'Adgang nægtet. Kun creators og administratorer har adgang.');
        }

        // Get the effective role (view_as if set, otherwise actual role)
        $effectiveRole = session('view_as', $user->role);

        // Only allow if the effective role is admin or creator
        if (!in_array($effectiveRole, ['admin', 'creator'])) {
            return redirect()->route('dashboard')
                ->with('warning', 'Du kan ikke se denne side som ' . $effectiveRole);
        }

        // Also check if the actual user is an admin or creator (to prevent members from using view_as)
        if (!in_array($user->role, ['admin', 'creator'])) {
            abort(403, 'Adgang nægtet. Kun creators og administratorer har adgang.');
        }

        return $next($request);
    }
}
