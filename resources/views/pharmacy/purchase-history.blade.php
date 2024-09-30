@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <h4>Purchases</h4><hr>
    <br>
    
    <h5>Total Purchases Per Day</h5>
    <div class="accordion" id="totalPurchasesAccordion">
        <div class="accordion-item">
            <h2 class="accordion-header" id="headingTotalPurchases">
                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTotalPurchases" aria-expanded="true" aria-controls="collapseTotalPurchases">
                    Total Purchases Per Day
                </button>
            </h2>
            <div id="collapseTotalPurchases" class="accordion-collapse collapse show" aria-labelledby="headingTotalPurchases" data-bs-parent="#totalPurchasesAccordion">
                <div class="accordion-body">
                    <table class="table table-light table-striped table-bordered mb-0">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($totalsPerDay as $date => $total)
                                <tr>
                                    <td>{{ $date }}</td>
                                    <td>P {{ number_format($total, 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <br>
    
    <h5>Detailed Purchases</h5>
    <div class="accordion" id="purchaseHistoryAccordion">
        @foreach($purchaseHistories->groupBy(function($date) {
            return \Carbon\Carbon::parse($date->purchased_at)->format('Y-m-d');
        }) as $date => $dailyPurchases)
            <div class="accordion-item">
                <h2 class="accordion-header" id="heading{{ $date }}">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $date }}" aria-expanded="true" aria-controls="collapse{{ $date }}">
                     {{ $date }}
                    </button>
                </h2>
                <div id="collapse{{ $date }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $date }}" data-bs-parent="#purchaseHistoryAccordion">
                    <div class="accordion-body">
                        <table class="table table-bordered table-striped mb-0">
                            <thead>
                                <tr>
                                    <th>Brand Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Total Cost</th>
                                    <th>Purchased At</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dailyPurchases as $history)
                                    <tr>
                                        <td>{{ $history->product->brand_name }}</td>
                                        <td>{{ $history->quantity }}</td>
                                        <td>P {{ number_format($history->price, 2) }}</td>
                                        <td>P {{ number_format($history->total_cost, 2) }}</td>
                                        <td>{{ $history->purchased_at->format('Y-m-d H:i:s') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
@endsection

@section('styles')
<style>
    #totalPurchasesAccordion .accordion-button {
        font-weight: bold;
    }

    #purchaseHistoryAccordion .accordion-button {
        font-weight: bold;
    }

    .accordion-body table {
        margin-bottom: 0;
    }
</style>
@endsection
