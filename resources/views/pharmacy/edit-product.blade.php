@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <h5>Edit Product</h5><hr>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card mb-3" style="border: 1px solid #7daaa5;">
        <div class="card-body">
            <form method="POST" action="{{ route('pharmacy.updateProduct', $product->id) }}">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="brand_name">Brand Name</label>
                            <input type="text" class="form-control" id="brand_name" name="brand_name" value="{{ old('brand_name', $product->brand_name) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="generic_name">Generic Name</label>
                            <input type="text" class="form-control" id="generic_name" name="generic_name" value="{{ old('generic_name', $product->generic_name) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="dosage">Dosage</label>
                            <input type="text" class="form-control" id="dosage" name="dosage" value="{{ old('dosage', $product->dosage) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="form">Form</label>
                            <input type="text" class="form-control" id="form" name="form" value="{{ old('form', $product->form) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="price">Retail Price</label>
                            <input type="number" class="form-control" id="price" name="price" step="0.01" value="{{ old('price', $product->price) }}" required>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="price_bought">Cost Price</label>
                            <input type="number" class="form-control" id="price_bought" name="price_bought" step="0.01" value="{{ old('price_bought', $product->price_bought) }}" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="description">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3" required>{{ old('description', $product->description) }}</textarea>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="age_group">Age Group</label>
                            <select class="form-control" id="age_group" name="age_group" required>
                                <option value="">Select Age Group</option>
                                <option value="Children" {{ old('age_group', $product->age_group) == 'Children' ? 'selected' : '' }}>Children</option>
                                <option value="Teen" {{ old('age_group', $product->age_group) == 'Teen' ? 'selected' : '' }}>Teen</option>
                                <option value="Adult" {{ old('age_group', $product->age_group) == 'Adult' ? 'selected' : '' }}>Adult</option>
                                <option value="Senior" {{ old('age_group', $product->age_group) == 'Senior' ? 'selected' : '' }}>Senior</option>
                                <option value="General" {{ old('age_group', $product->age_group) == 'General' ? 'selected' : '' }}>General</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12 mb-3">
                        <div class="form-group">
                            <label for="over_the_counter">Over the Counter</label>
                                <input type="checkbox" id="over_the_counter" name="over_the_counter" value="1" 
                                {{ old('over_the_counter', $product->over_the_counter) ? 'checked' : '' }}>
                            <small class="text-muted">Check if this product is available over the counter.</small>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn" style="background-color: #7daaa5; color: white;">Update Product</button>
            </form>
        </div>
    </div>
</div>
@endsection
