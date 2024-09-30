@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h5>Requests</h5>
</div>
    <div class="row">
        <div class="col-md-12">
            <div class="card shadow mb-4">

                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-striped" id="pharmacyRequestsTable">
                            <thead class="thead-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Documents</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($pharmacies as $pharmacy)
                                    <tr>
                                        <td>{{ $pharmacy->name }}</td>
                                        <td>{{ $pharmacy->user->email }}</td>
                                        <td>{{ $pharmacy->address }}</td>
                                        <td>
                                            <a href="{{ asset('storage/' . $pharmacy->document1_path) }}" target="_blank" class="btn btn-link">Document 1</a><br>
                                            <a href="{{ asset('storage/' . $pharmacy->document2_path) }}" target="_blank" class="btn btn-link">Document 2</a><br>
                                            <a href="{{ asset('storage/' . $pharmacy->document3_path) }}" target="_blank" class="btn btn-link">Document 3</a>
                                        </td>   
                                        <td>
                                            <form action="{{ route('admin.approvals.approve', $pharmacy->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                <button type="submit" class="btn btn-success btn-sm">Approve</button>
                                            </form>
                                            <form action="{{ route('admin.approvals.reject', $pharmacy->id) }}" method="POST" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger btn-sm">Reject</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    @if ($pharmacies->isEmpty())
                        <p class="text-center">No pharmacy registration requests at the moment.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
