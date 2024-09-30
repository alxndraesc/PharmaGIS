<!-- resources/views/pharmacy/weekly-sales.blade.php -->
@extends('layouts.pharmacy')

@section('title', 'Weekly Sales')

@section('content')
<div class="container mt-4" id="sales-page">
    <div class="card">
        <div class="card-header">
            <h5>Weekly Sales</h5>
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Week</th>
                        <th scope="col">Total Sales</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($weeklyTotals as $weekly)
                        <tr>
                            <td>{{ $weekly->week }}</td>
                            <td>{{ number_format($weekly->total_price, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        margin-bottom: 1.5rem;
    }

    .card-header {
        font-size: 1.25rem;
        font-weight: bold;
    }

    .table thead th {
        font-weight: bold;
    }
</style>
@endsection
