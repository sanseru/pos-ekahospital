<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Penjualan</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #4a5568;
            padding-bottom: 20px;
        }
        .header h1 {
            color: #2d3748;
            margin: 0;
            font-size: 24px;
        }
        .header p {
            color: #4a5568;
            margin: 5px 0;
        }
        .info-section {
            margin-bottom: 30px;
        }
        .info-section table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-section td {
            padding: 5px;
        }
        .summary-cards {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }
        .summary-card {
            width: 30%;
            padding: 15px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
        }
        .summary-card h3 {
            margin: 0;
            color: #4a5568;
            font-size: 14px;
        }
        .summary-card p {
            margin: 5px 0 0;
            font-size: 18px;
            font-weight: bold;
            color: #2d3748;
        }
        .sales-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .sales-table th {
            background-color: #f7fafc;
            padding: 12px;
            text-align: left;
            font-size: 12px;
            color: #4a5568;
            border-bottom: 2px solid #e2e8f0;
        }
        .sales-table td {
            padding: 12px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
            color: #2d3748;
        }
        .sales-table tr:nth-child(even) {
            background-color: #f7fafc;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #718096;
        }
        .page-break {
            page-break-after: always;
        }
        .summary-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .summary-table td {
            padding: 10px;
            border: 1px solid #e2e8f0;
            text-align: center;
            width: 33.33%;
        }
        .summary-title {
            color: #4a5568;
            font-size: 14px;
            margin-bottom: 5px;
            font-weight: normal;
        }
        .summary-value {
            font-size: 16px;
            font-weight: bold;
            color: #2d3748;
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <p>Laporan Penjualan</p>
        <p>Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
    </div>

    <div class="info-section">
        <table class="summary-table">
            <tr>
                <td>
                    <h3 class="summary-title">Total Penjualan</h3>
                    <p class="summary-value">Rp {{ number_format($sales->sum('total_amount'), 0, ',', '.') }}</p>
                </td>
                <td>
                    <h3 class="summary-title">Total Transaksi</h3>
                    <p class="summary-value">{{ number_format($sales->count(), 0, ',', '.') }}</p>
                </td>
                <td>
                    <h3 class="summary-title">Rata-rata Transaksi</h3>
                    <p class="summary-value">Rp {{ $sales->count() > 0 ? number_format($sales->sum('total_amount') / $sales->count(), 0, ',', '.') : '0' }}</p>
                </td>
            </tr>
        </table>
    </div>

    <table class="sales-table">
        <thead>
            <tr>
                <th>No. Invoice</th>
                <th>Tanggal</th>
                <th>Pelanggan</th>
                <th>Metode Pembayaran</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sales as $sale)
            <tr>
                <td>{{ $sale->invoice_number }}</td>
                <td>{{ $sale->created_at->format('d/m/Y H:i') }}</td>
                <td>{{ $sale->customer_name ?: 'Umum' }}</td>
                <td>{{ ucfirst($sale->payment_method) }}</td>
                <td>Rp {{ number_format($sale->total_amount, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="page-break"></div>

    <div class="header">
        <h1>Detail Penjualan per Produk</h1>
    </div>

    <table class="sales-table">
        <thead>
            <tr>
                <th>Nama Produk</th>
                <th>Jumlah Terjual</th>
                <th>Total Penjualan</th>
                <th>Rata-rata Harga</th>
            </tr>
        </thead>
        <tbody>
            @php
                $productSummary = collect();
                foreach($sales as $sale) {
                    foreach($sale->items as $item) {
                        $productSummary->push([
                            'name' => $item->product->name,
                            'quantity' => $item->quantity,
                            'total' => $item->subtotal
                        ]);
                    }
                }
                $productSummary = $productSummary->groupBy('name')
                    ->map(function($items) {
                        return [
                            'quantity' => $items->sum('quantity'),
                            'total' => $items->sum('total'),
                            'average' => $items->sum('total') / $items->sum('quantity')
                        ];
                    });
            @endphp

            @foreach($productSummary as $name => $summary)
            <tr>
                <td>{{ $name }}</td>
                <td>{{ number_format($summary['quantity'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($summary['total'], 0, ',', '.') }}</td>
                <td>Rp {{ number_format($summary['average'], 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Dokumen ini dicetak pada {{ now()->format('d/m/Y H:i:s') }}</p>
        <p>{{ config('app.name') }} - Laporan Penjualan</p>
    </div>
</body>
</html>
