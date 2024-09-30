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
        <div>
            <a href="{{ route('pharmacy.createProduct') }}" class="btn" style="background-color: #195465; color: white; margin-right: 0.5rem;">Add Product</a>
            <button type="button" class="btn" style="background-color: #0eba9c; color: white;" data-bs-toggle="modal" data-bs-target="#importModal">
                Import
            </button>
        </div>

        <form action="{{ route('pharmacy.searchProducts') }}" method="GET" class="form-inline">
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
                                <th>ID</th> <!-- General ID -->
                                <th>Brand Name</th>
                                <th>Generic Name</th>
                                <th>Dosage</th>
                                <th>Form</th>
                                <th>Cost Price</th>
                                <th>Retail Price</th>
                                <th>Age Group</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($products->sortBy('generic_name') as $product)
                            <tr>
                                <td>{{ $product->general_id }}</td> 
                                <td>{{ $product->brand_name }}</td>
                                <td>{{ $product->generic_name }}</td>
                                <td>{{ $product->dosage }}</td>
                                <td>{{ $product->form }}</td>
                                <td>P {{ number_format($product->price_bought, 2) }}</td>
                                <td>P {{ number_format($product->price, 2) }}</td>
                                <td>{{ $product->age_group }}</td>
                                <td>
                                    <a href="{{ route('pharmacy.editProduct', $product->id) }}" class="btn btn-sm" style="background-color: #7daaa5; color: white;">Edit</a>

                                    <!-- Trigger Modal -->
                                    <button type="button" class="btn btn-danger btn-sm" data-bs-toggle="modal" data-bs-target="#deleteModal" data-productid="{{ $product->id }}">
                                        Delete
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <!-- Import Products Modal -->
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Products</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">Upload File (.xlsx or .csv)</label>
                            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn" style="background-color: #7daaa5; color: white; margin-top: 1rem;">
                            <i class="fas fa-upload"></i> Import Products
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Confirm Deletion</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this product? This action cannot be undone.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>

                    <!-- The form to delete the product -->
                    <form id="deleteForm" method="POST" action="">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Delete</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
       document.addEventListener('DOMContentLoaded', function () {
        var deleteModal = document.getElementById('deleteModal');

        deleteModal.addEventListener('show.bs.modal', function (event) {
            var button = event.relatedTarget; // Button that triggered the modal
            var productId = button.getAttribute('data-productid'); // Extract product id
            
            // Use route helper to generate the correct URL
            var actionUrl = "{{ route('pharmacy.deleteProduct', ':id') }}".replace(':id', productId);

            var form = deleteModal.querySelector('#deleteForm');
            form.setAttribute('action', actionUrl); // Set the form action
        });
    });
    </script>

    @push('scripts')
    <script>
        document.querySelector('form').addEventListener('submit', function() {
            document.getElementById('import-btn').disabled = true;
            document.getElementById('loading-spinner').style.display = 'block';
        });
    </script>
    @endpush
@endsection
