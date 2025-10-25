<?php

namespace App\Http\Controllers;

use App\Models\Enrollment;
use App\Models\MailingList;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Get user's enrolled courses
        $enrolledCourses = Enrollment::where('user_id', $user->id)
            ->with(['course.lessons', 'course.creator', 'lastAccessedLesson'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get all groups the user is a member of
        $userGroups = $user->mailingLists()
            ->wherePivot('status', 'active')
            ->orderBy('name')
            ->get();

        // For creators, also get groups they created
        if (in_array($user->role, ['creator', 'admin'])) {
            $creatorGroups = MailingList::where('creator_id', $user->id)
                ->orderBy('name')
                ->get();

            // Merge and remove duplicates
            $allGroups = $userGroups->merge($creatorGroups)->unique('id')->sortBy('name');
        } else {
            $allGroups = $userGroups;
        }

        // Get selected group from request or default to first group
        $selectedGroupId = $request->input('group');
        $selectedGroup = null;

        if ($selectedGroupId) {
            $selectedGroup = $allGroups->firstWhere('id', $selectedGroupId);
        }

        // If no group selected or group not found, use first available group
        if (!$selectedGroup && $allGroups->isNotEmpty()) {
            $selectedGroup = $allGroups->first();
        }

        // Get emails sent to the selected group since user joined
        $groupEmails = collect();
        if ($selectedGroup) {
            // Get when the user joined this group
            $membership = $user->mailingLists()
                ->where('mailing_list_id', $selectedGroup->id)
                ->first();

            $joinedAt = $membership ? $membership->pivot->subscribed_at : now();

            // Only show emails sent after the user joined
            $groupEmails = \App\Models\Email::where('mailing_list_id', $selectedGroup->id)
                ->where('sent_at', '>=', $joinedAt)
                ->orderBy('sent_at', 'desc')
                ->limit(10)
                ->get();
        }

        return view('dashboard', compact('enrolledCourses', 'allGroups', 'selectedGroup', 'groupEmails'));
    }
}
