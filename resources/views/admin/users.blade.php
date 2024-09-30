@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h5 class="h5 text-gray-800">Users Registered</h5>
</div>
<br>

<!-- Nav tabs -->
<ul class="nav nav-tabs" id="userManagementTab" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link active" id="customer-tab" data-bs-toggle="tab" href="#customers" role="tab" aria-controls="customers" aria-selected="true">Customer Accounts</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="approved-pharmacies-tab" data-bs-toggle="tab" href="#approved-pharmacies" role="tab" aria-controls="approved-pharmacies" aria-selected="false">Approved Pharmacies</a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link" id="pending-pharmacies-tab" data-bs-toggle="tab" href="#pending-pharmacies" role="tab" aria-controls="pending-pharmacies" aria-selected="false">Pending Pharmacies</a>
    </li>
</ul>

<!-- Tab content -->
<div class="tab-content" id="userManagementTabContent">
    <!-- Customer Accounts Tab -->
    <div class="tab-pane fade show active" id="customers" role="tabpanel" aria-labelledby="customer-tab">
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($customers as $customer)
                            <tr>
                                <td>{{ $customer->name }}</td>
                                <td>{{ $customer->email }}</td>
                                <td>{{ $customer->created_at ? $customer->created_at->format('Y-m-d') : 'N/A' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Approved Pharmacies Tab -->
    <div class="tab-pane fade" id="approved-pharmacies" role="tabpanel" aria-labelledby="approved-pharmacies-tab">
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Pharmacy Name</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pharmacies as $pharmacy)
                            @if ($pharmacy->pharmacy && $pharmacy->pharmacy->is_approved)
                                <tr>
                                    <td>{{ $pharmacy->name }}</td>
                                    <td>{{ $pharmacy->email }}</td>
                                    <td>{{ $pharmacy->pharmacy->name ?? 'N/A' }}</td>
                                    <td>{{ $pharmacy->created_at ? $pharmacy->created_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pending Pharmacies Tab -->
    <div class="tab-pane fade" id="pending-pharmacies" role="tabpanel" aria-labelledby="pending-pharmacies-tab">
        <div class="card mt-3">
            <div class="card-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-light">
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Pharmacy Name</th>
                            <th>Registered At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pharmacies as $pharmacy)
                            @if ($pharmacy->pharmacy && !$pharmacy->pharmacy->is_approved)
                                <tr>
                                    <td>{{ $pharmacy->name }}</td>
                                    <td>{{ $pharmacy->email }}</td>
                                    <td>{{ $pharmacy->pharmacy->name ?? 'N/A' }}</td>
                                    <td>{{ $pharmacy->created_at ? $pharmacy->created_at->format('Y-m-d') : 'N/A' }}</td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .nav-tabs .nav-link {
        font-weight: 600;
        color: #4e73df;
    }

    .nav-tabs .nav-link.active {
        color: #fff;
        background-color: #4e73df;
        border-color: #4e73df;
    }

    .table thead th {
        font-weight: bold;
        background-color: #f8f9fc;
    }

    .table tbody tr:hover {
        background-color: #f1f1f1;
    }
</style>
@endsection
