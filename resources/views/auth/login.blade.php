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
                                <h3>Sign In to <strong>PharmaGIS</strong></h3>
                                <p>Please enter your login credentials below.</p>
                            </div>

                            <div class="card-body">
                                <!-- Start Laravel Form -->
                                <form method="POST" action="{{ route('login') }}">
                                    @csrf
                                    
                                    <div class="form-group mb-3">
                                        <label for="email">Email</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                                        
                                        @error('email')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-4">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                                        
                                        @error('password')
                                            <div class="invalid-feedback">
                                                <strong>{{ $message }}</strong>
                                            </div>
                                        @enderror
                                    </div>

                                    <div class="d-flex mb-4 align-items-center">
                                        <label class="control control--checkbox mb-0">
                                            <span class="caption">Remember me</span>
                                            <input type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                            <div class="control__indicator"></div>
                                        </label>

                                        @if (Route::has('password.request'))
                                            <span class="ml-auto"><a href="{{ route('password.request') }}" class="forgot-pass" style="color: #0eba9c;">Forgot Password?</a></span>
                                        @endif
                                    </div>

                                    <input type="submit" value="Log In" class="btn text-white btn-block" style="background-color: #0eba9c; border: none;">
                                </form>
                                <!-- End Laravel Form -->

                                <span class="d-block text-center my-4 text-muted">or <a href="{{ url('/registeras') }}" style="color: #a2dad7;">Register</a> to create an account</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
