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
    if ($user->role === 'pharmacy') {
        $pharmacy = $user->pharmacy;

        // Check if the pharmacy is approved
        if (!$pharmacy->is_approved) {
            // Check if documents are uploaded
            if ($pharmacy->document1_path && $pharmacy->document2_path && $pharmacy->document3_path) {
                
                // Check if the pharmacy is rejected
                if ($pharmacy->is_rejected) {
                    return redirect()->route('pharmacy.resubmitDocuments'); // Redirect to resubmit documents
                }

                // If not approved and not rejected, show not approved
                return redirect()->route('pharmacy.not-approved'); // Redirect to not approved
            } else {
                return redirect()->route('pharmacy.upload_documents'); // Redirect to upload documents if not uploaded
            }
        }

        // Check if the user has selected a sub-role
        if (!$pharmacy->sub_role) {
            return redirect()->route('pharmacy.selectRole'); // Redirect to the sub-role selection page
        }

        // Redirect based on the selected sub-role
        return redirect()->route('pharmacy.dashboard'); // Assuming both sub-roles redirect here
    }

    if ($user->role === 'customer') {
        return redirect()->route('customer.home'); // Adjust this as needed for customers
    }

    return redirect('/');
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
            'password' => ['required', 'string', 'min:8', 'regex:/^[a-zA-Z0-9]*$/'], // No special characters
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
