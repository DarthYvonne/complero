<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LessonFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'lesson_id',
        'filename',
        'file_path',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'file_size' => 'integer',
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
}
