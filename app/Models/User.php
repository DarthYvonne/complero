<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'website',
        'bio',
        'organization_name',
        'organization_email',
        'imported_from',
        'external_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get all mailing lists this user is subscribed to
     */
    public function mailingLists()
    {
        return $this->belongsToMany(MailingList::class, 'list_memberships')
            ->withPivot('subscribed_at', 'unsubscribed_at', 'status')
            ->withTimestamps();
    }

    /**
     * Get only active mailing list subscriptions
     */
    public function activeMailingLists()
    {
        return $this->mailingLists()->wherePivot('status', 'active');
    }

    /**
     * Check if user is subscribed to a specific mailing list
     */
    public function isSubscribedTo(MailingList $list)
    {
        return $this->mailingLists()
            ->where('mailing_list_id', $list->id)
            ->wherePivot('status', 'active')
            ->exists();
    }
}
