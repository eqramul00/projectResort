<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StaffOnly
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $user = $request->user();

        abort_unless(
            $user
                && $user->hasAnyRole([
                    'admin',
                    'manager',
                    'reception',
                ]),
            403,
            'This page is available to hotel staff only.'
        );

        return $next($request);
    }
}