@extends('layouts.master')

@section('title', 'Struk Penjualan')

@section('action')
<div class="gap-2 d-flex">
    <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-primary" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <polyline points="6,9 6,2 18,2 18,9" />
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
            <rect x="6" y="14" width="12" height="8" />
        </svg>
        Cetak Struk
    </a>
    <a href="{{ route('sales.history') }}" class="btn btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M19 12H5m7-7l-7 7 7 7" />
        </svg>
        Kembali
    </a>
    <a href="{{ route('sales.pos') }}" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M3 3h18l-1 13H4L3 3z" />
            <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
            <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
        </svg>
        Penjualan Baru
    </a>
</div>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <!-- Header -->
                <div class="mb-4 text-center">
                    <h2 class="mb-1 card-title">STRUK PENJUALAN</h2>
                    @if(isset($websiteSetting))
                    @if($websiteSetting->website_name)
                    <p class="mb-1 small text-muted">{{ $websiteSetting->website_name }}</p>
                    @endif
                    @if($websiteSetting->address)
                    <p class="mb-1 small text-muted">{{ $websiteSetting->address }}</p>
                    @endif
                    @if($websiteSetting->phone)
                    <p class="mb-1 small text-muted">Telp: {{ $websiteSetting->phone }}</p>
                    @endif
                    @if($websiteSetting->email)
                    <p class="small text-muted">Email: {{ $websiteSetting->email }}</p>
                    @endif
                    @endif
                    <hr>
                </div>

                <!-- Transaction Info -->
                <div class="mb-4 row">
                    <div class="col-6">
                        <p class="mb-1"><strong>ID Transaksi:</strong></p>
                        <p class="text-primary fw-bold">{{ $sale->transaction_id }}</p>

                        <p class="mb-1"><strong>Tanggal:</strong></p>
                        <p>{{ $sale->created_at->format('d/m/Y H:i:s') }}</p>

                        <p class="mb-1"><strong>Kasir:</strong></p>
                        <p>{{ $sale->user->name }}</p>
                    </div>
                    <div class="col-6 text-end">
                        <p class="mb-1"><strong>Konsumen:</strong></p>
                        <p>{{ $sale->customer ? $sale->customer->name : 'Konsumen Umum' }}</p>

                        @if($sale->customer && $sale->customer->phone)
                        <p class="mb-1"><strong>Telepon:</strong></p>
                        <p>{{ $sale->customer->phone }}</p>
                        @endif

                        <p class="mb-1"><strong>Status:</strong></p>
                        @if($sale->status === 'completed')
                        <span class="badge bg-success">{{ $sale->formatted_status }}</span>
                        @elseif($sale->status === 'pending')
                        <span class="badge bg-warning">{{ $sale->formatted_status }}</span>
                        @else
                        <span class="badge bg-danger">{{ $sale->formatted_status }}</span>
                        @endif
                    </div>
                </div>

                <!-- Items -->
                <div class="mb-4 table-responsive">
                    <table class="table table-sm">
                        <thead class="table-light">
                            <tr>
                                <th>Produk</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->salesItems as $item)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $item->product_name }}</strong>
                                        @if($item->product_sku)
                                        <br><small class="text-muted">{{ $item->product_sku }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td class="text-center">{{ $item->quantity }}</td>
                                <td class="text-end">Rp {{ number_format($item->unit_price, 0, ',', '.') }}</td>
                                <td class="text-end">Rp {{ number_format($item->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Summary -->
                <div class="row">
                    <div class="col-6">
                        @if($sale->notes)
                        <p class="mb-1"><strong>Catatan:</strong></p>
                        <p class="text-muted">{{ $sale->notes }}</p>
                        @endif
                    </div>
                    <div class="col-6">
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td class="text-end"><strong>Subtotal:</strong></td>
                                <td class="text-end">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                            </tr>
                            @if($sale->tax_amount > 0)
                            <tr>
                                <td class="text-end"><strong>Pajak:</strong></td>
                                <td class="text-end">Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($sale->discount_amount > 0)
                            <tr>
                                <td class="text-end"><strong>Diskon:</strong></td>
                                <td class="text-end text-danger">-Rp {{ number_format($sale->discount_amount, 0, ',',
                                    '.') }}</td>
                            </tr>
                            @endif
                            <tr class="border-top">
                                <td class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong class="text-success fs-5">Rp {{
                                        number_format($sale->total_amount, 0, ',', '.') }}</strong></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <!-- Payment Info -->
                <div class="p-3 rounded bg-light">
                    <div class="row">
                        <div class="col-6">
                            <p class="mb-1"><strong>Metode Pembayaran:</strong></p>
                            <span class="badge bg-info">{{ $sale->formatted_payment_method }}</span>
                        </div>
                        <div class="col-6 text-end">
                            <p class="mb-1"><strong>Jumlah Bayar:</strong> <span class="text-success">Rp {{
                                    number_format($sale->paid_amount, 0, ',', '.') }}</span></p>
                            @if($sale->change_amount > 0)
                            <p class="mb-0"><strong>Kembalian:</strong> <span class="text-primary">Rp {{
                                    number_format($sale->change_amount, 0, ',', '.') }}</span></p>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Footer -->
                <div class="pt-3 mt-4 text-center border-top">
                    <p class="mb-1 text-muted small">Terima kasih atas pembelian Anda!</p>
                    <p class="mb-0 text-muted small">{{ now()->format('d/m/Y H:i:s') }}</p>
                </div>
            </div>
        </div>

        <!-- Actions -->
        @if($sale->status === 'completed')
        <div class="mt-3 card">
            <div class="card-body">
                <h5 class="card-title text-danger">Zona Bahaya</h5>
                <p class="card-text">Membatalkan transaksi akan mengembalikan stok produk dan mengubah status menjadi
                    dibatalkan.</p>
                <button class="btn btn-danger" onclick="cancelSale()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M15 9l-6 6M9 9l6 6" />
                    </svg>
                    Batalkan Transaksi
                </button>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('addon-script')
<script>
    function cancelSale() {
    if (confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan dan transaksi tidak dapat dibatalkan lagi.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("sales.cancel", $sale->id) }}';

        // Add CSRF token
        const csrfInput = document.createElement('input');
        csrfInput.type = 'hidden';
        csrfInput.name = '_token';
        csrfInput.value = '{{ csrf_token() }}';
        form.appendChild(csrfInput);

        document.body.appendChild(form);
        form.submit();
    }
}
</script>
@endpush
