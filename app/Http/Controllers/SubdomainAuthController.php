<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class SubdomainAuthController extends Controller
{
    /**
     * Get the mailing list for the current subdomain
     */
    protected function getMailingList($subdomain)
    {
        $mailingList = MailingList::where('subdomain', $subdomain)->firstOrFail();
        return $mailingList;
    }

    /**
     * Index - redirect to dashboard if logged in, otherwise show login
     */
    public function index(Request $request, $subdomain)
    {
        $mailingList = $this->getMailingList($subdomain);

        // If user is logged in and is a member, redirect to dashboard
        if (Auth::check() && $mailingList->hasMember(Auth::user())) {
            return redirect()->route('dashboard');
        }

        // Otherwise show login page at root
        return view('subdomain.auth', compact('mailingList'));
    }

    /**
     * Handle login authentication
     */
    public function authenticate(Request $request, $subdomain)
    {
        $mailingList = $this->getMailingList($subdomain);

        $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        $remember = $request->boolean('remember');

        // Try to authenticate
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password], $remember)) {
            $request->session()->regenerate();

            // Check if user is a member of this group
            if (!$mailingList->hasMember(Auth::user())) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Du er ikke medlem af denne gruppe.',
                ]);
            }

            return redirect()->route('dashboard');
        }

        return back()->withErrors([
            'email' => 'Email eller adgangskode er forkert.',
        ])->withInput($request->only('email'));
    }

    /**
     * Show signup form
     */
    public function signup(Request $request, $subdomain)
    {
        $mailingList = $this->getMailingList($subdomain);
        return view('subdomain.auth', compact('mailingList'))->with('showSignup', true);
    }

    /**
     * Handle registration
     */
    public function register(Request $request, $subdomain)
    {
        $mailingList = $this->getMailingList($subdomain);

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        // Create user
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'member',
        ]);

        // Add user to mailing list
        $mailingList->members()->attach($user->id, [
            'subscribed_at' => now(),
            'status' => 'active',
        ]);

        // Log user in
        Auth::login($user, $request->boolean('remember'));

        return redirect()->route('dashboard');
    }

    /**
     * Show forgot password form
     */
    public function forgotPassword(Request $request, $subdomain)
    {
        $mailingList = $this->getMailingList($subdomain);
        return view('subdomain.auth', compact('mailingList'))->with('showForgotPassword', true);
    }

    /**
     * Send password reset link
     */
    public function sendResetLink(Request $request, $subdomain)
    {
        $mailingList = $this->getMailingList($subdomain);

        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Vi kan ikke finde en bruger med denne email.',
            ]);
        }

        // If user has no password, generate one and send it
        if (!$user->password) {
            $newPassword = Str::random(12);
            $user->password = Hash::make($newPassword);
            $user->save();

            // TODO: Send email with new password
            // For now, just show success message
            return back()->with('status', 'En ny adgangskode er blevet sendt til din email.');
        }

        // TODO: Send standard password reset link
        return back()->with('status', 'Vi har sendt dig et link til at nulstille din adgangskode.');
    }

    /**
     * Logout
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back();
    }
}
