<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = session()->get('locale', config('app.locale'));

        // If a user is logged in via any guard, prefer their saved locale if it exists
        $guards = ['web', 'securegate', 'investor', 'customer'];
        foreach ($guards as $guard) {
            $user = auth()->guard($guard)->user();
            if ($user && isset($user->locale) && $user->locale) {
                $locale = $user->locale;
                break;
            }
        }

        App::setLocale($locale);

        // Also set Carbon locale for date handling
        if (class_exists(\Carbon\Carbon::class)) {
            \Carbon\Carbon::setLocale($locale);
        }

        return $next($request);
    }
}
