<?php

// app/Http/Controllers/Auth/LoginController.php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    

    public function showLoginForm()
{
    return view('auth.login'); // Return the login view
}

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle an authenticated user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
{
    // Set cookies for session and CSRF token
    $cookieSession = cookie('laravel_session', session()->getId(), 120, null, null, true, true);
    $cookieXSRF = cookie('XSRF-TOKEN', csrf_token(), 120, null, null, true, true);

    if ($user->role === 'pharmacy') {
        $pharmacy = $user->pharmacy;

        // Check if the pharmacy is approved
        if (!$pharmacy->is_approved) {
            // Check if documents are uploaded
            if ($pharmacy->document1_path && $pharmacy->document2_path && $pharmacy->document3_path) {
                // Check if the pharmacy is rejected
                if ($pharmacy->is_rejected) {
                    return redirect()->route('pharmacy.resubmitDocuments')->withCookies([$cookieSession, $cookieXSRF]);
                }
                // If not approved and not rejected, show not approved
                return redirect()->route('pharmacy.not-approved')->withCookies([$cookieSession, $cookieXSRF]);
            } else {
                return redirect()->route('pharmacy.upload_documents')->withCookies([$cookieSession, $cookieXSRF]);
            }
        }

        // Check if the user has selected a sub-role
        if (!$pharmacy->sub_role) {
            return redirect()->route('pharmacy.selectRole')->withCookies([$cookieSession, $cookieXSRF]);
        }

        // Redirect based on the selected sub-role
        return redirect()->route('pharmacy.dashboard')->withCookies([$cookieSession, $cookieXSRF]);
    }

    if ($user->role === 'customer') {
        return redirect()->route('customer.home')->withCookies([$cookieSession, $cookieXSRF]);
    }

    return redirect('/')->withCookies([$cookieSession, $cookieXSRF]);
}


    /**
     * Validate login request data.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:50'],
            'password' => ['required', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]*$/'], 
        ]);
    }

    /**
     * Handle a login attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validator($request->all())->validate();

        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            return $this->authenticated($request, Auth::user());
        }

        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout(Request $request)
    {
        $user = Auth::user();
        
        // Set sub_role to null before logging out
        if ($user->role === 'pharmacy') {
            $user->pharmacy->sub_role = null;
            $user->pharmacy->save();
        }
    
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect('/');
    }
}
