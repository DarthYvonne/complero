<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Resource;
use Illuminate\Http\Request;

class ContentController extends Controller
{
    /**
     * Display the content page with tabs for courses and resources.
     */
    public function index(Request $request)
    {
        $tab = $request->query('tab', 'courses');

        // Get published courses
        $courses = Course::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        // Get published resources
        $resources = Resource::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return view('content.index', compact('courses', 'resources', 'tab'));
    }
}
