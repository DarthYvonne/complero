<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_id',
        'accessed_at',
        'payment_id',
    ];

    protected $casts = [
        'accessed_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
