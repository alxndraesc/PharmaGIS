@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <!-- Dashboard Title -->
    <h5 class="text-secondary">Dashboard</h5>

    <!-- Welcome Alert -->
    <div class="alert alert-dismissible alert-secondary" style="background-color: #b0bdba;">
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        <p>Welcome Back, <strong>{{ Auth::user()->name }}</strong>!</p>
    </div>
    <hr>

<!-- First Row: Total Cost and Total Products Sold Today -->
<div class="row mb-4">
    <!-- Total Cost Today -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow h-100 py-3 text-center">
            <div class="card-body">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto">
                        <i class="bi bi-cash-coin fa-3x text-info"></i>
                    </div>
                    <div class="col">
                        <div class="h5 font-weight-bold text-gray-800">Php {{ number_format($totalPurchasesToday, 2) }}</div>
                        <div class="text-xs font-weight-bold text-info text-uppercase">
                            Total Cost for {{ $today }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Total Products Sold Today -->
    <div class="col-lg-6 col-md-12 mb-4">
        <div class="card shadow h-100 py-3 text-center">
            <div class="card-body">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto">
                        <i class="bi bi-box-seam fa-3x text-info"></i>
                    </div>
                    <div class="col">
                        <div class="h5 font-weight-bold text-gray-800">{{ $totalProductsSoldToday }}</div>
                        <div class="text-xs font-weight-bold text-info text-uppercase">
                            Total Products Sold Today
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Second Row: Total Products, Low in Stock, Out of Stock -->
<div class="row">
    <!-- Total Products -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow h-100 py-3 text-center">
            <div class="card-body">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto">
                        <i class="bi bi-boxes fa-3x text-secondary"></i>
                    </div>
                    <div class="col">
                        <div class="h5 font-weight-bold text-gray-800">{{ $totalProducts }}</div>
                        <div class="text-xs font-weight-bold text-secondary text-uppercase">
                            Total Products Listed
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Low in Stock Products -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow h-100 py-3 text-center">
            <div class="card-body">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto">
                        <i class="bi bi-basket fa-3x text-warning"></i>
                    </div>
                    <div class="col">
                        <div class="h5 font-weight-bold text-gray-800">{{ $lowInStockCount }}</div>
                        <div class="text-xs font-weight-bold text-warning text-uppercase">
                            Low in Stock Products
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Out of Stock Products -->
    <div class="col-lg-4 col-md-6 mb-4">
        <div class="card shadow h-100 py-3 text-center">
            <div class="card-body">
                <div class="row align-items-center justify-content-center">
                    <div class="col-auto">
                        <i class="bi bi-x-circle fa-3x text-danger"></i>
                    </div>
                    <div class="col">
                        <div class="h5 font-weight-bold text-gray-800">{{ $outOfStockCount }}</div>
                        <div class="text-xs font-weight-bold text-danger text-uppercase">
                            Out of Stock Products
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

    
    <!-- Purchase Form Section -->
    <br><hr><br>
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center mb-4">
                    <div class="col">
                        <h5 class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Purchase Items</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('pharmacy.purchaseHistory') }}" class="btn" style="background-color: #7daaa5; color: white;">
                            <i class="bi bi-clock-history"></i> Purchases
                        </a>
                    </div>
                </div>

                <form id="purchaseForm" method="POST" action="{{ route('pharmacy.purchase') }}">
                    @csrf
                    <div class="form-group row mb-3">
                        <label for="searchProduct" class="col-md-4 col-form-label text-secondary">Select Product</label>
                        <div class="col-md-8">
                            <select id="searchProduct" class="form-control product-search" style="width: 100%; color: #465260;">
                                <option></option>
                                @foreach($products as $product)
                                    <option value="{{ $product->product_id }}" data-price="{{ $product->price }}">
                                        {{ $product->brand_name }} ({{ $product->generic_name }}) - Php {{ $product->price }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div id="purchaseList" class="mb-4"></div>

                    <h4 class="text-xs font-weight-bold text-secondary text-uppercase mb-4">Total Cost: Php <span id="totalCost">0.00</span></h4>
                    <button type="submit" class="btn" style="background-color: #0eba9c; color: white;">Purchase</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Product Selection -->
<div class="modal fade" id="confirmProductModal" tabindex="-1" aria-labelledby="confirmProductModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="quantityInput" class="col-form-label">Enter Quantity:</label>
                    <input type="number" class="form-control" id="quantityInput" min="1" required>
                </div>
                <input type="hidden" id="confirmProductId">
                <input type="hidden" id="confirmProductPriceData">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="confirmQuantityForm">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- Confirmation Modal for Quantity Input -->
<div class="modal fade" id="quantityModal" tabindex="-1" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quantityModalLabel">Enter Quantity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <label for="quantity" class="col-form-label">Quantity:</label>
                <input type="number" class="form-control" id="quantity" min="1" required>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-success" id="submitInventoryBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize Select2
        $('#searchProduct').select2({
            placeholder: 'Select a product',
            minimumInputLength: 1,
            dropdownAutoWidth: true,
            allowClear: true,
            width: 'resolve'
        });

        // When a product is selected, show the quantity modal
        $('#searchProduct').on('change', function() {
            let productId = $(this).val();
            if (productId) {
                // Show the quantity input modal
                $('#quantityModal').modal('show');
            }
        });

        // Confirm quantity and update purchase list
        $('#submitInventoryBtn').on('click', function() {
            let quantity = $('#quantity').val();
            let productId = $('#searchProduct').val();
            let productText = $('#searchProduct option:selected').text();
            let productPrice = $('#searchProduct option:selected').data('price');

            if (quantity && !isNaN(quantity) && quantity > 0) {
                addToPurchaseList(productId, productText, productPrice, quantity);
                $('#quantityModal').modal('hide'); // Close the modal
                $('#searchProduct').val(null).trigger('change'); // Reset select2 input
            } else {
                alert('Please enter a valid quantity');
            }
        });

        function addToPurchaseList(productId, productText, productPrice, quantity) {
            let listItem = $('<div>').addClass('card mb-2')
                .append($('<div>').addClass('card-body')
                    .append($('<div>').text(productText))
                    .append($('<p>').text('Quantity: ' + quantity))
                    .append($('<input>').attr({
                        type: 'hidden',
                        name: 'quantities[]',
                        value: quantity,
                        'data-price': productPrice
                    }))
                    .append($('<input>').attr({
                        type: 'hidden',
                        name: 'products[]',
                        value: productId
                    }))
                    .append($('<button>').addClass('btn btn-info mt-2 remove-btn').text('Remove'))
                );

            $('#purchaseList').append(listItem);
            calculateTotalCost();
        }

        function calculateTotalCost() {
            let totalCost = 0;
            $('#purchaseList .card-body').each(function() {
                let quantity = parseInt($(this).find('input[name="quantities[]"]').val()) || 0;
                let price = parseFloat($(this).find('input[name="quantities[]"]').data('price')) || 0;
                totalCost += quantity * price;
            });
            $('#totalCost').text(totalCost.toFixed(2));
        }

        $(document).on('click', '.remove-btn', function() {
            $(this).closest('.card').remove();
            calculateTotalCost();
        });
    });
</script>

@endsection
