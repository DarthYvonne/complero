<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;

class MailingListController extends Controller
{
    /**
     * Display a listing of the creator's mailing lists.
     */
    public function index()
    {
        $mailingLists = MailingList::where('creator_id', auth()->id())
            ->withCount(['members', 'courses', 'resources'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('creator.mailing-lists.index', compact('mailingLists'));
    }

    /**
     * Show the form for creating a new mailing list.
     */
    public function create()
    {
        return view('creator.mailing-lists.create');
    }

    /**
     * Store a newly created mailing list in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $mailingList = MailingList::create([
            'creator_id' => auth()->id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('creator.mailing-lists.show', $mailingList)
            ->with('success', 'Mailing liste oprettet succesfuldt');
    }

    /**
     * Display the specified mailing list.
     */
    public function show(MailingList $mailingList)
    {
        if ($mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til denne mailing liste');
        }

        $mailingList->load(['activeMembers', 'courses', 'resources']);

        return view('creator.mailing-lists.show', compact('mailingList'));
    }

    /**
     * Show the form for editing the specified mailing list.
     */
    public function edit(MailingList $mailingList)
    {
        if ($mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne mailing liste');
        }

        return view('creator.mailing-lists.edit', compact('mailingList'));
    }

    /**
     * Update the specified mailing list in storage.
     */
    public function update(Request $request, MailingList $mailingList)
    {
        if ($mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at redigere denne mailing liste');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $mailingList->update([
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('creator.mailing-lists.show', $mailingList)
            ->with('success', 'Mailing liste opdateret succesfuldt');
    }

    /**
     * Remove the specified mailing list from storage.
     */
    public function destroy(MailingList $mailingList)
    {
        if ($mailingList->creator_id !== auth()->id()) {
            abort(403, 'Du har ikke adgang til at slette denne mailing liste');
        }

        $mailingList->delete();

        return redirect()
            ->route('creator.mailing-lists.index')
            ->with('success', 'Mailing liste slettet succesfuldt');
    }

    /**
     * Add a member to the mailing list
     */
    public function addMember(Request $request, MailingList $mailingList)
    {
        if ($mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $user = User::findOrFail($validated['user_id']);

        // Check if already a member
        if ($mailingList->hasMember($user)) {
            return back()->with('error', 'Bruger er allerede medlem af denne liste');
        }

        $mailingList->members()->attach($user->id, [
            'subscribed_at' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Medlem tilføjet succesfuldt');
    }

    /**
     * Remove a member from the mailing list
     */
    public function removeMember(MailingList $mailingList, User $user)
    {
        if ($mailingList->creator_id !== auth()->id()) {
            abort(403);
        }

        $mailingList->members()->detach($user->id);

        return back()->with('success', 'Medlem fjernet succesfuldt');
    }
}
