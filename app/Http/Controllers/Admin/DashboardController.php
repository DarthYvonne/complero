<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_members' => User::where('role', 'member')->count(),
            'total_creators' => User::where('role', 'creator')->count(),
            'total_admins' => User::where('role', 'admin')->count(),
            'total_users' => User::count(),
        ];

        $recentUsers = User::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'recentUsers'));
    }
}
