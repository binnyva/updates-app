<?php

use App\Http\Controllers\GoogleController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ViewerController;
use App\Http\Controllers\Admin\VideoController as AdminVideoController;
use App\Http\Controllers\UpdateController;
use App\Http\Controllers\VideoStreamController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Auth routes
Route::get('/login', fn () => view('auth.login'))->name('login');
Route::get('/adm/login', fn () => view('auth.admin-login'))->name('admin.login');
Route::get('/auth/google/redirect', [GoogleController::class, 'redirectToGoogle']);
Route::get('/google-callback', [GoogleController::class, 'handleGoogleCallback']);

Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    Auth::guard('viewer')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// Public routes (viewers + users)
Route::middleware('auth.any')->group(function () {
    Route::get('/', [UpdateController::class, 'index']);
    Route::get('/update/{video}', [UpdateController::class, 'show']);
    Route::post('/update/{video}/comment', [UpdateController::class, 'storeComment']);
    Route::post('/update/{video}/view', [UpdateController::class, 'trackView']);
    Route::get('/video/{video}/stream', [VideoStreamController::class, 'stream']);
    Route::get('/video/{video}/subtitles', [VideoStreamController::class, 'subtitles']);
});

// Admin routes (users only)
Route::prefix('adm')->middleware(['auth.user'])->group(function () {
    Route::get('/', [DashboardController::class, 'index']);

    // Super admin only
    Route::resource('users', UserController::class)->middleware('auth.superadmin');

    Route::resource('viewers', ViewerController::class);
    Route::get('videos/server-files', [AdminVideoController::class, 'serverFiles']);
    Route::get('videos/{video}/stats', [AdminVideoController::class, 'stats']);
    Route::resource('videos', AdminVideoController::class);
});
