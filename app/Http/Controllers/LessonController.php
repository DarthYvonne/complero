<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    /**
     * Display the lesson player (member view).
     */
    public function show(Course $course, Lesson $lesson)
    {
        // Only show published courses to members
        if (!$course->is_published) {
            abort(404);
        }

        // Verify lesson belongs to course
        if ($lesson->course_id !== $course->id) {
            abort(404);
        }

        $lesson->load('files', 'tabs');

        // Get all lessons for navigation
        $lessons = $course->lessons()->orderBy('order')->get();

        return view('lessons.show', compact('course', 'lesson', 'lessons'));
    }
}
