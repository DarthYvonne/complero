<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'causer_id',
        'type',
        'description',
        'subject_type',
        'subject_id',
        'properties',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * The user affected by this activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * The user who caused this activity
     */
    public function causer()
    {
        return $this->belongsTo(User::class, 'causer_id');
    }

    /**
     * The subject of the activity (polymorphic)
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Log an activity
     */
    public static function log(string $type, string $description, $subject = null, $user = null, $causer = null, array $properties = [])
    {
        return self::create([
            'type' => $type,
            'description' => $description,
            'user_id' => $user ? $user->id : null,
            'causer_id' => $causer ? $causer->id : ($user ? $user->id : auth()->id()),
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'properties' => $properties,
        ]);
    }
}
