<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = [
            'organization_name' => Setting::get('organization_name', 'Complero'),
            'organization_email' => Setting::get('organization_email', ''),
            'organization_website' => Setting::get('organization_website', ''),
        ];

        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'organization_name' => ['required', 'string', 'max:255'],
            'organization_email' => ['nullable', 'email', 'max:255'],
            'organization_website' => ['nullable', 'url', 'max:255'],
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Indstillinger opdateret succesfuldt');
    }
}
