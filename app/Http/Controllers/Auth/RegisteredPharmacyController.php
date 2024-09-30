<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth; 
use GuzzleHttp\Client;
use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Storage;
use App\Rules\NoSpecialCharacters;
use Illuminate\Support\Facades\Http; 
use App\Mail\PharmacyEmailVerificationMail;


class RegisteredPharmacyController extends Controller
{
    public function showStep1Form()
{
    return view('auth.register-pharmacy-basic');
}

public function registerStep1(Request $request)
{
    $this->validate($request, [
        'account_name' => ['required', 'string', 'max:30'],
        'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
        'password' => ['required', 'string', 'min:8', 'confirmed', new NoSpecialCharacters],
    ]);

    // Store user details in the session
    session([
        'account_name' => htmlspecialchars($request->account_name, ENT_QUOTES, 'UTF-8'),
        'email' => filter_var($request->email, FILTER_SANITIZE_EMAIL),
        'password' => Hash::make($request->password), // Storing hashed password in session
    ]);

    return redirect()->route('pharmacy.register.step2');
}

public function showStep2Form()
{
    return view('auth.register-pharmacy-address');
}

public function registerStep2(Request $request)
{
    $this->validate($request, [
        'pharmacy_name' => ['required', 'string', 'max:50'],
        'street' => ['required', 'string'],
        'neighborhood' => ['required', 'string'],
        'contact_number' => ['required', 'string', 'digits_between:11,15', 'unique:pharmacies,contact_number'],
    ]);

    $fullAddress = htmlspecialchars($request->street, ENT_QUOTES, 'UTF-8') . ', ' .
                   htmlspecialchars($request->neighborhood, ENT_QUOTES, 'UTF-8') . ', Gubat, Sorsogon, Philippines';

    // Geocode the address to get latitude and longitude using Mapbox
    $coordinates = $this->geocodeAddressWithMapbox($fullAddress);

    if (!$coordinates) {
        return redirect()->back()->withErrors(['geocode' => 'Unable to retrieve coordinates. Please try again.']);
    }

    // Store pharmacy details in the session or directly in the database
    session([
        'pharmacy_name' => htmlspecialchars($request->pharmacy_name, ENT_QUOTES, 'UTF-8'),
        'address' => $fullAddress,
        'contact_number' => htmlspecialchars($request->contact_number, ENT_QUOTES, 'UTF-8'),
        'latitude' => $coordinates['latitude'],
        'longitude' => $coordinates['longitude'],
    ]);

    // Create user and pharmacy in the database
    $user = User::create([
        'name' => session('account_name'),
        'email' => session('email'),
        'password' => session('password'),
        'role' => 'pharmacy',
        'is_verified' => false,
        'is_approved' => false,
    ]);

    Pharmacy::create([
        'user_id' => $user->id,
        'name' => session('pharmacy_name'),
        'contact_number' => session('contact_number'),
        'address' => session('address'),
        'latitude' => session('latitude'),
        'longitude' => session('longitude'),
        'is_approved' => false, // Mark as pending approval
    ]);

    // Send email verification
    \Mail::to($user->email)->send(new PharmacyEmailVerificationMail($user));

    return redirect()->route('verification.notice');
}

protected function geocodeAddressWithMapbox($address)
{
    $client = new \GuzzleHttp\Client();
    $accessToken = 'pk.eyJ1IjoiYWx4bmRyYWUiLCJhIjoiY20wM2drNGNhMDd1cjJqcHhzbTV4NXdlaCJ9.KLPS-IT0Y9_IGEwqBRn00A'; // Replace with your Mapbox access token

    try {
        $response = $client->get('https://api.mapbox.com/geocoding/v5/mapbox.places/' . urlencode($address) . '.json', [
            'query' => [
                'access_token' => $accessToken,
                'limit' => 1
            ]
        ]);

        $data = json_decode($response->getBody(), true);

        if (!empty($data['features'])) {
            return [
                'latitude' => $data['features'][0]['geometry']['coordinates'][1],
                'longitude' => $data['features'][0]['geometry']['coordinates'][0]
            ];
        }

        return null;

    } catch (\Exception $e) {
        \Log::error('Geocoding error: ' . $e->getMessage());
        return null;
    }
}
public function showUploadDocumentsForm()
{
    return view('pharmacy.upload-documents');
}

public function uploadDocuments(Request $request)
{
    $this->validate($request, [
        'document1' => ['required', 'file', 'mimes:pdf,png,jpeg,jpg', 'max:5120'],
        'document2' => ['required', 'file', 'mimes:pdf,png,jpeg,jpg', 'max:5120'],
        'document3' => ['required', 'file', 'mimes:pdf,png,jpeg,jpg', 'max:5120'],
    ]);

    $user = Auth::user();
    $pharmacy = $user->pharmacy;

    // Store documents in public storage
    $pharmacy->document1_path = $request->file('document1')->store('documents', 'public');
    $pharmacy->document2_path = $request->file('document2')->store('documents', 'public');
    $pharmacy->document3_path = $request->file('document3')->store('documents', 'public');

    $pharmacy->is_approved = false; // Mark as pending approval
    $pharmacy->save();

    return redirect()->route('pharmacy.not-approved')->with('success', 'Documents uploaded successfully. Your registration is pending approval.');
}
}
