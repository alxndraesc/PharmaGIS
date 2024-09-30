<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified;
use App\Models\User;
use App\Models\Pharmacy;
use Illuminate\Support\Facades\Session;

class EmailVerificationListener
{
    /**
     * Handle the event.
     *
     * @param  Verified  $event
     * @return void
     */
    public function handle(Verified $event)
    {
        // Get the verified user
        $user = $event->user;

        // Check if session data exists
        if (Session::has('account_name') && Session::has('email')) {
            // Update the user status to verified
            $user->update([
                'is_verified' => true,
            ]);

            // Insert pharmacy details
            Pharmacy::create([
                'user_id' => $user->id,
                'name' => session('pharmacy_name'),
                'contact_number' => session('contact_number'),
                'address' => session('street') . ', ' . session('neighborhood') . ', Gubat, Sorsogon',
                'document1_path' => session('document1_path'),
                'document2_path' => session('document2_path'),
                'document3_path' => session('document3_path'),
                'is_approved' => false,
            ]);

            // Clear session after data is saved
            session()->forget([
                'account_name',
                'email',
                'password',
                'pharmacy_name',
                'street',
                'neighborhood',
                'contact_number',
                'document1_path',
                'document2_path',
                'document3_path',
            ]);
        }
    }
}
