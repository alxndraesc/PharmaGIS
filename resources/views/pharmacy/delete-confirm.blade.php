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
                    <div class="col-md-8">
                        <div class="card shadow" style="border-radius: 0.5rem;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h3>Delete Account Confirmation</h3>
                                <p>Please confirm your password to delete your account.</p>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('pharmacy.account.delete') }}">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="password">Password</label>
                                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required>
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                    <div class="form-group text-center mb-0">
                                        <button type="submit" class="btn btn-danger btn-block">
                                            Delete My Account
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
@endsection
