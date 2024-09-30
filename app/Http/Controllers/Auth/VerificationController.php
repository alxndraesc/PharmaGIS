<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\VerifiesEmails;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class VerificationController extends Controller
{
    use VerifiesEmails;

    /**
     * Where to redirect users after verification.
     *
     * @var string
     */
    protected $redirectTo = '/home'; // Updated to redirect to /home

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    public function verify(Request $request)
    {
        Log::info('Verification process started');

        $user = User::find($request->route('id'));
        
        if (!$user || !hash_equals((string) $request->route('hash'), sha1($user->getEmailForVerification()))) {
            Log::warning('Invalid verification link for user ID: ' . $request->route('id'));
            return redirect()->route('login')->with('error', 'Invalid verification link.');
        }

        if ($user->hasVerifiedEmail()) {
            Log::info('User already verified: ' . $user->email);
            return redirect()->route('home')->with('status', 'Your email is already verified.');
        }

        // This is where you mark the email as verified.
        $user->markEmailAsVerified();
        
        Log::info('Email marked as verified for user ID: ' . $user->id);

        return redirect($this->redirectTo)->with('status', 'Your email has been successfully verified.');
    }

    /**
     * Handle the verified user redirection.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function verified(Request $request)
    {
        return redirect($this->redirectTo)->with('status', 'Your email has been verified.');
    }
}
