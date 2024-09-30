@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('images/img_main.png') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow" style="border-radius: 0.5rem;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h3>Pharmacy Registration - Step 2</h3>
                                <p>Please fill in the address details.</p>
                            </div>
                            <div class="card-body">
                                <!-- Form for Step 2 -->
                                <form method="POST" action="{{ route('pharmacy.register.step2') }}">
                                    @csrf

                                    <!-- Pharmacy Name -->
                                    <div class="form-group mb-3">
                                        <label for="pharmacy_name">Pharmacy Name</label>
                                        <input id="pharmacy_name" type="text" class="form-control @error('pharmacy_name') is-invalid @enderror" name="pharmacy_name" value="{{ old('pharmacy_name') }}" required>
                                        @error('pharmacy_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Contact Number -->
                                    <div class="form-group mb-3">
                                        <label for="contact_number">Contact Number</label>
                                        <input id="contact_number" type="text" class="form-control @error('contact_number') is-invalid @enderror" name="contact_number" required oninput="validateContactNumber(this)">
                                        @error('contact_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div id="contact_error" class="text-danger" style="display: none;"></div>
                                    </div>

                                    <!-- Address Fields -->
                                    <div class="form-group mb-3">
                                        <label for="street">Street</label>
                                        <input id="street" type="text" class="form-control @error('street') is-invalid @enderror" name="street" value="{{ old('street') }}" required>
                                        @error('street')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="neighborhood">Barangay</label>
                                        <input id="neighborhood" type="text" class="form-control @error('neighborhood') is-invalid @enderror" name="neighborhood" value="{{ old('neighborhood') }}" required>
                                        @error('neighborhood')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group text-center mb-0">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Submit
                                        </button>
                                    </div>
                                </form>
                            </div> 
                        </div> 
                    </div> 
                </div> 
            </div> 
        </div> 
    </div> 
</div>

<script>
    function validateContactNumber(input) {
        const value = input.value;
        const regex = /^\d{11,}$/; // Regex for at least 11 digits
        const errorMessage = document.getElementById('contact_error');

        // Check for letters in the input
        if (/[a-zA-Z]/.test(value)) {
            errorMessage.textContent = 'Only numbers are allowed.';
            errorMessage.style.display = 'block'; // Show error message
            input.classList.add('is-invalid'); // Add invalid class
        } else if (!regex.test(value)) {
            errorMessage.textContent = 'Contact number must be at least 11 digits long.';
            errorMessage.style.display = 'block'; // Show error message
            input.classList.add('is-invalid'); // Add invalid class
        } else {
            errorMessage.style.display = 'none'; // Hide error message if valid
            input.classList.remove('is-invalid'); // Remove invalid class
        }
    }

    // Validate contact number before form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        const input = document.getElementById('contact_number');
        const regex = /^\d{11,}$/;
        const errorMessage = document.getElementById('contact_error');

        // Check for letters in the input
        if (/[a-zA-Z]/.test(input.value)) {
            errorMessage.textContent = 'Only numbers are allowed.';
            errorMessage.style.display = 'block'; // Show error message
            input.classList.add('is-invalid'); // Add invalid class
            event.preventDefault(); // Prevent form submission
        } else if (!regex.test(input.value)) {
            errorMessage.textContent = 'Contact number must be at least 11 digits long.';
            errorMessage.style.display = 'block'; // Show error message
            input.classList.add('is-invalid'); // Add invalid class
            event.preventDefault(); // Prevent form submission
        } else {
            errorMessage.style.display = 'none'; // Hide error message if valid
            input.classList.remove('is-invalid'); // Remove invalid class
        }
    });
</script>
    </div> 
</div> 
@endsection
