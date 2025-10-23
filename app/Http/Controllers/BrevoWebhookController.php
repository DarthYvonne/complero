<?php

namespace App\Http\Controllers;

use App\Models\Email;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class BrevoWebhookController extends Controller
{
    /**
     * Handle incoming Brevo webhook events
     */
    public function handle(Request $request)
    {
        try {
            $event = $request->input('event');
            $messageId = $request->input('message-id');
            $email = $request->input('email');
            $timestamp = $request->input('ts_event');

            Log::info('Brevo webhook received', [
                'event' => $event,
                'message_id' => $messageId,
                'email' => $email,
            ]);

            // Find the email campaign by message ID
            $emailRecord = Email::where('brevo_message_id', $messageId)->first();

            if (!$emailRecord) {
                Log::warning('Email record not found for message ID: ' . $messageId);
                return response()->json(['status' => 'ok']);
            }

            // Handle different event types
            switch ($event) {
                case 'opened':
                case 'unique_opened':
                    $this->handleOpen($emailRecord, $event === 'unique_opened');
                    break;

                case 'click':
                case 'unique_click':
                    $this->handleClick($emailRecord, $event === 'unique_click');
                    break;

                case 'delivered':
                    Log::info('Email delivered: ' . $messageId);
                    break;

                case 'hard_bounce':
                case 'soft_bounce':
                case 'invalid_email':
                    Log::warning('Email bounce: ' . $event . ' for ' . $email);
                    break;

                case 'spam':
                case 'blocked':
                    Log::warning('Email spam/blocked: ' . $event . ' for ' . $email);
                    break;

                case 'unsubscribe':
                    Log::info('User unsubscribed: ' . $email);
                    break;
            }

            return response()->json(['status' => 'ok']);

        } catch (\Exception $e) {
            Log::error('Brevo webhook error: ' . $e->getMessage());
            return response()->json(['status' => 'error'], 500);
        }
    }

    /**
     * Handle email open events
     */
    protected function handleOpen(Email $email, bool $unique)
    {
        $email->total_opens++;

        if ($unique) {
            $email->unique_opens++;
        }

        $email->last_opened_at = now();
        $email->save();

        Log::info('Email opened', [
            'email_id' => $email->id,
            'unique' => $unique,
            'total_opens' => $email->total_opens,
            'unique_opens' => $email->unique_opens,
        ]);
    }

    /**
     * Handle email click events
     */
    protected function handleClick(Email $email, bool $unique)
    {
        $email->total_clicks++;

        if ($unique) {
            $email->unique_clicks++;
        }

        $email->last_clicked_at = now();
        $email->save();

        Log::info('Email clicked', [
            'email_id' => $email->id,
            'unique' => $unique,
            'total_clicks' => $email->total_clicks,
            'unique_clicks' => $email->unique_clicks,
        ]);
    }
}
