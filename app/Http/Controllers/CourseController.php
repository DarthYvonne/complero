<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * Display a listing of available courses (member view).
     */
    public function index()
    {
        // Show all published courses for members
        $courses = Course::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('courses.index', compact('courses'));
    }

    /**
     * Display the specified course (member view).
     */
    public function show(Course $course)
    {
        // Only show published courses to members
        if (!$course->is_published) {
            abort(404);
        }

        $course->load('tabs', 'lessons', 'creator', 'enrollments');

        return view('courses.show', compact('course'));
    }
}
