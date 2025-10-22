<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Resource;
use App\Models\MailingList;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $creatorId = auth()->id();

        $stats = [
            'total_courses' => Course::where('creator_id', $creatorId)->count(),
            'published_courses' => Course::where('creator_id', $creatorId)->where('is_published', true)->count(),
            'total_resources' => Resource::where('creator_id', $creatorId)->count(),
            'total_mailing_lists' => MailingList::where('creator_id', $creatorId)->count(),
        ];

        $recentCourses = Course::where('creator_id', $creatorId)
            ->with('lessons')
            ->latest()
            ->limit(5)
            ->get();

        return view('creator.dashboard', compact('stats', 'recentCourses'));
    }
}
