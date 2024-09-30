@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <h5>Inventory</h5><hr><br>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="row justify-content-center">
    <div class="col-md-12">
        <div class="card shadow h-100 py-2">
            <div class="card-body">
                <div class="row no-gutters align-items-center mb-4">
                    <div class="col">
                        <h5 class="text-xs font-weight-bold text-secondary text-uppercase mb-1">Update Inventory</h5>
                    </div>
                    <div class="col-auto">
                        <a href="{{ route('pharmacy.inventoryHistory') }}" class="btn" style="background-color: #7daaa5; color: white;">
                            <i class="bi bi-clock-history"></i> Inventory
                        </a>
                    </div>
                </div>

                <!-- Inventory Form -->
                <form id="inventoryForm" method="POST" action="{{ route('pharmacy.updateInventory') }}">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="product" class="text-secondary">Product</label>
                        <select class="form-control" id="product" name="product_id" required>
                            <option value="" selected disabled>Select Product</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}">{{ $product->brand_name }} ({{ $product->generic_name }})</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Quantity Display (readonly) -->
                    <div class="form-group mb-3">
                        <label for="quantityDisplay" class="text-secondary">Quantity</label>
                        <input type="text" class="form-control" id="quantityDisplay" name="quantityDisplay" readonly required>
                    </div>

                    <!-- Hidden input for the actual quantity -->
                    <input type="hidden" id="quantity" name="quantity">

                    <div class="form-group mb-3">
                        <label for="status" class="text-secondary">Status</label>
                        <select class="form-control" id="status" name="status">
                            <option value="available">Available</option>
                            <option value="sold out">Sold Out</option>
                            <option value="on order">On Order</option>
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label for="expiration_date" class="text-secondary">Expiration Date</label>
                        <input type="date" id="expiration_date" name="expiration_date" class="form-control" required>
                        <div class="invalid-feedback" id="expiration_error" style="display: none;">
                            The expiration date cannot be in the past.
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn" style="background-color: #0eba9c; color: white;">
                        Update Inventory
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Quantity Modal -->
<div class="modal fade" id="quantityModal" tabindex="-1" role="dialog" aria-labelledby="quantityModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="quantityModalLabel">Enter Quantity</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <input type="number" id="modalQuantityInput" class="form-control" placeholder="Enter Quantity">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancelQuantityBtn">Cancel</button>
                <button type="button" class="btn btn-primary" id="confirmQuantityBtn">Confirm</button>
            </div>
        </div>
    </div>
</div>

    <br>

<h6 class="m-0 font-weight-bold text-secondary">Inventory List</h6>
    <br>

    <ul class="nav nav-tabs" id="inventoryTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="available-tab" data-bs-toggle="tab" href="#available" role="tab" aria-controls="available" aria-selected="true">Available</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="out-of-stock-tab" data-bs-toggle="tab" href="#out-of-stock" role="tab" aria-controls="out-of-stock" aria-selected="false">Out of Stock</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="on-order-tab" data-bs-toggle="tab" href="#on-order" role="tab" aria-controls="on-order" aria-selected="false">Low in Stock</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="discontinued-tab" data-bs-toggle="tab" href="#discontinued" role="tab" aria-controls="discontinued" aria-selected="false">Discontinued</a>
        </li>
    </ul>


<div class="tab-content mt-3" id="inventoryTabsContent">

    <div class="tab-pane fade show active" id="available" role="tabpanel" aria-labelledby="available-tab">
        <div class="table-responsive">
            <table class="table table-bordered" id="availableTable" width="100%" cellspacing="0">
                <thead style="background-color: #7daaa5; color: white;">
                    <tr>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody style="background-color: #f8f9fa; color: #465260;">
                    @foreach($inventoryItems as $item)
                        @if($item->total_quantity > 0 && !$item->on_order && !$item->discontinued)
                            <tr>
                                <td>{{ $item->product->brand_name }}</td>
                                <td>{{ $item->product->generic_name }}</td>
                                <td>{{ $item->total_quantity }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="tab-pane fade" id="out-of-stock" role="tabpanel" aria-labelledby="out-of-stock-tab">
        <div class="table-responsive">
            <table class="table table-bordered" id="outOfStockTable" width="100%" cellspacing="0">
                <thead style="background-color: #7daaa5; color: white;">
                    <tr>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                    </tr>
                </thead>
                <tbody style="background-color: #f8f9fa; color: #465260;">
                    @foreach($inventoryItems as $item)
                        @if($item->total_quantity <= 0)
                            <tr>
                                <td>{{ $item->product->brand_name }}</td>
                                <td>{{ $item->product->generic_name }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>


    <div class="tab-pane fade" id="on-order" role="tabpanel" aria-labelledby="on-order-tab">
        <div class="table-responsive">
            <table class="table table-bordered" id="onOrderTable" width="100%" cellspacing="0">
                <thead style="background-color: #7daaa5; color: white;">
                    <tr>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody style="background-color: #f8f9fa; color: #465260;">
                    @foreach($inventoryItems as $item)
                        @if($item->total_quantity <= 10 && !$item->discontinued)
                            <tr>
                                <td>{{ $item->product->brand_name }}</td>
                                <td>{{ $item->product->generic_name }}</td>
                                <td>{{ $item->total_quantity }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Discontinued Tab -->
    <div class="tab-pane fade" id="discontinued" role="tabpanel" aria-labelledby="discontinued-tab">
        <div class="table-responsive">
            <table class="table table-bordered" id="discontinuedTable" width="100%" cellspacing="0">
                <thead style="background-color: #7daaa5; color: white;">
                    <tr>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Total Quantity</th>
                    </tr>
                </thead>
                <tbody style="background-color: #f8f9fa; color: #465260;">
                    @foreach($inventoryItems as $item)
                        @if($item->discontinued)
                            <tr>
                                <td>{{ $item->product->brand_name }}</td>
                                <td>{{ $item->product->generic_name }}</td>
                                <td>{{ $item->total_quantity }}</td>
                            </tr>
                        @endif
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Show modal when a product is selected
        $('#product').on('change', function() {
            $('#quantityModal').modal('show');
        });

        // Confirm button in the modal
        $('#confirmQuantityBtn').on('click', function() {
            let quantity = $('#modalQuantityInput').val(); // Get the quantity input from modal
            if(quantity && !isNaN(quantity) && quantity > 0) {
                // Set the quantity in the main form
                $('#quantityDisplay').val(quantity);
                $('#quantity').val(quantity); // Hidden input for actual quantity

                // Close the modal
                $('#quantityModal').modal('hide');
            } else {
                alert('Please enter a valid quantity.');
            }
        });

        // Cancel button in the modal
        $('#cancelQuantityBtn').on('click', function() {
            // Close the modal without any action
            $('#quantityModal').modal('hide');
        });

        // Expiration date validation
        document.getElementById('expiration_date').addEventListener('change', function () {
            const selectedDate = new Date(this.value);
            const today = new Date();
            today.setHours(0, 0, 0, 0); // Set time to 00:00:00 to compare dates only

            if (selectedDate < today) {
                // Show error message
                this.classList.add('is-invalid');
                document.getElementById('expiration_error').style.display = 'block';
            } else {
                // Remove error message
                this.classList.remove('is-invalid');
                document.getElementById('expiration_error').style.display = 'none';
            }
        });
    });
</script>

@endsection
