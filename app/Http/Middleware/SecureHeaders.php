<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecureHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Security headers
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy - Relaxed for local dev and specific CDNs
        $csp = "default-src 'self' https:; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:; ";
        $csp .= "style-src 'self' 'unsafe-inline' https: http:; ";
        $csp .= "img-src 'self' data: https: blob: http:; ";
        $csp .= "font-src 'self' data: https: http:; ";
        $csp .= "frame-src 'self' https:; ";
        $csp .= "connect-src 'self' ws: wss: https: http:; ";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
