<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Comment extends Model
{
    protected $fillable = [
        'content',
        'viewer_id',
        'video_time',
        'video_id',
    ];

    protected $casts = [
        'video_time' => 'float',
    ];

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(Viewer::class);
    }

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }
}
