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
                                <h3>Register as <strong>Pharmacy - Step 1</strong></h3>
                                <p>Please fill in your details below.</p>
                            </div>

                            <div class="card-body">
                                <form method="POST" action="{{ route('pharmacy.register.step1') }}">
                                    @csrf

                                    <!-- Account Name for the users table -->
                                    <div class="form-group mb-3">
                                        <label for="account_name">{{ __('Account Name') }}</label>
                                        <input id="account_name" type="text" class="form-control @error('account_name') is-invalid @enderror" name="account_name" value="{{ old('account_name') }}" required autocomplete="account_name" autofocus>

                                        @error('account_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="email">{{ __('Email Address') }}</label>
                                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password">{{ __('Password') }}</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="password-confirm">{{ __('Confirm Password') }}</label>
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                                    </div>

                                    <div class="form-group text-center mb-0">
                                        <button type="submit" class="btn" style="background-color: #0eba9c; color: white;">
                                            {{ __('Next') }}
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
<br><br>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 0.5rem;
    }

    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    @media (max-width: 576px) {
        .card-header {
            font-size: 1.125rem;
        }
    }
</style>
@endsection
