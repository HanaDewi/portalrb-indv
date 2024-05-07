<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetAppUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHttpHost();

        // Check if the request's domain is portalrb.menpan.go.id
        if ($host === 'portalrb.menpan.go.id') {
            config(['app.url' => 'https://' . $host]);
        } else {
            // Default to portalrb.id for other domains
            config(['app.url' => 'https://portalrb.id']);
        }

        return $next($request);
    }
}
