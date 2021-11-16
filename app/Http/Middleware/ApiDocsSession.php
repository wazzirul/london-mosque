<?php

namespace App\Http\Middleware;

use Closure;

class ApiDocsSession
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
        if (!$request->session()->exists('login')) {
            // user value cannot be found in session
            return redirect('/login')->with('alert','Kamu harus login dulu');
        }
        return $next($request);
    }
}
