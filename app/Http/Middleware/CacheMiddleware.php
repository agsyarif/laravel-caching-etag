<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = 'user_' . $request->url();

        if (Cache::has($key)) {
            return response(Cache::get($key));
        }

        $response = $next($request);

        // Cache the response for 10 minutes (adjust as needed)
        Cache::put($key, $response->getContent(), 60);

        return $response;
    }
}
