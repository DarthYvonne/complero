<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ResourceFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'resource_id',
        'filename',
        'file_path',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function resource()
    {
        return $this->belongsTo(Resource::class);
    }
}
