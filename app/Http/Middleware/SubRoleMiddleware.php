<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  ...$subRoles
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$subRoles)
{
    if (Auth::check() && Auth::user()->role === 'pharmacy') {
        $userSubRole = Auth::user()->pharmacy->sub_role;

        if ($userSubRole === null || !in_array($userSubRole, $subRoles)) {
            return redirect()->route('pharmacy.select-sub-role'); // Redirect to the selection page if sub_role is null or not valid
        }
    }

    return $next($request);
}
}
