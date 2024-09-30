@extends('layouts.app')

@section('content')
<div class="content">
    <div class="container">
        <div class="row">
            <div class="col-md-6 order-md-2">
                <img src="{{ asset('images/img_main.png') }}" alt="Image" class="img-fluid">
            </div>
            <div class="col-md-6 contents">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card shadow">
                            <div class="card-body">
                                <!-- Start Search Bar -->
                                <form action="{{ route('customer.search') }}" method="POST">
                                    @csrf
                                    <div class="input-group mb-3">
                                        <!-- Filter Dropdown -->
                                        <div class="dropdown">
                                            <button class="btn btn-outline-info dropdown-toggle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bi bi-funnel"></i>
                                            </button>
                                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                <li>
                                                    <div class="px-4 py-2">
                                                        <!-- Age Group Filter -->
                                                        <div class="mb-3">
                                                            <select class="form-select" name="age_group">
                                                                <option value="">Age Group</option>
                                                                <option value="Children" {{ request('age_group') == 'Children' ? 'selected' : '' }}>Children</option>
                                                                <option value="Teen" {{ request('age_group') == 'Teen' ? 'selected' : '' }}>Teen</option>
                                                                <option value="Adult" {{ request('age_group') == 'Adult' ? 'selected' : '' }}>Adult</option>
                                                                <option value="Senior" {{ request('age_group') == 'Senior' ? 'selected' : '' }}>Senior</option>
                                                                <option value="General" {{ request('age_group') == 'General' ? 'selected' : '' }}>General</option>
                                                            </select>
                                                        </div>

                                                        <!-- Price Range Filter -->
                                                        <div class="mb-3">
                                                            <select class="form-select" name="price_sort">
                                                                <option value="">Sort by Price</option>
                                                                <option value="asc" {{ request('price_sort') == 'asc' ? 'selected' : '' }}>Cheapest First</option>
                                                                <option value="desc" {{ request('price_sort') == 'desc' ? 'selected' : '' }}>Expensive First</option>
                                                            </select>
                                                        </div>

                                                        <button class="btn btn-info btn-sm" type="submit">Filter</button>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>

                                        <!-- Search Input and Button -->
                                        <input type="text" class="form-control" name="query" required placeholder="Search products...">
                                        <div class="input-group-append">
                                            <button class="btn btn-info text-white" type="submit">
                                                <i class="bi bi-search"></i>
                                            </button>
                                        </div>
                                    </div>
                                </form>
                                <!-- End Search Bar -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        /* Styles for the search bar card */
        .search-bar-card {
            background-color: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); /* Soft shadow */
            margin-bottom: 30px; /* Space between search bar and results */
        }

        /* Custom button styling for dropdown and search */
        .btn-outline-info {
            border-color: #68CBD7;
            color: #68CBD7;
        }
        .btn-outline-info:hover {
            background-color: #68CBD7;
            color: white;
        }

        .btn-info {
            background-color: #68CBD7;
            border-color: #68CBD7;
        }

        /* Dropdown menu custom styling */
        .dropdown-menu {
            width: 250px; /* Adjusts the width of the dropdown */
        }

        /* Form input and search bar alignment */
        .form-control {
            border-radius: 0px !important;
            box-shadow: none;
        }

        /* Input group button and text alignment */
        .input-group-append .btn {
            border-radius: 0 !important;
            padding-left: 20px;
            padding-right: 20px;
        }

        /* Adjust shadow for search bar */
        .card {
            border: none;
        }
    </style>
@endsection

@section('scripts')
<!-- Include any JS files needed for the template -->
<script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<script src="{{ asset('js/main.js') }}"></script>
@endsection

