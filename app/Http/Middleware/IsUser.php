<?php

namespace App\Http\Middleware;

use Closure;

class IsUser
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
        if (auth()->user()->isUser()) {
            return $next($request);
        } else if (auth()->user()->isAdmin()) {
            return redirect('admin/dashboard');
        }
        return redirect('/');
    }
}
