<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Filter by mailing list if selected
        if ($request->filled('mailing_list')) {
            $query->whereHas('mailingLists', function ($q) use ($request) {
                $q->where('mailing_list_id', $request->mailing_list)
                  ->where('status', 'active');
            });
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        $mailingLists = MailingList::orderBy('name')->get();

        return view('admin.users.index', compact('users', 'mailingLists'));
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'creator', 'member'])],
            'website' => ['nullable', 'url', 'max:255'],
            'bio' => ['nullable', 'string', 'max:1000'],
        ]);

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Bruger opdateret succesfuldt');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Du kan ikke slette din egen konto');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Bruger slettet succesfuldt');
    }
}
