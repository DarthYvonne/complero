<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class LandingPageController extends Controller
{
    /**
     * Display the landing page for a mailing list
     */
    public function show($slug)
    {
        $mailingList = MailingList::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        return view('landing-page', compact('mailingList'));
    }

    /**
     * Handle the landing page signup form submission
     */
    public function store(Request $request, $slug)
    {
        $mailingList = MailingList::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
        ]);

        // Check if user already exists
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make(Str::random(32)), // Random password
                'role' => 'member',
            ]);
        }

        // Check if already subscribed
        $existingMembership = $user->mailingLists()
            ->where('mailing_list_id', $mailingList->id)
            ->wherePivot('status', 'active')
            ->exists();

        if (!$existingMembership) {
            // Subscribe user to the mailing list
            $user->mailingLists()->attach($mailingList->id, [
                'subscribed_at' => now(),
                'status' => 'active',
            ]);
        }

        return redirect()->back()->with('success', 'Tak for din tilmelding!');
    }
}
