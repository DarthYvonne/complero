<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MailingList extends Model
{
    use HasFactory;

    protected $fillable = [
        'creator_id',
        'name',
        'slug',
        'subdomain',
        'description',
        'is_active',
        'signup_form_template',
        'signup_form_data',
        'offer_membership',
        'welcome_header',
        'welcome_text',
        'welcome_image',
        'organization_name',
        'website',
        'responsible_person',
        'support_email',
        'primary_color',
        'secondary_color',
        'landing_hero_title',
        'landing_hero_subtitle',
        'landing_hero_image',
        'landing_feature_1',
        'landing_feature_2',
        'landing_feature_3',
        'landing_cta_text',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'signup_form_data' => 'array',
        'offer_membership' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($list) {
            if (empty($list->slug)) {
                $list->slug = static::generateUniqueSlug($list->name);
            }
        });
    }

    /**
     * Generate a unique slug for the mailing list
     */
    protected static function generateUniqueSlug($name)
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Get the creator of this mailing list
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    /**
     * Get all users subscribed to this list
     */
    public function members()
    {
        return $this->belongsToMany(User::class, 'list_memberships')
            ->withPivot('subscribed_at', 'unsubscribed_at', 'status')
            ->withTimestamps()
            ->using(ListMembership::class);
    }

    /**
     * Get active members only
     */
    public function activeMembers()
    {
        return $this->members()->wherePivot('status', 'active');
    }

    /**
     * Get all courses available to this list
     */
    public function courses()
    {
        return $this->hasMany(Course::class);
    }

    /**
     * Get all resources available to this list (legacy - single mailing list)
     */
    public function resources()
    {
        return $this->belongsToMany(Resource::class);
    }

    /**
     * Check if a user is a member of this list
     */
    public function hasMember(User $user)
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->wherePivot('status', 'active')
            ->exists();
    }
}
