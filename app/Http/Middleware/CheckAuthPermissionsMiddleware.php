<?php

namespace App\Http\Middleware;

use App\Enums\UserStatusEnum;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAuthPermissionsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ( ! auth()->check()) {
            Auth::logout();
            // User is not authenticated, redirect to the login page
            return redirect()->route('nova.pages.login')->with('status', 'Please log in to access this page.');
        }

        if (auth()->user()->status !== UserStatusEnum::ACTIVE) {
            Auth::logout();

            return redirect()->route('nova.pages.login')->with('status', 'Please log in to access this page.');
        }

        return $next($request);
    }
}
