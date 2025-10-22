<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Lesson extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'title',
        'slug',
        'video_path',
        'content',
        'order',
        'duration_minutes',
    ];

    protected $casts = [
        'order' => 'integer',
        'duration_minutes' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($lesson) {
            if (empty($lesson->slug)) {
                $lesson->slug = Str::slug($lesson->title);
            }

            // Auto-assign order if not set
            if (empty($lesson->order)) {
                $maxOrder = static::where('course_id', $lesson->course_id)->max('order');
                $lesson->order = ($maxOrder ?? 0) + 1;
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

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function files()
    {
        return $this->hasMany(LessonFile::class);
    }

    public function tabs()
    {
        return $this->hasMany(LessonTab::class)->orderBy('order');
    }

    public function completions()
    {
        return $this->hasMany(LessonCompletion::class);
    }

    public function isCompletedBy(User $user)
    {
        return $this->completions()->where('user_id', $user->id)->exists();
    }
}
