<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;

class LocaleSetting
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
        if ($request->has('locale')) {
            App::setLocale($request->input('locale', 'en'));
        } elseif (Auth::check()) {
            App::setLocale(data_get($request->user(), 'locale', 'en'));
        }
        unset($request['locale']);

        return $next($request);
    }
}
