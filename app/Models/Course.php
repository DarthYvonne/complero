<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Course extends Model
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

        static::creating(function ($course) {
            if (empty($course->slug)) {
                $course->slug = Str::slug($course->title);
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

    public function lessons()
    {
        return $this->hasMany(Lesson::class)->orderBy('order');
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function tabs()
    {
        return $this->hasMany(CourseTab::class)->orderBy('order');
    }

    public function mailingList()
    {
        return $this->belongsTo(MailingList::class);
    }

    public function isEnrolledBy(User $user)
    {
        return $this->enrollments()->where('user_id', $user->id)->exists();
    }

    /**
     * Check if user has access to this course
     * Access is granted if:
     * - Course has no mailing list (free for all)
     * - User is a member of the course's mailing list
     */
    public function userHasAccess(User $user)
    {
        // If no mailing list, course is free for all
        if (!$this->mailing_list_id) {
            return true;
        }

        // Check if user is a member of the course's mailing list
        return $user->mailingLists()
            ->where('mailing_list_id', $this->mailing_list_id)
            ->wherePivot('status', 'active')
            ->exists();
    }
}
