<?php

namespace App\View\Composers;

use App\Models\Course;
use App\Models\MailingList;
use App\Models\Resource;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class MenuComposer
{
    /**
     * Bind data to the view.
     */
    public function compose(View $view): void
    {
        $user = Auth::user();

        if (!$user) {
            return;
        }

        // Get counts for creator menu
        $coursesCount = 0;
        $resourcesCount = 0;
        $mailingListsCount = 0;

        if (in_array($user->role, ['creator', 'admin'])) {
            $coursesCount = Course::where('creator_id', $user->id)->count();
            $resourcesCount = Resource::where('creator_id', $user->id)->count();
            $mailingListsCount = MailingList::where('creator_id', $user->id)->count();
        }

        $view->with([
            'coursesCount' => $coursesCount,
            'resourcesCount' => $resourcesCount,
            'mailingListsCount' => $mailingListsCount,
        ]);
    }
}
