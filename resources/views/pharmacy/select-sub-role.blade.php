@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('images/select_role.png') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow" style="border-radius: 0.5rem;">
                            <div class="card-header text-center" style="background-color: #f8f9fa;">
                                <h3>Select Role</h3>
                            </div>

                            <div class="card-body">
                                <form id="sub-role-form" action="{{ route('pharmacy.setRole') }}" method="POST">
                                    @csrf
                                    <div class="form-group mb-3">
                                        <label for="sub_role">Role</label>
                                        <select name="sub_role" id="sub_role" class="form-control custom-select">
                                            <option value="owner">Owner</option>
                                            <option value="employee">Employee</option>
                                        </select>
                                    </div>
                                    <div class="form-group text-center mb-0">
                                        <button type="submit" class="btn" style="background-color: #0eba9c; color: white;">
                                            Select
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

<!-- Modal for Password Confirmation -->
<div class="modal fade" id="passwordConfirmModal" tabindex="-1" role="dialog" aria-labelledby="passwordConfirmModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form id="password-confirm-form" action="{{ route('pharmacy.confirm-password') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="passwordConfirmModalLabel">Confirm Password</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <input type="hidden" name="sub_role" id="modal_sub_role" value="owner">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Confirm</button>
                </div>
            </form>
        </div>
    </div>
</div>

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

<!-- Ensure jQuery is included -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

<script>
document.getElementById('sub-role-form').addEventListener('submit', function(event) {
    var selectedRole = document.getElementById('sub_role').value;
    if (selectedRole === 'owner') {
        event.preventDefault(); // Prevent the form from submitting
        document.getElementById('modal_sub_role').value = selectedRole; // Set the hidden input value in the modal
        $('#passwordConfirmModal').modal('show'); // Show the modal
    }
});

document.getElementById('password-confirm-form').addEventListener('submit', function(event) {
    var password = document.getElementById('password').value;
    if (password === '') {
        event.preventDefault(); // Prevent the form from submitting
        alert('Password is required.');
    }
});
</script>
@endsection