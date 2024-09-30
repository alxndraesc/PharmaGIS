@extends('layouts.pharmacy')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-5 order-md-2">
                <img src="{{ asset('images/docs.png') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-10"> 
                        <div class="card shadow" style="border-radius: 0.5rem; width: 100%;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h3>Pharmacy Registration - Document Upload</h3>
                                <p>Please upload the necessary documents to proceed.</p>
                            </div>
                            <div class="card-body">
                                <!-- Form for Document Upload -->
                                <form method="POST" action="{{ route('pharmacy.upload_documents.submit') }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Document Uploads -->
                                    <div class="form-group mb-3">
                                        <label for="document1">BIR License (PDF, PNG, JPEG)</label>
                                        <input id="document1" type="file" class="form-control @error('document1') is-invalid @enderror" name="document1" required onchange="validateFile(this)">
                                        @error('document1')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div id="document1_error" class="text-danger" style="display: none;"></div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="document2">FDA License (PDF, PNG, JPEG)</label>
                                        <input id="document2" type="file" class="form-control @error('document2') is-invalid @enderror" name="document2" required onchange="validateFile(this)">
                                        @error('document2')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div id="document2_error" class="text-danger" style="display: none;"></div>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="document3">Business Permit (PDF, PNG, JPEG)</label>
                                        <input id="document3" type="file" class="form-control @error('document3') is-invalid @enderror" name="document3" required onchange="validateFile(this)">
                                        @error('document3')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <div id="document3_error" class="text-danger" style="display: none;"></div>
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="form-group text-center mb-0">
                                        <button type="submit" class="btn btn-primary btn-block">
                                            Submit and Verify Email
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
    function validateFile(input) {
        const file = input.files[0];
        const errorMessage = document.getElementById(`${input.id}_error`);
        const allowedTypes = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
        const maxSize = 5120 * 1024; // 5MB in bytes

        // Reset previous error messages
        errorMessage.style.display = 'none';
        input.classList.remove('is-invalid');

        if (file) {
            // Check file type
            if (!allowedTypes.includes(file.type)) {
                errorMessage.textContent = 'Invalid file format. Only PDF, PNG, and JPEG files are allowed.';
                errorMessage.style.display = 'block'; // Show error message
                input.classList.add('is-invalid'); // Add invalid class
            } 
            // Check file size
            else if (file.size > maxSize) {
                errorMessage.textContent = 'File size must not exceed 5MB.';
                errorMessage.style.display = 'block'; // Show error message
                input.classList.add('is-invalid'); // Add invalid class
            } 
            // If valid
            else {
                errorMessage.style.display = 'none'; // Hide error message if valid
                input.classList.remove('is-invalid'); // Remove invalid class
            }
        }
    }

    // Validate files before form submission
    document.querySelector('form').addEventListener('submit', function(event) {
        const inputs = ['document1', 'document2', 'document3'];
        let valid = true;

        inputs.forEach(id => {
            const input = document.getElementById(id);
            const errorMessage = document.getElementById(`${id}_error`);
            const file = input.files[0];
            const allowedTypes = ['application/pdf', 'image/png', 'image/jpeg', 'image/jpg'];
            const maxSize = 5120 * 1024; // 5MB in bytes

            // Reset previous error messages
            errorMessage.style.display = 'none';
            input.classList.remove('is-invalid');

            if (file) {
                // Check file type
                if (!allowedTypes.includes(file.type)) {
                    errorMessage.textContent = 'Invalid file format. Only PDF, PNG, and JPEG files are allowed.';
                    errorMessage.style.display = 'block'; // Show error message
                    input.classList.add('is-invalid'); // Add invalid class
                    valid = false; // Set valid to false
                } 
                // Check file size
                else if (file.size > maxSize) {
                    errorMessage.textContent = 'File size must not exceed 5MB.';
                    errorMessage.style.display = 'block'; // Show error message
                    input.classList.add('is-invalid'); // Add invalid class
                    valid = false; // Set valid to false
                }
            } else {
                errorMessage.textContent = 'File is required.';
                errorMessage.style.display = 'block'; // Show error message
                input.classList.add('is-invalid'); // Add invalid class
                valid = false; // Set valid to false
            }
        });

        // Prevent form submission if invalid
        if (!valid) {
            event.preventDefault();
        }
    });
</script>
@endsection
