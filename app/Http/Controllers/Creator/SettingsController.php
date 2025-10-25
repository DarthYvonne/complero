<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    /**
     * Show the settings page.
     */
    public function index()
    {
        $user = auth()->user();

        return view('creator.settings.index', compact('user'));
    }

    /**
     * Update the settings.
     */
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'organization_name' => ['nullable', 'string', 'max:255'],
        ]);

        $user->update($validated);

        return redirect()->route('creator.settings.index')
            ->with('success', 'Indstillinger opdateret succesfuldt');
    }
}
