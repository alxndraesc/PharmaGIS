@extends('layouts.app')

@section('title', 'Account Created')

@section('content')
<div class="content">
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('images/img_main.png') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow" style="border-radius: 0.5rem;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h3>Account Created Successfully</h3>
                                <p>Welcome, New User!</p>
                            </div>
                            <div class="card-body">
                                @if (session('status'))
                                    <div class="alert alert-dismissible alert-success" role="alert">
                                        {{ session('status') }}
                                    </div>
                                @endif

                                <p>{{ __('You have successfully created an account! To continue, please ') }}
                                    <a href="{{ route('logout') }}" class="btn btn-link p-0 m-0 align-baseline" style="color: #0eba9c;" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                        re-login
                                    </a>
                                    {{ __(' to get started.') }}</p>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
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
