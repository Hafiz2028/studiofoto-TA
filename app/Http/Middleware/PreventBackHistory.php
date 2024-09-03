<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class PreventBackHistory
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        // Log::info('PreventBackHistory middleware triggered.');
        return $response->header('Cache-Control','nocache,no-store,max-age=0,must-revalidate')
                        ->header('Pragma', 'no-cache')
                        ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');

    }
}
