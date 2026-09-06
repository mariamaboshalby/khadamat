<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * HttpCacheHeaders Middleware
 *
 * Adds appropriate Cache-Control headers to public (unauthenticated) pages
 * and aggressive cache headers to static assets.
 *
 * Strategy:
 * - Public pages (/, /services, /offers, /technicians): cache 5 minutes,
 *   stale-while-revalidate 60 seconds. This allows CDN and browser caching
 *   while ensuring content stays reasonably fresh.
 * - Auth pages: no-store (never cache user-specific pages).
 * - Static assets (handled by web server ideally, but as a fallback): 1 year.
 */
class HttpCacheHeaders
{
    /**
     * Routes that are public and can be cached by browsers/CDN.
     * These must not contain user-specific content in the HTML.
     */
    private const PUBLIC_ROUTES = [
        'home',
        'services.index',
        'service.show',
        'offers.index',
        'technicians.index',
        'technician.profile',
    ];

    /**
     * TTL in seconds for public pages.
     */
    private const PUBLIC_TTL = 300; // 5 minutes

    /**
     * Stale-while-revalidate window in seconds.
     */
    private const SWR = 60;

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only apply to GET/HEAD — never to POST/PUT/DELETE
        if (!$request->isMethod('GET') && !$request->isMethod('HEAD')) {
            return $response;
        }

        // Never cache responses with errors
        if ($response->getStatusCode() >= 400) {
            return $response;
        }

        // Auth pages — must never be cached
        if ($request->user()) {
            $response->headers->set(
                'Cache-Control',
                'no-store, no-cache, must-revalidate, private'
            );
            return $response;
        }

        // Check if this is a public cacheable route
        $routeName = $request->route()?->getName();
        if ($routeName && in_array($routeName, self::PUBLIC_ROUTES, true)) {
            $response->headers->set(
                'Cache-Control',
                sprintf(
                    'public, max-age=%d, stale-while-revalidate=%d',
                    self::PUBLIC_TTL,
                    self::SWR
                )
            );

            // Add Vary header so caches differentiate by Accept-Encoding
            $response->headers->set('Vary', 'Accept-Encoding');

            return $response;
        }

        // Default: conservative no-cache for any other unauthenticated page
        $response->headers->set('Cache-Control', 'no-cache, no-store, must-revalidate');

        return $response;
    }
}
