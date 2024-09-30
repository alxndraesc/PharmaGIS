@extends('layouts.pharmacy')

@section('content')
<div class="container">
    <h3 class="mb-4">Import Products</h3>

    <form action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
            <label for="file">Upload Products File (XLSX or CSV)</label>
            <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" required>
            @error('file')
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                </span>
            @enderror
        </div>

        <button type="submit" class="btn btn-info mt-3" id="import-btn">
            <i class="fas fa-upload"></i> Import Products
        </button>

        <!-- Loading Spinner -->
        <div id="loading-spinner" class="mt-3" style="display: none;">
            <div class="spinner-border text-info" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
    </form>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="alert alert-success mt-3">{{ session('success') }}</div>
    @elseif (session('error'))
        <div class="alert alert-danger mt-3">{{ session('error') }}</div>
    @endif
</div>

@endsection

@push('scripts')
<script>
    // Show loading spinner when form is submitted
    document.querySelector('form').addEventListener('submit', function() {
        document.getElementById('import-btn').disabled = true;
        document.getElementById('loading-spinner').style.display = 'block';
    });
</script>
@endpush
