<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Resource extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'mailing_list_id',
        'title',
        'slug',
        'description',
        'image_url',
        'price',
        'is_free',
        'is_published',
        'stripe_price_id',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_free' => 'boolean',
        'is_published' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($resource) {
            if (empty($resource->slug)) {
                $resource->slug = Str::slug($resource->title);
            }
        });
    }

    /**
     * Get the route key for the model.
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    public function files()
    {
        return $this->hasMany(ResourceFile::class);
    }

    public function tabs()
    {
        return $this->hasMany(ResourceTab::class)->orderBy('order');
    }

    public function accesses()
    {
        return $this->hasMany(ResourceAccess::class);
    }

    public function mailingList()
    {
        return $this->belongsTo(MailingList::class);
    }

    public function hasAccessBy(User $user)
    {
        return $this->accesses()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user has access to this resource
     * Access is granted if:
     * - Resource has no mailing list (free for all)
     * - User is a member of the resource's mailing list
     */
    public function userHasAccess(User $user)
    {
        // If no mailing list, resource is free for all
        if (!$this->mailing_list_id) {
            return true;
        }

        // Check if user is a member of the resource's mailing list
        return $user->mailingLists()
            ->where('mailing_list_id', $this->mailing_list_id)
            ->wherePivot('status', 'active')
            ->exists();
    }
}
