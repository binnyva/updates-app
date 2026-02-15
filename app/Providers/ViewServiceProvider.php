<?php

namespace App\Providers;

use App\Services\AuthService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(AuthService::class);
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $auth = app(AuthService::class);
            $view->with('currentUser', $auth->currentUser());
            $view->with('currentViewer', $auth->currentViewer());
            $view->with('actorType', $auth->actorType());
        });
    }
}
