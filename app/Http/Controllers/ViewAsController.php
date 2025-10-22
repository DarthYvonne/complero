<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViewAsController extends Controller
{
    /**
     * Switch the view as role for admins.
     */
    public function switch(Request $request, $role)
    {
        // Only admins can use this feature
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $allowedRoles = ['admin', 'creator', 'member'];

        if (!in_array($role, $allowedRoles)) {
            abort(400, 'Invalid role');
        }

        // Set the view_as role in session
        session(['view_as' => $role]);

        // Check if current route is accessible with the new role
        $currentRoute = $request->route()->getName();

        // Admin-only routes
        if (str_starts_with($currentRoute, 'admin.') && $role !== 'admin') {
            return redirect()->route('dashboard')->with('info', 'Nu ser du som ' . $role);
        }

        // Redirect back to current page or dashboard
        return back()->with('info', 'Nu ser du som ' . $role);
    }
}
