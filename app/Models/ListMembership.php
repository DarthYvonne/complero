<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ListMembership extends Pivot
{
    /**
     * The table associated with the pivot model.
     */
    protected $table = 'list_memberships';

    /**
     * Indicates if the IDs are auto-incrementing.
     */
    public $incrementing = true;

    /**
     * The attributes that should be cast.
     */
    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
}
