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
                                <h3>Register As</h3>
                                <p>Please select your registration type below.</p>
                            </div>

                            <div class="card-body">
                                <div class="d-flex flex-column">
                                    <a href="{{ route('register.customer') }}" class="btn text-white btn-block" style="background-color: #0eba9c; border: none;">
                                        <i class="bi bi-person-fill"></i>Customer
                                    </a>
                                    <a href="{{ route('pharmacy.register.step1') }}" class="btn text-white btn-block" style="background-color: #0eba9c; border: none;" >
                                        <i class="bi bi-capsule-pill"></i>Pharmacy
                                    </a>
                                </div>

                                <span class="d-block text-center my-4 text-muted">or <a href="{{ route('login') }}" style="color: #0eba9c;">Log In</a> if you already have an account</span>
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
<link rel="stylesheet" href="{{ asset('css/style.css') }}">
@endsection

@section('scripts')
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@endsection
