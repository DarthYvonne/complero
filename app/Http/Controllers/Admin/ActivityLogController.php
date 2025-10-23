<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with(['user', 'causer', 'subject']);

        // Filter by type
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('user_id', $request->user_id)
                  ->orWhere('causer_id', $request->user_id);
            });
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->orderBy('created_at', 'desc')->paginate(50);

        // Get available activity types
        $activityTypes = ActivityLog::select('type')->distinct()->pluck('type');

        // Get users for filter
        $users = User::orderBy('name')->get();

        return view('admin.activity-logs.index', compact('activities', 'activityTypes', 'users'));
    }
}
