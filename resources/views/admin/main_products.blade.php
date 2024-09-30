@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h5>Products</h5>
</div>
    <div class="row">
        <div class="col-lg-12">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <!-- Nav tabs -->
            <ul class="nav nav-tabs" id="productManagementTab" role="tablist">
                <li class="nav-item" role="presentation">
                    <a class="nav-link active" id="approved-products-tab" data-bs-toggle="tab" href="#approved-products" role="tab" aria-controls="approved-products" aria-selected="true">Approved Products</a>
                </li>
                <li class="nav-item" role="presentation">
                    <a class="nav-link" id="pending-products-tab" data-bs-toggle="tab" href="#pending-products" role="tab" aria-controls="pending-products" aria-selected="false">Pending Approval</a>
                </li>
            </ul>

            <!-- Tab content -->
            <div class="tab-content" id="productManagementTabContent">
                <!-- Approved Products Tab -->
<div class="tab-pane fade show active" id="approved-products" role="tabpanel" aria-labelledby="approved-products-tab">
    <div class="card mt-3 shadow">
        <div class="card-body">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Product ID</th>
                        <th>Brand Name</th>
                        <th>Generic Name</th>
                        <th>Dosage</th>
                        <th>Form</th>
                        <th>Age Group</th>
                        <th>OTC</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mainProducts as $mainProduct)
                        <tr>
                            <td>{{ $mainProduct->id }}</td>
                            <td>{{ $mainProduct->brand_name }}</td>
                            <td>{{ $mainProduct->generic_name }}</td>
                            <td>{{ $mainProduct->dosage }}</td>
                            <td>{{ $mainProduct->form }}</td>
                            <td>{{ $mainProduct->age_group }}</td>
                            <td>{{ $mainProduct->over_the_counter ? 'Yes' : 'No' }}</td>
                            <td>
                                <a href="{{ route('main_products.edit', $mainProduct->id) }}" class="btn btn-sm btn-primary">Edit</a>

                                <!-- Delete button to trigger modal -->
                                <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal"
                                    data-url="{{ route('main_products.destroy', $mainProduct->id) }}">
                                    Delete
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $mainProducts->links() }} <!-- Pagination for approved products -->
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

<!-- Pending Approval Products Tab -->
<div class="tab-pane fade" id="pending-products" role="tabpanel" aria-labelledby="pending-products-tab">
    <div class="card mt-3 shadow">
        <div class="card-body">
            <form action="{{ route('admin.approve_multiple_products') }}" method="POST">
                @csrf
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="select-all"></th> <!-- Select all checkbox -->
                            <th>Brand Name</th>
                            <th>Generic Name</th>
                            <th>Dosage</th>
                            <th>Form</th>
                            <th>Age Group</th>
                            <th>OTC</th>
                            <th>Approval Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($unapprovedProducts as $product)
                            <tr>
                                <td><input type="checkbox" name="products[]" value="{{ $product->id }}"></td>
                                <td>{{ $product->brand_name }}</td>
                                <td>{{ $product->generic_name }}</td>
                                <td>{{ $product->dosage }}</td>
                                <td>{{ $product->form }}</td>
                                <td>{{ $product->age_group }}</td>
                                <td>{{ $product->over_the_counter ? 'Yes' : 'No' }}</td>
                                <td>{{ $product->general_id ? 'Approved' : 'Pending' }}</td>
                                <td>
                                    <a href="{{ route('admin.approve_product', $product->id) }}" class="btn btn-sm btn-success">Approve</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <button type="submit" class="btn btn-sm btn-success">Approve Selected</button>
            </form>
            {{ $unapprovedProducts->links() }}
        </div>
    </div>
</div>


<script>
    var deleteModal = document.getElementById('deleteModal');
    deleteModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; 
        var url = button.getAttribute('data-url');

        var form = document.getElementById('deleteForm');
        form.action = url;
    });
</script>


<script>
    document.addEventListener('DOMContentLoaded', function () {
    const selectAllCheckbox = document.getElementById('select-all');
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[name="products[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });
    }
});

</script>


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

    .card {
        border: 1px solid #dee2e6;
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
