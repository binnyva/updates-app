<?php

namespace App\Services;

use App\Models\User;
use App\Models\Viewer;
use Illuminate\Support\Facades\Auth;

class AuthService
{
    public function currentUser(): ?User
    {
        return Auth::guard('web')->user();
    }

    public function currentViewer(): ?Viewer
    {
        return Auth::guard('viewer')->user();
    }

    public function isUser(): bool
    {
        return Auth::guard('web')->check();
    }

    public function isViewer(): bool
    {
        return Auth::guard('viewer')->check();
    }

    public function isAuthenticated(): bool
    {
        return $this->isUser() || $this->isViewer();
    }

    public function actorType(): ?string
    {
        if ($this->isUser()) return 'user';
        if ($this->isViewer()) return 'viewer';
        return null;
    }
}
