@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <!-- Search Bar -->
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow mb-4">
                <div class="card-body">
                    <form action="{{ route('customer.search') }}" method="POST">
                        @csrf
                        <div class="input-group">
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
                                                    <option value="">Price</option>
                                                    <option value="asc" {{ request('price_sort') == 'asc' ? 'selected' : '' }}>Lowest</option>
                                                    <option value="desc" {{ request('price_sort') == 'desc' ? 'selected' : '' }}>Highest</option>
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
                </div>
            </div>
        </div>
    </div>

    <!-- Search Results -->
    <div class="d-flex align-items-center justify-content-between">
        <button id="showMap" class="btn btn-info">
            <i class="bi bi-geo-alt"></i> Show Map
        </button>
    </div>
    <div id="map" style="height: 400px; display: none; margin-top: 20px;"></div>

    <br>

    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow">
                <div class="card-body">
                    <div class="row search-body">
                        <div class="col-lg-12">
                            <div class="search-result">
                                <div class="result-header mb-3">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="records">Showing: <b>{{ $products->count() }}</b> results found</div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="result-actions text-end">
                                                <!-- Sorting already applied in the search form -->
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Product List -->
                                <div class="table-responsive">
                                    <table class="table widget-26">
                                        <tbody>
                                            @foreach($products as $product)
                                                <tr>
                                                    <!-- Bootstrap Pill Icon instead of image -->
                                                    <td>
                                                        <i class="bi bi-capsule-pill" style="font-size: 2rem; color: #0eba9c;"></i>
                                                    </td>
                                                    <!-- Product Name and Pharmacy Info -->
                                                    <td>
                                                        <div class="widget-26-job-title">
                                                            <a href="#">{{ $product->brand_name}}</a>
                                                            <p class="m-0">{{ $product->pharmacy->name }}</p>
                                                        </div>
                                                    </td>
                                                    <!-- Available Badge -->
                                                    <td>
                                                        <span class="badge badge-success">Available</span>
                                                    </td>
                                                    <!-- Product Price -->
                                                    <td>
                                                        <div class="widget-26-job-salary">P{{ $product->price }}</div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Pagination -->
                    <nav class="d-flex justify-content-center mt-4">
                        {{ $products->links() }} <!-- Use the paginated products for pagination links -->
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
    <style>
        body {
            background: #dcdcdc;
            margin-top: 20px;
        }
        .shadow {
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }
        .table-responsive {
            margin-top: 1rem;
        }
        .widget-26-job-title a {
            color: #3c4142;
            font-size: 0.875rem;
        }
        .widget-26-job-info {
            color: #3c4142;
            font-size: 0.8125rem;
        }
        .widget-26-job-salary {
            font-weight: 600;
            color: #3c4142;
        }
        .badge-success {
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
        }
        .pagination {
            margin-top: 20px;
        }
        #showMap {
    background-color: #007bff; /* Blue background */
    color: white; /* White icon color */
    padding: 10px;
    border-radius: 50%; /* Circular button */
    box-shadow: 0px 4px 8px rgba(0, 123, 255, 0.5); /* Depth effect */
    transition: all 0.3s ease; /* Smooth transitions */
    display: inline-flex; /* Center the icon */
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

#showMap:hover {
    background-color: #00c6ff; /* Hover color change */
    box-shadow: 0px 6px 12px rgba(0, 123, 255, 0.7); /* Stronger shadow on hover */
    transform: translateY(-2px); /* Slight lift effect */
}
    </style>
@endsection

@section('scripts')
<script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-routing-machine/dist/leaflet-routing-machine.js"></script>
<script src="https://api.mapbox.com/mapbox-gl-js/v2.10.0/mapbox-gl.js"></script>
<script>
    document.getElementById('showMap').addEventListener('click', function() {
    var mapContainer = document.getElementById('map');
    var mapVisible = mapContainer.style.display === 'block';
    
    if (mapVisible) {
        mapContainer.style.display = 'none';
    } else {
        mapContainer.style.display = 'block';

        if (typeof map === 'undefined') {
            var map = L.map('map').setView([12.9185, 124.1372], 13); // Center on Gubat, Sorsogon, Philippines

            // Use Mapbox Streets style
            L.tileLayer('https://api.mapbox.com/styles/v1/mapbox/outdoors-v12/tiles/256/{z}/{x}/{y}@2x?access_token={accessToken}', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors, &copy; <a href="https://www.mapbox.com/about/maps/">Mapbox</a>',
                maxZoom: 18,
                accessToken: 'pk.eyJ1IjoiYWx4bmRyYWUiLCJhIjoiY20wM2drNGNhMDd1cjJqcHhzbTV4NXdlaCJ9.KLPS-IT0Y9_IGEwqBRn00A'
            }).addTo(map);
        }

        // Clear previous markers and routes
        map.eachLayer(function (layer) {
            if (layer instanceof L.Marker || layer instanceof L.Polyline) {
                map.removeLayer(layer);
            }
        });

        // Adding markers for available products
        @foreach($products as $product)
            L.marker([{{ $product->pharmacy->latitude }}, {{ $product->pharmacy->longitude }}])
            .addTo(map)
            .bindPopup('<b>{{ $product->pharmacy->name }}</b><br>{{ $product->pharmacy->address }}');
        @endforeach

        // Adding the user's current location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                console.log('Geolocation returned:', position.coords); // Debugging log
                if (position && position.coords) {
                    var userLocation = [position.coords.latitude, position.coords.longitude];

                    L.marker(userLocation)
                        .addTo(map)
                        .bindPopup('<b>You are here</b>')
                        .openPopup();

                    map.setView(userLocation, 13);

                    // Routing to nearest pharmacy
                    @foreach($products as $product)
                        var pharmacyLocation = [{{ $product->pharmacy->latitude }}, {{ $product->pharmacy->longitude }}];
                        L.Routing.control({
                            waypoints: [
                                L.latLng(userLocation),
                                L.latLng(pharmacyLocation)
                            ],
                            routeWhileDragging: true
                        }).addTo(map);
                    @endforeach
                }
            });
        }
    }
});
</script>
@endsection
