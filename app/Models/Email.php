<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Email extends Model
{
    protected $fillable = [
        'creator_id',
        'mailing_list_id',
        'subject',
        'body_html',
        'recipients_count',
        'sent_at',
        'brevo_message_id',
        'total_opens',
        'unique_opens',
        'total_clicks',
        'unique_clicks',
        'last_opened_at',
        'last_clicked_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'last_opened_at' => 'datetime',
        'last_clicked_at' => 'datetime',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function mailingList()
    {
        return $this->belongsTo(MailingList::class);
    }
}
