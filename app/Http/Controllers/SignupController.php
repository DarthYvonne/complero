<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SignupController extends Controller
{
    /**
     * Display the public signup form for a mailing list
     */
    public function show(string $slug)
    {
        $mailingList = MailingList::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        // Get the selected template and form data
        $template = $mailingList->signup_form_template ?? 'simple';
        $formData = $mailingList->signup_form_data ?? [];

        // Get defaults for the template
        $defaults = [
            'simple' => [
                'image' => asset('graphics/logo.png'),
                'header' => 'Tilmeld dig vores liste',
                'body' => 'Få eksklusive opdateringer og indhold direkte i din indbakke.',
                'buttonText' => 'Tilmeld',
                'buttonColor' => '#B9185E'
            ],
            'modern' => [
                'image' => asset('graphics/header-placeholder.jpg'),
                'header' => 'Bliv en del af vores community',
                'body' => 'Tilmeld dig og få adgang til eksklusivt indhold og ressourcer.',
                'buttonText' => 'Start nu',
                'buttonColor' => '#B9185E'
            ],
            'split' => [
                'image' => asset('graphics/side-placeholder.jpg'),
                'header' => 'Tilmeld dig i dag',
                'body' => 'Få de nyeste nyheder og opdateringer.',
                'buttonText' => 'Tilmeld',
                'buttonColor' => '#B9185E'
            ]
        ];

        // Merge defaults with saved data
        $data = array_merge($defaults[$template] ?? [], $formData[$template] ?? []);

        return response()
            ->view('signup.form', compact('mailingList', 'template', 'data'))
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    /**
     * Handle the signup form submission
     */
    public function store(Request $request, string $slug)
    {
        $mailingList = MailingList::where('slug', $slug)
            ->where('is_active', true)
            ->firstOrFail();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        // Check if user already exists
        $user = User::where('email', $validated['email'])->first();

        if (!$user) {
            // Determine password: use provided password or generate random one
            $password = !empty($validated['password'])
                ? $validated['password']
                : Str::random(32);

            // Create new user
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($password),
                'role' => 'member',
            ]);
        } elseif (!empty($validated['password'])) {
            // User exists and provided a password - update their password
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        // Check if already subscribed
        if ($mailingList->hasMember($user)) {
            return back()->with('info', 'Du er allerede tilmeldt denne liste.');
        }

        // Subscribe user to mailing list
        $mailingList->members()->attach($user->id, [
            'subscribed_at' => now(),
            'status' => 'active',
        ]);

        return back()->with('success', 'Tak for din tilmelding!');
    }
}
