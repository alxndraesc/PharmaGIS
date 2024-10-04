<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CookieController extends Controller
{
    // Set a cookie
    public function setCookie(Request $request)
    {
        return response('Cookie Set')->cookie('laravel_session', 'your_value', 120); // 120 minutes lifetime
    }

    // Get a cookie
    public function getCookie(Request $request)
    {
        $value = $request->cookie('laravel_session');
        return response()->json(['cookie_value' => $value]);
    }

    // Delete a cookie
    public function deleteCookie()
    {
        return response('Cookie Deleted')->withCookie(Cookie::forget('laravel_session'));
    }
}
