<?php

namespace App\Http\Controllers\Creator;

use App\Http\Controllers\Controller;
use App\Models\Email;
use App\Models\MailingList;
use App\Services\BrevoService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmailController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get creator's mailing lists for the composer
        $mailingLists = MailingList::where('creator_id', $user->id)
            ->withCount('activeMembers')
            ->get();

        // Get creator's sent emails for the archive
        $emails = Email::where('creator_id', $user->id)
            ->with('mailingList')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('creator.emails.index', compact('mailingLists', 'emails'));
    }

    public function send(Request $request, BrevoService $brevoService)
    {
        $validated = $request->validate([
            'mailing_list_id' => 'nullable|exists:mailing_lists,id',
            'subject' => 'required|string|max:255',
            'body_html' => 'required|string',
            'test_email' => 'nullable|email',
        ]);

        $user = Auth::user();

        // Get recipients
        $recipients = [];
        $recipientData = [];

        // Check if this is a test email
        if ($request->test_email) {
            $recipientData = [[
                'email' => $request->test_email,
                'name' => $user->name,
            ]];
            $recipients = [$request->test_email];
        } elseif ($request->mailing_list_id) {
            $mailingList = MailingList::findOrFail($request->mailing_list_id);
            $recipientData = $mailingList->activeMembers()->get()->map(function($member) {
                return [
                    'email' => $member->email,
                    'name' => $member->name,
                ];
            })->toArray();
            $recipients = array_column($recipientData, 'email');
        }

        // Save email to database
        $email = Email::create([
            'creator_id' => $user->id,
            'mailing_list_id' => $request->mailing_list_id,
            'subject' => $validated['subject'],
            'body_html' => $validated['body_html'],
            'recipients_count' => count($recipients),
            'sent_at' => now(),
        ]);

        // Send emails using Brevo with placeholder replacement
        $successCount = 0;
        $errors = [];

        foreach ($recipientData as $recipient) {
            $personalizedBody = str_replace(
                ['{{navn}}', '{{email}}'],
                [$recipient['name'], $recipient['email']],
                $validated['body_html']
            );

            $personalizedSubject = str_replace(
                ['{{navn}}', '{{email}}'],
                [$recipient['name'], $recipient['email']],
                $validated['subject']
            );

            $result = $brevoService->sendEmail(
                $recipient['email'],
                $recipient['name'],
                $personalizedSubject,
                $personalizedBody
            );

            if ($result['success']) {
                $successCount++;
                // Store the first message ID for tracking
                if (empty($email->brevo_message_id)) {
                    $email->brevo_message_id = $result['message_id'];
                    $email->save();
                }
            } else {
                $errors[] = $recipient['email'];
            }
        }

        // Determine redirect route
        $redirectRoute = $request->mailing_list_id && !$request->test_email
            ? route('creator.mailing-lists.emails', $request->mailing_list_id)
            : route('creator.emails.index');

        if (count($errors) > 0) {
            return redirect($redirectRoute)
                ->with('warning', 'Email sendt til ' . $successCount . ' af ' . count($recipients) . ' modtagere. ' . count($errors) . ' fejlede.');
        }

        $successMessage = $request->test_email
            ? 'Testmail sendt til ' . $request->test_email
            : 'Email sendt til ' . count($recipients) . ' modtagere';

        return redirect($redirectRoute)
            ->with('success', $successMessage);
    }
}
