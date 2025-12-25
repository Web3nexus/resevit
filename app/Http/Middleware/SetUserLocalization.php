<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetUserLocalization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasSession() && auth()->check()) {
            $timezone = auth()->user()->timezone;
            $locale = auth()->user()->locale;
        } elseif (function_exists('tenant') && tenant()) {
            $timezone = tenant()->timezone;
            $locale = tenant()->locale ?? config('app.locale');
        }

        if (isset($locale)) {
            app()->setLocale($locale);
        }

        if (isset($timezone) && $timezone) {
            date_default_timezone_set($timezone);
            config(['app.timezone' => $timezone]);
        }

        return $next($request);
    }
}

