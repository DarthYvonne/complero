<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get user's enrolled courses
        $enrolledCourses = Enrollment::where('user_id', $user->id)
            ->with(['course.lessons', 'course.creator', 'lastAccessedLesson'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard', compact('enrolledCourses'));
    }
}
