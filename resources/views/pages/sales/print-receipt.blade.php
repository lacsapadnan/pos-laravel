<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cetak Struk - {{ $sale->transaction_id }}</title>
    <style>
        @media print {
            body {
                margin: 0;
            }

            .no-print {
                display: none;
            }
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.3;
            margin: 0;
            padding: 10px;
            max-width: 80mm;
            background: white;
        }

        .header {
            text-align: center;
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            margin: 0 0 5px 0;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .transaction-info {
            margin-bottom: 15px;
            font-size: 11px;
        }

        .transaction-info div {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .items {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .item {
            margin-bottom: 8px;
        }

        .item-name {
            font-weight: bold;
            margin-bottom: 2px;
        }

        .item-details {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
        }

        .summary {
            margin-bottom: 15px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
        }

        .summary-row.total {
            border-top: 1px solid #000;
            padding-top: 3px;
            font-weight: bold;
            font-size: 13px;
        }

        .payment-info {
            margin-bottom: 15px;
            border-bottom: 1px dashed #000;
            padding-bottom: 10px;
        }

        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 10px;
        }

        .print-controls {
            text-align: center;
            margin: 20px 0;
        }

        .btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 4px;
            cursor: pointer;
            margin: 0 5px;
            text-decoration: none;
            display: inline-block;
        }

        .btn:hover {
            background: #0056b3;
        }

        .btn-secondary {
            background: #6c757d;
        }

        .btn-secondary:hover {
            background: #545b62;
        }
    </style>
</head>

<body>
    <div class="print-controls no-print">
        <button class="btn" onclick="window.print()">🖨️ Cetak</button>
        <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="receipt">
        <!-- Header -->
        <div class="header">
            <h1>{{ config('app.name', 'LARAVEL POS') }}</h1>
            @if(isset($websiteSetting))
            @if($websiteSetting->address)
            <p>{{ $websiteSetting->address }}</p>
            @endif
            @if($websiteSetting->phone)
            <p>Telp: {{ $websiteSetting->phone }}</p>
            @endif
            @if($websiteSetting->email)
            <p>{{ $websiteSetting->email }}</p>
            @endif
            @endif
        </div>

        <!-- Transaction Info -->
        <div class="transaction-info">
            <div>
                <span>No. Transaksi:</span>
                <span>{{ $sale->transaction_id }}</span>
            </div>
            <div>
                <span>Tanggal:</span>
                <span>{{ $sale->created_at->format('d/m/Y H:i') }}</span>
            </div>
            <div>
                <span>Kasir:</span>
                <span>{{ $sale->user->name }}</span>
            </div>
            <div>
                <span>Konsumen:</span>
                <span>{{ $sale->customer ? $sale->customer->name : 'Umum' }}</span>
            </div>
        </div>

        <!-- Items -->
        <div class="items">
            @foreach($sale->salesItems as $item)
            <div class="item">
                <div class="item-name">{{ $item->product_name }}</div>
                @if($item->product_sku)
                <div style="font-size: 10px; color: #666;">{{ $item->product_sku }}</div>
                @endif
                <div class="item-details">
                    <span>{{ $item->quantity }} x {{ number_format($item->unit_price, 0, ',', '.') }}</span>
                    <span>{{ number_format($item->total_price, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Summary -->
        <div class="summary">
            <div class="summary-row">
                <span>Subtotal:</span>
                <span>{{ number_format($sale->subtotal, 0, ',', '.') }}</span>
            </div>
            @if($sale->tax_amount > 0)
            <div class="summary-row">
                <span>Pajak:</span>
                <span>{{ number_format($sale->tax_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($sale->discount_amount > 0)
            <div class="summary-row">
                <span>Diskon:</span>
                <span>-{{ number_format($sale->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="summary-row total">
                <span>TOTAL:</span>
                <span>{{ number_format($sale->total_amount, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="summary-row">
                <span>{{ $sale->formatted_payment_method }}:</span>
                <span>{{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
            </div>
            @if($sale->change_amount > 0)
            <div class="summary-row">
                <span>Kembalian:</span>
                <span>{{ number_format($sale->change_amount, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        @if($sale->notes)
        <div style="margin-bottom: 15px; border-bottom: 1px dashed #000; padding-bottom: 10px;">
            <div style="font-size: 11px;">
                <strong>Catatan:</strong><br>
                {{ $sale->notes }}
            </div>
        </div>
        @endif

        <!-- Footer -->
        <div class="footer">
            <p>Terima kasih atas kunjungan Anda!</p>
            <p>{{ $sale->created_at->format('d/m/Y H:i:s') }}</p>
            <p>Status: {{ $sale->formatted_status }}</p>
        </div>
    </div>

    <script>
        // Auto print when page loads (optional)
        // window.addEventListener('load', function() {
        //     setTimeout(function() {
        //         window.print();
        //     }, 500);
        // });
    </script>
</body>

</html>
