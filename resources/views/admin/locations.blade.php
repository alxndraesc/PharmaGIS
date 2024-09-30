@extends('layouts.admin')

@section('content')
<div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h5>Locations</h5>
</div>
    <br>
    <div class="row">
        <div class="col-md-12">
            <!-- Map Card -->
            <div class="card shadow mb-4">
                <div class="card-header">
                    <h5>Map</h5>
                </div>
                <div class="card-body p-0">
                    <div id="map" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <!-- Registered Pharmacies Table Card -->
            <div class="card shadow">
                <div class="card-header">
                    <h5>Registered Pharmacies</h5>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Address</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pharmacies as $pharmacy)
                                <tr>
                                    <td>{{ $pharmacy->name }}</td>
                                    <td>{{ $pharmacy->address }}</td>
                                    <td>{{ $pharmacy->latitude }}</td>
                                    <td>{{ $pharmacy->longitude }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Mapbox Library -->
<link href='https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.css' rel='stylesheet' />
<script src='https://api.mapbox.com/mapbox-gl-js/v2.4.1/mapbox-gl.js'></script>

<script>
    mapboxgl.accessToken = 'pk.eyJ1IjoiYWx4bmRyYWUiLCJhIjoiY20wM2drNGNhMDd1cjJqcHhzbTV4NXdlaCJ9.KLPS-IT0Y9_IGEwqBRn00A';
    var map = new mapboxgl.Map({
        container: 'map',
        style: 'mapbox://styles/mapbox/outdoors-v12', 
        center: [124.1226879, 12.9208355], 
        zoom: 12 
    });

    @foreach($pharmacies as $pharmacy)
        new mapboxgl.Marker()
            .setLngLat([{{ $pharmacy->longitude }}, {{ $pharmacy->latitude }}])
            .setPopup(new mapboxgl.Popup().setHTML("<b>{{ $pharmacy->name }}</b><br>{{ $pharmacy->address }}"))
            .addTo(map);
    @endforeach
</script>
@endsection
