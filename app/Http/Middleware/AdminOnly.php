<?php

namespace App\Http\Middleware;
use App\Models\User;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

if (!$user || $user->role !== 'admin') {
    abort(403);
}
        return $next($request);
    }

    
}