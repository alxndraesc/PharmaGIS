<?php

// app/Http/Middleware/RoleMiddleware.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();

        if ($user->role!== $role) {
            if ($user->role === 'customer') {
                return redirect()->route('customer.home');
            } elseif ($user->role === 'pharmacy') {
                return redirect()->route('pharmacy.dashboard');
            } else {
                // Handle other roles or unauthorized access
                return redirect('/');
            }
        }

        return $next($request);
    }
}

