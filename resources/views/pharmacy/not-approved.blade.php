@extends('layouts.pharmacy')

@section('title', 'Registration Pending')

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
                                <h3>Registration Pending</h3>
                                <p>Hello, New User!</p>
                            </div>
                            <div class="card-body">
                                <div class="alert alert-dismissible alert-info" role="alert">
                                    Your pharmacy registration is pending approval by the admin.
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
