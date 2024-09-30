@extends('layouts.pharmacy')

@section('title', 'Monthly Sales')

@section('content')
<div class="container mt-4" id="monthly-sales-page">
    <h5>Monthly Sales</h5><hr>
    <br>

    <div class="accordion" id="monthlySalesAccordion">
        @php
            $groupedTotals = $monthlyTotals->groupBy(function ($item) {
                return $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT);
            });
        @endphp

        @foreach($groupedTotals as $yearMonth => $sales)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $yearMonth }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $yearMonth }}" aria-expanded="true" aria-controls="collapse{{ $yearMonth }}">
                        Sales for {{ \Carbon\Carbon::createFromFormat('Y-m', $yearMonth)->format('F Y') }}
                    </button>
                </h2>
                <div id="collapse{{ $yearMonth }}" class="accordion-collapse collapse show" aria-labelledby="heading{{ $yearMonth }}" data-bs-parent="#monthlySalesAccordion">
                    <div class="accordion-body">
                        <table class="table table-light table-striped table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Total Quantity Sold</th>
                                    <th scope="col">Total Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sales as $sale)
                                    <tr>
                                        <td>{{ $sale->product_name }}</td>
                                        <td>{{ $sale->total_quantity }}</td>
                                        <td>{{ number_format($sale->total_cost, 2) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection

@section('styles')
<style>
    #monthly-sales-page {
        font-family: 'Courier New', Courier, monospace;
    }

    .accordion-button {
        font-weight: bold;
    }

    .accordion-body table {
        margin-bottom: 0;
    }
</style>
@endsection
