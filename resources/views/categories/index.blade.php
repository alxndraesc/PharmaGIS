@extends('layouts.pharmacy')

@section('content')
<br>
<br>
<br>
<div class="container">
    <h5>Categories</h5>
    <hr>

    <!-- Success message -->
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="row">
        <!-- Categories List (Left Side) -->
        <div class="col-lg-8 col-md-12">
            @foreach ($categories as $category)
                <div class="accordion mb-4" id="accordionExample{{ $category->id }}">
                    <div class="accordion-item shadow-sm" style="border-radius: 12px; background-color: #f8f9fa;">
                        <h2 class="accordion-header" id="heading{{ $category->id }}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $category->id }}" aria-expanded="true" aria-controls="collapse{{ $category->id }}" style="background-color: #e1e6db;">
                                <span class="text-secondary">{{ $category->name }}</span>
                            </button>
                        </h2>
                        <div id="collapse{{ $category->id }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $category->id }}" data-bs-parent="#accordionExample{{ $category->id }}">
                            <div class="accordion-body">
                                <!-- Products Table -->
                                <table class="table table-hover table-bordered" style="background-color: white; border-radius: 12px;">
                                    <thead class="table-light">
                                        <tr class="text-secondary">
                                            <th>Brand Name</th>
                                            <th>Generic Name</th>
                                            <th>Form</th>
                                            <th>Dosage</th>
                                            <th>Age Group</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($category->products as $product)
                                            <tr>
                                                <td>{{ $product->brand_name }}</td>
                                                <td>{{ $product->generic_name }}</td>
                                                <td>{{ $product->form }}</td>
                                                <td>{{ $product->dosage }}</td>
                                                <td>{{ $product->age_group }}</td>
                                                <td>
                                                    <form action="{{ route('categories.removeProduct', [$category->id, $product->id]) }}" method="POST" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <!-- Add Product Button -->
                                <button class="btn" style="background-color: #195465; color: white;" data-bs-toggle="modal" data-bs-target="#addProductModal{{ $category->id }}">
                                    Add Product
                                </button>

                                <!-- Add Product Modal -->
                                <div class="modal fade" id="addProductModal{{ $category->id }}" tabindex="-1" aria-labelledby="addProductModalLabel" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="addProductModalLabel">Add Product to {{ $category->name }}</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <form method="POST" action="{{ route('categories.addProduct', $category->id) }}">
                                                    @csrf
                                                    <div class="form-group">
                                                        <label for="product_id" class="text-secondary">Select Product</label>
                                                        <select class="form-control" id="product_id" name="product_id" required>
                                                            @foreach ($products as $product)
                                                                <option value="{{ $product->id }}">{{ $product->product_name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <button type="submit" class="btn" style="background-color: #195465; color: white; margin-top: 12px;">Add Product</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Category Actions -->
                                <a href="{{ route('categories.edit', $category->id) }}" class="btn" style="background-color: #7daaa5; color: white;">Edit Category</a>
                                <form action="{{ route('categories.destroy', $category->id) }}" method="POST" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Delete Category</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Add Category Card (Right Side) -->
        <div class="col-lg-4 col-md-12">
            <div class="card shadow-sm" style="border-radius: 12px;">
                <div class="card-header" style="background-color: #7daaa5; color: white;">Add Category</div>
                <div class="card-body" style="background-color: #f8f9fa;">
                    <form method="POST" action="{{ route('categories.store') }}">
                        @csrf
                        <div class="form-group">
                            <label for="name" class="text-secondary">Category Name</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter category name" required>
                        </div>
                        <button type="submit" class="btn" style="background-color: #195465; color: white; margin-top: 12px;">
                            <i class="fas fa-plus"></i> Add Category
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
