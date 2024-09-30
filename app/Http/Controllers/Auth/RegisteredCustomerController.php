<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use App\Rules\NoSpecialCharacters;

class RegisteredCustomerController extends Controller
{
    public function create()
    {
        return view('auth.register-customer');
    }

    public function store(Request $request)
{
    $this->validator($request->all())->validate();

    $user = $this->createCustomer($request->all());

    event(new Registered($user));

    auth()->login($user);

    return redirect()->route('verification.notice');
}

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:30'],
            'email' => ['required', 'string', 'email', 'max:50', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed', new NoSpecialCharacters ],
        ]);
    }

    protected function createCustomer(array $data)
{
    return User::create([
        'name' => filter_var($data['name'], FILTER_SANITIZE_STRING), // Sanitize name
        'email' => filter_var($data['email'], FILTER_SANITIZE_EMAIL), // Sanitize email
        'password' => Hash::make($data['password']),
        'role' => 'customer',
    ]);
}
}