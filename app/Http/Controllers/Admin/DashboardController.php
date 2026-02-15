<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::guard('web')->user();

        return view('admin.dashboard', [
            'videoCount' => $user->videos()->count(),
            'viewerCount' => $user->viewers()->count(),
            'commentCount' => $user->videos()->withCount('comments')->get()->sum('comments_count'),
        ]);
    }
}
