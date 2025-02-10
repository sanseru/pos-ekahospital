<!DOCTYPE html>
<html>
<head>
    <title>Struk Pembayaran {{ $sale->invoice_number }}</title>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            font-size: 12px;
            line-height: 1.4;
        }

        .invoice-box {
            width: 80mm;
            margin: 0 auto;
            padding: 8px;
            background: #fff;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header img {
            max-width: 150px;
            margin-bottom: 5px;
        }

        .header h1 {
            font-size: 16px;
            margin: 0;
            font-weight: bold;
        }

        .header p {
            margin: 3px 0;
            font-size: 11px;
        }

        .info {
            margin-bottom: 10px;
            font-size: 11px;
        }

        .info p {
            margin: 2px 0;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        .table th {
            text-align: left;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
            padding: 5px 0;
        }

        .table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .total-section {
            border-top: 1px dashed #000;
            margin-top: 10px;
            padding-top: 10px;
        }

        .total-line {
            display: flex;
            justify-content: space-between;
            margin: 2px 0;
        }

        .footer {
            text-align: center;
            margin-top: 20px;
            border-top: 1px dashed #000;
            padding-top: 10px;
            font-size: 10px;
        }

        .bold {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="invoice-box">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
            <h1>{{ config('app.name') }}</h1>
            <p>Jl. Contoh No. 123, Jakarta</p>
            <p>Telp: (021) 123-4567</p>
        </div>

        <!-- Invoice Info -->
        <div class="info">
            <p>No: {{ $sale->invoice_number }}</p>
            <p>Kasir: {{ auth()->user()->name }}</p>
            <p>Tanggal: {{ $sale->created_at->format('d/m/Y H:i') }}</p>
            <p>Pelanggan: {{ $sale->customer_name ?: 'Umum' }}</p>
        </div>

        <!-- Items Table -->
        <table class="table">
            <thead>
                <tr>
                    <th style="width: 40%">Item</th>
                    <th style="width: 20%">Qty</th>
                    <th style="width: 40%; text-align: right">Subtotal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($sale->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</td>
                    <td style="text-align: right">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <!-- Total Section -->
        <div class="total-section">
            <div class="total-line">
                <span>Total</span>
                <span class="bold">Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
            <div class="total-line">
                <span>Metode Pembayaran</span>
                <span>{{ __('payment_methods ' . $sale->payment_method) }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda</p>
            <p>Barang yang sudah dibeli tidak dapat ditukar/dikembalikan</p>
            <p>Struk ini merupakan bukti pembayaran yang sah</p>
            <p>{{ now()->format('d/m/Y H:i:s') }}</p>
        </div>
    </div>

    @if(request()->has('print'))
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
    @endif
</body>
</html>
