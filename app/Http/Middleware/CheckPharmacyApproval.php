<?php

// app/Http/Middleware/CheckPharmacyApproval.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CheckPharmacyApproval
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role == 'pharmacy') {
            $pharmacy = Auth::user()->pharmacy;
            Log::info('Checking pharmacy approval', ['user_id' => Auth::user()->id, 'is_approved' => $pharmacy->is_approved]);
            
            if (!$pharmacy->is_approved) {
                Log::warning('Pharmacy not approved', ['user_id' => Auth::user()->id]);
                return redirect()->route('pharmacy.notApproved');
            }
        }

        return $next($request);
    }
}
