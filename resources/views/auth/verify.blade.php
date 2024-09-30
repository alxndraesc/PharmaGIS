@extends('layouts.app')

@section('title', 'Verify Your Email')

@section('content')
<div class="content">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('images/pending.png') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow" style="border-radius: 0.5rem;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h3>{{ __('Verify Your Email Address') }}</h3>
                                <p>Hello, User!</p>
                            </div>
                            <div class="card-body">
                                @if (session('resent'))
                                    <div class="alert alert-dismissible alert-success" role="alert">
                                        {{ __('A fresh verification link has been sent to your email address.') }}
                                    </div>
                                @endif

                                <div class="alert alert-dismissible alert-info" role="alert">
                                    {{ __('Before proceeding, please check your email for a verification link.') }}
                                </div>
                                <p>{{ __('If you did not receive the email') }},
                                    <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-link p-0 m-0 align-baseline" style="color: #0eba9c;">{{ __('click here to request another') }}</button>.
                                    </form>
                                </p>
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
