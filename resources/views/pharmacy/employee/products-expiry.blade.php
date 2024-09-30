@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <h5>Expiry</h5><hr><br>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <!-- Tabs Navigation -->
    <ul class="nav nav-tabs" id="expiryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="expiring-tab" data-bs-toggle="tab" href="#expiring" role="tab" aria-controls="expiring" aria-selected="true">Expiring Products</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="expired-tab" data-bs-toggle="tab" href="#expired" role="tab" aria-controls="expired" aria-selected="false">Expired Products</a>
        </li>
    </ul>

    <!-- Tabs Content -->
    <div class="tab-content mt-3" id="expiryTabsContent">
        <!-- Expiring Products Tab -->
        <div class="tab-pane fade show active" id="expiring" role="tabpanel" aria-labelledby="expiring-tab">
            <table class="table table-light table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Expiration Date</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiringProducts as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->product->brand_name }}</td>
                            <td>{{ $item->product->generic_name }}</td>
                            <td>{{ $item->expiration_date->format('Y-m-d') }}</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Expired Products Tab -->
        <div class="tab-pane fade" id="expired" role="tabpanel" aria-labelledby="expired-tab">
            <table class="table table-light table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Product Name</th>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Expiration Date</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($expiredProducts as $item)
                        <tr>
                            <td>{{ $item->product->product_name }}</td>
                            <td>{{ $item->product->brand_name }}</td>
                            <td>{{ $item->product->generic_name }}</td>
                            <td>{{ $item->expiration_date->format('Y-m-d') }}</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection
