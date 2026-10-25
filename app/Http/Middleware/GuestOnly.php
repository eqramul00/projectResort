<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class GuestOnly
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        if (!$request->user()) {
            return redirect()->guest(route('guest.login'));
        }

        abort_unless(
            $request->user()->isGuest(),
            403,
            'This page is available to guest accounts only.'
        );

        return $next($request);
    }
}