<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminPanel
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->role) {
            if (in_array(Auth::user()->role->role, ['admin', 'moderator'])) {
                return $next($request);
            }
        }
        return redirect()->route('admin.login.show');
    }
}
