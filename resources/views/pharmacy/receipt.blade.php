@extends('layouts.pharmacy')

@section('content')
    <style>
        .receipt-container {
            font-family: Courier, sans-serif;
            padding: 20px;
            border: 1px solid #ddd;
            width: 80%;
            margin: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .text-right {
            text-align: right;
        }
        .btn {
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <h2 style="text-align: center;">Receipt</h2>
        <p>{{ $receipt['date'] }}</p>
        <p>{{ $receipt['pharmacyName'] }}</p>
        <p>{{ $receipt['address'] }}</p>
        <p>{{ $receipt['receiptNumber'] }}</p>
        <hr>
        <table>
            <thead>
                <tr>
                    <th>Item</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($receipt['items'] as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">Php {{ number_format($item['price'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <p class="text-right"><strong>Total:</strong> Php {{ number_format($receipt['totalCost'], 2) }}</p>
        <div class="text-right">
    <a href="{{ route('receipt.download', ['id' => $receipt['receiptNumber']]) }}" class="btn">Download Receipt</a>
</div>
    </div>
@endsection