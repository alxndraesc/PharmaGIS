<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .receipt-container {
            width: 100%;
            border: 1px solid #ddd;
            padding: 20px;
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
    </style>
</head>
<body>
    <div class="receipt-container">
        <h2 style="text-align: center;">Receipt</h2>
        <p><strong>Date:</strong> {{ $date }}</p>
        <p><strong>Pharmacy:</strong> {{ $pharmacyName }}</p>
        <p><strong>Address:</strong> {{ $address }}</p>
        <p><strong>Receipt Number:</strong> {{ $receiptNumber }}</p>
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
                @foreach ($items as $item)
                <tr>
                    <td>{{ $item['name'] }}</td>
                    <td class="text-right">{{ $item['quantity'] }}</td>
                    <td class="text-right">Php {{ number_format($item['price'], 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
        <p class="text-right"><strong>Total:</strong> Php {{ number_format($totalCost, 2) }}</p>
    </div>
</body>
</html>
