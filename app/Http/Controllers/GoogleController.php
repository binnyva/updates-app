<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Viewer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle(Request $request)
    {
        $authType = $request->query('type', 'viewer');
        session(['auth_type' => $authType]);

        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(Request $request)
    {
        $googleUser = Socialite::driver('google')->user();
        $authType = session('auth_type', 'viewer');
        session()->forget('auth_type');

        if ($authType === 'user') {
            return $this->handleUserLogin($googleUser);
        }

        return $this->handleViewerLogin($googleUser);
    }

    private function handleUserLogin($googleUser)
    {
        $user = User::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$user) {
            return redirect('/adm/login')
                ->with('error', 'You are not registered as a user. Please contact an administrator.');
        }

        $user->update([
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
            'avatar' => $googleUser->getAvatar(),
        ]);

        Auth::guard('web')->login($user, true);

        return redirect('/adm');
    }

    private function handleViewerLogin($googleUser)
    {
        $viewer = Viewer::where('google_id', $googleUser->getId())
            ->orWhere('email', $googleUser->getEmail())
            ->first();

        if (!$viewer) {
            return redirect('/login')
                ->with('error', 'You are not registered as a viewer. Please contact the person who shared their updates with you.');
        }

        $viewer->update([
            'google_id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
            'avatar' => $googleUser->getAvatar(),
            'last_login_on' => now(),
        ]);

        Auth::guard('viewer')->login($viewer, true);

        return redirect('/');
    }
}
