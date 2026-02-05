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
        // Security headers
        // We remove X-Frame-Options to allow it to be controlled by Content-Security-Policy (frame-ancestors)
        // This allows our dashboard to iframe the preview websites.
        // $response->headers->set('X-Frame-Options', 'SAMEORIGIN'); 
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Content Security Policy
        $csp = "default-src 'self' https:; ";
        $csp .= "script-src 'self' 'unsafe-inline' 'unsafe-eval' https: http:; ";
        $csp .= "style-src 'self' 'unsafe-inline' https: http:; ";
        $csp .= "img-src 'self' data: https: blob: http:; ";
        $csp .= "font-src 'self' data: https: http:; ";
        $csp .= "frame-src 'self' https:; ";
        $csp .= "connect-src 'self' ws: wss: https: http:; ";

        // Allow the main app and preview domains to iframe the site
        $centralDomain = parse_url(config('app.url'), PHP_URL_HOST);
        $previewDomain = config('tenancy.preview_domain');
        $csp .= "frame-ancestors 'self' https://{$centralDomain} https://*.{$centralDomain} https://{$previewDomain} https://*.{$previewDomain}; ";

        $response->headers->set('Content-Security-Policy', $csp);

        return $response;
    }
}
