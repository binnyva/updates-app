<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureAuthenticated
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::guard('web')->check() || Auth::guard('viewer')->check()) {
            return $next($request);
        }

        if ($request->is('adm/*') || $request->is('adm')) {
            return redirect('/adm/login');
        }

        session(['url.intended' => $request->fullUrl()]);

        return redirect('/login');
    }
}
