<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    /**
     * Enroll the user in a course.
     */
    public function enroll(Course $course)
    {
        $user = Auth::user();

        // Check if user is already enrolled
        $existingEnrollment = Enrollment::where('user_id', $user->id)
            ->where('course_id', $course->id)
            ->first();

        if (!$existingEnrollment) {
            // Create new enrollment
            Enrollment::create([
                'user_id' => $user->id,
                'course_id' => $course->id,
                'enrolled_at' => now(),
            ]);
        }

        // Get the first lesson
        $firstLesson = $course->lessons()->orderBy('order')->first();

        if ($firstLesson) {
            return redirect()->route('lessons.show', [$course, $firstLesson]);
        }

        // If no lessons, redirect back to course page
        return redirect()->route('courses.show', $course)->with('error', 'Dette forløb har ingen lektioner endnu.');
    }
}
