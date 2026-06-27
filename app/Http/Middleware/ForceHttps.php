<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Symfony\Component\HttpFoundation\Response;

class ForceHttps
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->getScheme() === 'https') {
            URL::forceRootUrl($request->getScheme() . '://' . $request->getHttpHost());
        }

        return $next($request);
    }
}
