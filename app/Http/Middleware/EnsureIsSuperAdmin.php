<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureIsSuperAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::guard('web')->user();

        if (!$user || !$user->is_super_admin) {
            abort(403, 'Unauthorized. Super admin access required.');
        }

        return $next($request);
    }
}
