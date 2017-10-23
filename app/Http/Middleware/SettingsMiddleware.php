<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class SettingsMiddleware
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
        if ( Auth::user()->isDepLeader() || Auth::user()->isAdmin() )
        {
            return $next($request);
        }
        else
        {
            return redirect('/');
        }

    }
}
