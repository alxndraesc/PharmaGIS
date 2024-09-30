@extends('layouts.pharmacy')

@section('content')
<br>
<div class="container">
    <h5>Inventory History</h5><hr>
    <br>
    @if($inventoryHistories->isEmpty())
        <p>No inventory history found.</p>
    @else
        <div class="accordion" id="inventoryHistoryAccordion">
            @foreach($inventoryHistories->groupBy(function($date) {
                return \Carbon\Carbon::parse($date->updated_at)->format('Y-m-d');
            }) as $date => $dailyHistories)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $date }}">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $date }}" aria-expanded="true" aria-controls="collapse{{ $date }}">
                        {{ $date }}
                        </button>
                    </h2>
                    <div id="collapse{{ $date }}" class="accordion-collapse collapse" aria-labelledby="heading{{ $date }}" data-bs-parent="#inventoryHistoryAccordion">
                        <div class="accordion-body">
                            <div class="table-responsive">
                                <table class="table table-light table-striped table-bordered mb-0">
                                    <thead>
                                        <tr>
                                            <th>Brand</th>
                                            <th>Quantity</th>
                                            <th>Status</th>
                                            <th>Updated At</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($dailyHistories as $history)
                                            <tr>
                                                <td>{{ $history->product->brand_name }}</td>
                                                <td>{{ $history->quantity }}</td>
                                                <td>{{ $history->status }}</td>
                                                <td>{{ $history->updated_at->format('Y-m-d H:i:s') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    #inventoryHistoryAccordion .accordion-button {
        font-weight: bold;
    }

    .accordion-body table {
        margin-bottom: 0;
    }
</style>
@endsection
