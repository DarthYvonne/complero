<?php

namespace App\Services;

use Brevo\Client\Api\TransactionalEmailsApi;
use Brevo\Client\Configuration;
use Brevo\Client\Model\SendSmtpEmail;
use GuzzleHttp\Client;

class BrevoService
{
    protected $api;

    public function __construct()
    {
        $config = Configuration::getDefaultConfiguration()->setApiKey(
            'api-key',
            config('services.brevo.api_key')
        );

        $this->api = new TransactionalEmailsApi(
            new Client(),
            $config
        );
    }

    /**
     * Send an email via Brevo
     *
     * @param string $toEmail
     * @param string $toName
     * @param string $subject
     * @param string $htmlContent
     * @param string|null $fromEmail
     * @param string|null $fromName
     * @return array ['success' => bool, 'message_id' => string|null, 'error' => string|null]
     */
    public function sendEmail($toEmail, $toName, $subject, $htmlContent, $fromEmail = null, $fromName = null)
    {
        try {
            $sendSmtpEmail = new SendSmtpEmail([
                'subject' => $subject,
                'htmlContent' => $htmlContent,
                'sender' => [
                    'name' => $fromName ?? config('mail.from.name'),
                    'email' => $fromEmail ?? config('mail.from.address')
                ],
                'to' => [
                    [
                        'email' => $toEmail,
                        'name' => $toName
                    ]
                ],
                // Enable tracking
                'params' => [
                    'TRACKING' => true
                ]
            ]);

            $result = $this->api->sendTransacEmail($sendSmtpEmail);

            return [
                'success' => true,
                'message_id' => $result->getMessageId(),
                'error' => null
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message_id' => null,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Send bulk emails via Brevo
     *
     * @param array $recipients Array of ['email' => '...', 'name' => '...']
     * @param string $subject
     * @param string $htmlContent
     * @param string|null $fromEmail
     * @param string|null $fromName
     * @return array ['success' => bool, 'message_ids' => array, 'errors' => array]
     */
    public function sendBulkEmails($recipients, $subject, $htmlContent, $fromEmail = null, $fromName = null)
    {
        $messageIds = [];
        $errors = [];

        foreach ($recipients as $recipient) {
            $result = $this->sendEmail(
                $recipient['email'],
                $recipient['name'],
                $subject,
                $htmlContent,
                $fromEmail,
                $fromName
            );

            if ($result['success']) {
                $messageIds[] = $result['message_id'];
            } else {
                $errors[] = [
                    'email' => $recipient['email'],
                    'error' => $result['error']
                ];
            }

            // Sleep briefly to avoid rate limiting
            usleep(100000); // 0.1 seconds
        }

        return [
            'success' => count($errors) === 0,
            'message_ids' => $messageIds,
            'errors' => $errors
        ];
    }
}
