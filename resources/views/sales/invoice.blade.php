<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $sale->invoice_number }}</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .invoice-box { max-width: 800px; margin: auto; padding: 30px; }
        .table { width: 100%; border-collapse: collapse; }
        .table th, .table td { padding: 10px; text-align: left; border-bottom: 1px solid #ddd; }
    </style>
</head>
<body>
    <div class="invoice-box">
        <h1>Invoice #{{ $sale->invoice_number }}</h1>
        <p>Date: {{ $sale->created_at->format('Y-m-d H:i:s') }}</p>
        <p>Customer: {{ $sale->customer_name }}</p>

        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>Rp{{ number_format($item->price, 2) }}</td>
                    <td>Rp{{ number_format($item->subtotal, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3" style="text-align: right;"><strong>Total:</strong></td>
                    <td><strong>Rp{{ number_format($sale->total_amount, 2) }}</strong></td>
                </tr>
            </tfoot>
        </table>

        <p>Payment Method: {{ ucfirst($sale->payment_method) }}</p>
        <p>Thank you for your business!</p>
    </div>
</body>
</html>
