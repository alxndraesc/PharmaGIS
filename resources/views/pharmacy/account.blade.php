@extends('layouts.pharmacy')

@section('title', 'Account Management')

@section('content')
<div class="container mt-4">
    <h5>Account</h5>
    <hr>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow mb-4">
        <div class="card-body">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="account-info-tab" data-bs-toggle="tab" href="#account-info" role="tab" aria-controls="account-info" aria-selected="true">Account Information</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="change-password-tab" data-bs-toggle="tab" href="#change-password" role="tab" aria-controls="change-password" aria-selected="false">Change Password</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="delete-account-tab" data-bs-toggle="tab" href="#delete-account" role="tab" aria-controls="delete-account" aria-selected="false">Delete Account</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content mt-3">
                <!-- Account Information Tab -->
<div class="tab-pane fade show active" id="account-info" role="tabpanel" aria-labelledby="account-info-tab">
<div class="card">
<div class="card-body">
    <form method="POST" action="{{ route('pharmacy.updateAccount') }}">
        @csrf
        <div class="form-group mb-3">
            <label for="name">Pharmacy Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $pharmacy->name ?? '') }}" required>
        </div>
        
        <!-- Street input -->
        <div class="form-group mb-3">
            <label for="street">Street</label>
            <input type="text" class="form-control" id="street" name="street" value="{{ old('street') }}" placeholder="Enter your street" required>
        </div>
        
        <!-- Neighborhood (Barangay) input -->
        <div class="form-group mb-3">
            <label for="neighborhood">Barangay</label>
            <input type="text" class="form-control" id="neighborhood" name="neighborhood" value="{{ old('neighborhood') }}" placeholder="Enter your barangay" required>
        </div>

        <!-- The address will now display as 'Gubat, Sorsogon, Philippines' -->
        <div class="form-group mb-3">
            <label for="fixed_address">Municipality</label>
            <input type="text" class="form-control" id="fixed_address" value="Gubat, Sorsogon, Philippines" readonly>
        </div>

        <!-- Contact number input -->
        <div class="form-group mb-3">
            <label for="contact_number">Contact Number</label>
            <input type="text" class="form-control" id="contact_number" name="contact_number" value="{{ old('contact_number', $pharmacy->contact_number ?? '') }}" required>
        </div>

        <button type="submit" class="btn btn-info" style="background-color: #0eba9c; border-color: #0eba9c;">Update</button>
    </form>
</div>
</div>
</div>


                <!-- Change Password Tab -->
                <div class="tab-pane fade" id="change-password" role="tabpanel" aria-labelledby="change-password-tab">
                <div class="card">
                <div class="card-body">
                    <form method="POST" action="{{ route('pharmacy.changePassword') }}">
                        @csrf
                        <div class="form-group mb-3">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password">New Password</label>
                            <input type="password" class="form-control" id="new_password" name="new_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password_confirmation">Confirm New Password</label>
                            <input type="password" class="form-control" id="new_password_confirmation" name="new_password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-info" style="background-color: #0eba9c; border-color: #0eba9c;">Change Password</button>
                    </form>
                </div>
                </div>
                </div>

                <!-- Delete Account Tab -->
                <div class="tab-pane fade" id="delete-account" role="tabpanel" aria-labelledby="delete-account-tab">
                    <div class="card">
                        <div class="card-body">
                            <h5>Delete Your Account</h5>
                            <p class="text-danger">Warning: This action is irreversible. Please confirm your password to delete your account.</p>
                            <form method="POST" action="{{ route('pharmacy.account.delete') }}">
                                @csrf
                                <div class="form-group mb-3">
                                    <label for="password">Confirm Password</label>
                                    <input type="password" name="password" id="password" class="form-control" required>
                                    @error('password')
                                        <div class="text-danger">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-danger">Delete Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
