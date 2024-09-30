<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter as FacadeRateLimiter;
use App\Notifications\PharmacyApproved;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PharmacyRejectedNotification;
use App\Models\Pharmacy;
use App\Models\User;

class AdminController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.login');
    }

    public function login(Request $request)
    {
        // Validate the incoming request
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6', // Adjust the min length as needed
        ]);
    
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
    
        // Use rate limiting to prevent brute-force attacks
        $limiter = FacadeRateLimiter::attempts('login:' . $request->ip());
    
        if ($limiter >= 5) {
            return back()->withErrors(['email' => 'Too many login attempts. Please try again later.']);
        }
    
        $credentials = $request->only('email', 'password');
    
        if (Auth::attempt($credentials)) {
            // Check if user is admin
            if (Auth::user()->role == 'admin') {
                // Clear the rate limit on successful login
                FacadeRateLimiter::clear('login:' . $request->ip());
                return redirect()->route('admin.dashboard');
            } else {
                // Log out the user if not an admin
                Auth::logout();
                return back()->withErrors(['email' => 'Invalid credentials or not an admin.']);
            }
        }
    
        // Increment the failed login attempt
        FacadeRateLimiter::hit('login:' . $request->ip());
        return back()->withErrors(['email' => 'Invalid credentials or not an admin.']);
    }

    public function dashboard()
{
    // Pharmacy and User counts
    $pharmacyRequestsCount = Pharmacy::where('is_approved', false)->count();
    $approvedPharmaciesCount = Pharmacy::where('is_approved', true)->count();
    $totalRegisteredAccounts = User::whereHas('pharmacy', function ($query) {
        $query->where('is_approved', true);
    })->orWhere('role', 'customer')->count();

    // Get start and end of current month
    $currentMonthStart = now()->startOfMonth();
    $currentMonthEnd = now()->endOfMonth();

    // Get start and end of last month
    $lastMonthStart = now()->subMonth()->startOfMonth();
    $lastMonthEnd = now()->subMonth()->endOfMonth();

    // Customer counts for last month and this month
    $lastMonthCustomerCount = User::where('role', 'customer')
        ->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->count();

    $thisMonthCustomerCount = User::where('role', 'customer')
        ->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
        ->count();

    // Pharmacy counts for last month and this month
    $lastMonthPharmacyCount = User::whereHas('pharmacy', function ($query) {
        $query->where('is_approved', true);
    })->whereBetween('created_at', [$lastMonthStart, $lastMonthEnd])
        ->count();

    $thisMonthPharmacyCount = User::whereHas('pharmacy', function ($query) {
        $query->where('is_approved', true);
    })->whereBetween('created_at', [$currentMonthStart, $currentMonthEnd])
        ->count();

    // Return to the dashboard view with updated counts
    return view('admin.dashboard', compact(
        'pharmacyRequestsCount',
        'approvedPharmaciesCount',
        'totalRegisteredAccounts',
        'lastMonthCustomerCount',
        'thisMonthCustomerCount',
        'lastMonthPharmacyCount',
        'thisMonthPharmacyCount'
    ));
}


    public function logout()
    {
        Auth::logout();
        return redirect()->route('admin.login');
    }

    public function pharmacyRequests()
{
    $pharmacies = Pharmacy::where('is_approved', false)
                          ->whereNull('reviewed_at')  // Only fetch pharmacies that haven't been reviewed yet
                          ->get();
                          
    return view('admin.pharmacyRequests', compact('pharmacies'));
}


    public function approvePharmacy($id)
{

    $pharmacy = Pharmacy::find($id);

    if (!$pharmacy) {
        return redirect()->route('admin.pharmacyRequests')->with('error', 'Pharmacy not found.');
    }

    $pharmacy->is_approved = true;
    $pharmacy->save();

    $user = User::where('id', $pharmacy->user_id)->first();

    if ($user) {
        
        Notification::send($user, new PharmacyApproved($user));
    }

    return redirect()->route('admin.pharmacyRequests')->with('success', 'Pharmacy approved successfully and notification sent.');
}

public function rejectPharmacy($id)
{
    $pharmacy = Pharmacy::find($id);
    $pharmacy->is_approved = false;
    $pharmacy->reviewed_at = now();
    $pharmacy->save();

    Notification::route('mail', $pharmacy->user->email)
            ->notify(new PharmacyRejectedNotification($pharmacy));

    return redirect()->route('admin.pharmacyRequests')->with('success', 'Pharmacy rejected successfully and notified via email.');
}

    public function showDocument($id, $document)
{
    $pharmacy = Pharmacy::findOrFail($id);

    $documentPath = $pharmacy->{$document . '_path'};

    $fullPath = storage_path('app/public/documents/' . $documentPath);

    if (!file_exists($fullPath)) {
        abort(404, 'File not found.');
    }

    return response()->file($fullPath);
}
    public function locationManagement()
    {
        $pharmacies = Pharmacy::where('is_approved', true)->get(['name', 'address', 'latitude', 'longitude']);
        return view('admin.locations', compact('pharmacies'));
    }

    public function userManagement()
{
    $customers = User::where('role', 'customer')->get();

    $pharmacies = User::where('role', 'pharmacy')
        ->with('pharmacy')
        ->get();

    return view('admin.users', compact('customers', 'pharmacies'));
}


    public function settings()
    {
        return view('admin.settings');
    }
}