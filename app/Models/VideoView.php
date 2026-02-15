<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VideoView extends Model
{
    protected $fillable = [
        'video_id',
        'viewer_id',
        'video_view_time',
    ];

    protected $casts = [
        'video_view_time' => 'float',
    ];

    public function video(): BelongsTo
    {
        return $this->belongsTo(Video::class);
    }

    public function viewer(): BelongsTo
    {
        return $this->belongsTo(Viewer::class);
    }
}
