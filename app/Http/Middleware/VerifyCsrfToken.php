<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];

    public function handle($request, Closure $next)
{
    if ($request->isMethod('post') && !$request->session()->token()) {
        \Log::info('CSRF Token not found in session.');
    }

    return parent::handle($request, $next);
}

}
