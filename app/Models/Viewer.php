<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Viewer extends Authenticatable
{
    protected $fillable = [
        'name',
        'email',
        'google_id',
        'avatar',
        'last_login_on',
    ];

    protected $casts = [
        'last_login_on' => 'datetime',
    ];

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_viewer')
            ->withPivot('level')
            ->withTimestamps();
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function videoViews(): HasMany
    {
        return $this->hasMany(VideoView::class);
    }
}
