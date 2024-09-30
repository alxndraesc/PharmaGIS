@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <h5>Products</h5><hr>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <br>
    <div class="d-flex justify-content-between mb-3">
        <form action="{{ route('pharmacy.emp.searchProducts') }}" method="GET" class="form-inline">
            <div class="input-group">
                <input type="text" name="query" class="form-control" placeholder="Search products..." required value="{{ request()->input('query') }}">
                <div class="input-group-append">
                    <button type="submit" class="btn" style="background-color: #7daaa5; color: white;">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
        </form>
    </div><br>

    @if($products->isEmpty())
        <p>No products found.</p>
    @else
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-secondary">Products List</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Brand Name</th>
                                <th>Generic Name</th>
                                <th>Dosage</th>
                                <th>Form</th>
                                <th>Price</th>
                                <th>For Ages</th>
                                <th>Availability</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td>{{ $product->product_name }}</td>
                                    <td>{{ $product->brand_name }}</td>
                                    <td>{{ $product->generic_name }}</td>
                                    <td>{{ $product->dosage }}</td>
                                    <td>{{ $product->form }}</td>
                                    <td>P {{ number_format($product->price, 2) }}</td>
                                    <td>{{ $product->age_group }}</td>
                                    <td>
                                        @php
                                            $quantity = isset($inventoryData[$product->id]) ? $inventoryData[$product->id] : 0;
                                            if ($quantity > 10) {
                                                $status = 'Available';
                                                $badgeClass = 'bg-success';
                                            } elseif ($quantity > 0 && $quantity <= 10) {
                                                $status = 'On Order';
                                                $badgeClass = 'bg-warning';
                                            } else {
                                                $status = 'Sold Out';
                                                $badgeClass = 'bg-danger';
                                            }
                                        @endphp
                                        <span class="badge {{ $badgeClass }}">{{ $status }}</span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection
