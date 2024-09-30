@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <!-- Image Section -->
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('images/img_main.png') }}" alt="Image" class="img-fluid">
            </div>

            <!-- Login Form Section -->
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow" style="border-radius: 0.5rem;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h5>Admin Login</h5>
                            </div>

                            <div class="card-body">
                                <!-- Start Laravel Form -->
                                <form method="POST" action="{{ route('admin.login') }}">
                                    @csrf
                                    
                                    <!-- Email Field -->
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <!-- Password Field -->
                                    <div class="form-group mb-4">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>
                                    <input type="submit" value="Log In" class="btn text-white btn-block" style="background-color: #0eba9c; border: none;">
                                </form>
                        
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End of Form Section -->
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- Custom CSS from your Colorlib Template -->
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')
<!-- Include any JS files needed for the template -->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@endsection
