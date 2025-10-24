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
        'video_path',
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

    public function mailingLists()
    {
        return $this->belongsToMany(MailingList::class);
    }

    public function hasAccessBy(User $user)
    {
        return $this->accesses()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user has access to this resource
     * Access is granted if:
     * - Resource has no mailing lists (free for all)
     * - User is a member of at least one of the resource's mailing lists
     */
    public function userHasAccess(User $user)
    {
        // If no mailing lists assigned, resource is free for all
        if ($this->mailingLists->isEmpty()) {
            return true;
        }

        // Check if user is a member of any of the resource's mailing lists
        $resourceMailingListIds = $this->mailingLists->pluck('id');

        return $user->mailingLists()
            ->whereIn('mailing_list_id', $resourceMailingListIds)
            ->wherePivot('status', 'active')
            ->exists();
    }

    /**
     * Get the placeholder image (use magenta as default for resources)
     */
    public function getPlaceholderImageAttribute()
    {
        return asset('graphics/placeholder-magenta.jpg');
    }

    /**
     * Get the image URL or placeholder
     */
    public function getImageAttribute()
    {
        if ($this->image_url) {
            return \Storage::url($this->image_url);
        }
        return $this->placeholder_image;
    }

    /**
     * Get the full URL for the video file.
     */
    public function getVideoUrl()
    {
        if (!$this->video_path) {
            return null;
        }

        $url = \Illuminate\Support\Facades\Storage::disk('videos')->url($this->video_path);

        // Always force HTTPS - we're always on HTTPS in production
        $url = str_replace('http://', 'https://', $url);

        return $url;
    }
}
