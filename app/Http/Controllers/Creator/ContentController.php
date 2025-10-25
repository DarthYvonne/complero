<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
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

        // Get courses
        $coursesQuery = Course::with('creator')->latest();
        if (auth()->user()->role !== 'admin') {
            $coursesQuery->where('creator_id', auth()->id());
        }
        $courses = $coursesQuery->get();

        // Get resources
        $resourcesQuery = Resource::with('creator')->latest();
        if (auth()->user()->role !== 'admin') {
            $resourcesQuery->where('creator_id', auth()->id());
        }
        $resources = $resourcesQuery->get();

        return view('creator.content.index', compact('courses', 'resources', 'tab'));
    }
}
