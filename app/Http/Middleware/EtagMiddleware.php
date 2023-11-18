<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EtagMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $response = $next($request);

        if ($response->status() == 200) {
            $etag = md5($response->getContent());

            // Set Etag header in the response
            $response->header('Etag', $etag);

            // Check If-None-Match header from the request
            $clientEtag = $request->header('If-None-Match');

            // If Etag matches, send a 304 Not Modified response
            if ($clientEtag && $clientEtag === $etag) {

                return response()->json(null, 304);
            }
        }

        return $response;
    }
}
