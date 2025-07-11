@extends('layouts.master')

@section('title', 'Detail Penjualan')

@section('action')
<div class="gap-2 d-flex">
    <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-success">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
            <polyline points="14,2 14,8 20,8" />
            <line x1="16" y1="13" x2="8" y2="13" />
            <line x1="16" y1="17" x2="8" y2="17" />
            <polyline points="10,9 9,9 8,9" />
        </svg>
        Lihat Struk
    </a>
    <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-primary" target="_blank">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <polyline points="6,9 6,2 18,2 18,9" />
            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
            <rect x="6" y="14" width="12" height="8" />
        </svg>
        Cetak
    </a>
    <a href="{{ route('sales.history') }}" class="btn btn-outline-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2">
            <path d="M19 12H5m7-7l-7 7 7 7" />
        </svg>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <!-- Transaction Overview -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Detail Transaksi</h3>
                <div class="card-actions">
                    @if($sale->status === 'completed')
                    <span class="badge bg-success">{{ $sale->formatted_status }}</span>
                    @elseif($sale->status === 'pending')
                    <span class="badge bg-warning">{{ $sale->formatted_status }}</span>
                    @else
                    <span class="badge bg-danger">{{ $sale->formatted_status }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <!-- Transaction Info -->
                <div class="mb-4 row">
                    <div class="col-md-6">
                        <h5>Informasi Transaksi</h5>
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>ID Transaksi:</strong></td>
                                <td>{{ $sale->transaction_id }}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>{{ $sale->created_at->format('d/m/Y H:i:s') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Kasir:</strong></td>
                                <td>{{ $sale->user->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    @if($sale->status === 'completed')
                                    <span class="badge bg-success">{{ $sale->formatted_status }}</span>
                                    @elseif($sale->status === 'pending')
                                    <span class="badge bg-warning">{{ $sale->formatted_status }}</span>
                                    @else
                                    <span class="badge bg-danger">{{ $sale->formatted_status }}</span>
                                    @endif
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Informasi Konsumen</h5>
                        @if($sale->customer)
                        <table class="table table-sm table-borderless">
                            <tr>
                                <td width="40%"><strong>Nama:</strong></td>
                                <td>{{ $sale->customer->name }}</td>
                            </tr>
                            <tr>
                                <td><strong>Email:</strong></td>
                                <td>{{ $sale->customer->email ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Telepon:</strong></td>
                                <td>{{ $sale->customer->phone ?? '-' }}</td>
                            </tr>
                            <tr>
                                <td><strong>Alamat:</strong></td>
                                <td>{{ $sale->customer->address ?? '-' }}</td>
                            </tr>
                        </table>
                        @else
                        <p class="text-muted">Konsumen Umum</p>
                        @endif
                    </div>
                </div>

                <!-- Items -->
                <h5>Detail Produk</h5>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>SKU</th>
                                <th class="text-center">Qty</th>
                                <th class="text-end">Harga Satuan</th>
                                <th class="text-end">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->salesItems as $item)
                            <tr>
                                <td>
                                    <div>
                                        <strong>{{ $item->product_name }}</strong>
                                        @if($item->product && $item->product->category)
                                        <br><small class="text-muted">{{ $item->product->category->name }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <code>{{ $item->product->code ?? '-' }}</code>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-secondary">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-end">
                                    Rp {{ number_format($item->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="text-end">
                                    <strong>Rp {{ number_format($item->total_price, 0, ',', '.') }}</strong>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Total Item:</th>
                                <th class="text-end">{{ $sale->salesItems->sum('quantity') }} pcs</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                @if($sale->notes)
                <div class="mt-3">
                    <h5>Catatan</h5>
                    <div class="alert alert-info">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="me-2">
                            <circle cx="12" cy="12" r="10" />
                            <path d="M12 16v-4M12 8h.01" />
                        </svg>
                        {{ $sale->notes }}
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Payment Summary -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ringkasan Pembayaran</h3>
            </div>
            <div class="card-body">
                <table class="table table-sm table-borderless">
                    <tr>
                        <td>Subtotal:</td>
                        <td class="text-end">Rp {{ number_format($sale->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @if($sale->tax_amount > 0)
                    <tr>
                        <td>Pajak:</td>
                        <td class="text-end">Rp {{ number_format($sale->tax_amount, 0, ',', '.') }}</td>
                    </tr>
                    @endif
                    @if($sale->discount_amount > 0)
                    <tr>
                        <td>Diskon:</td>
                        <td class="text-end text-danger">-Rp {{ number_format($sale->discount_amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @endif
                    <tr class="border-top">
                        <td><strong>Total:</strong></td>
                        <td class="text-end"><strong class="text-success fs-4">Rp {{ number_format($sale->total_amount,
                                0, ',', '.') }}</strong></td>
                    </tr>
                </table>

                <div class="p-3 mt-3 rounded bg-light">
                    <div class="mb-2 d-flex justify-content-between">
                        <span><strong>Metode Pembayaran:</strong></span>
                        <span class="badge bg-info">{{ $sale->formatted_payment_method }}</span>
                    </div>
                    <div class="mb-2 d-flex justify-content-between">
                        <span>Jumlah Bayar:</span>
                        <span class="text-success">Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}</span>
                    </div>
                    @if($sale->change_amount > 0)
                    <div class="d-flex justify-content-between">
                        <span>Kembalian:</span>
                        <span class="text-primary">Rp {{ number_format($sale->change_amount, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="mt-3 card">
            <div class="card-header">
                <h3 class="card-title">Aksi Cepat</h3>
            </div>
            <div class="card-body">
                <div class="gap-2 d-grid">
                    <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-outline-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="me-1">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14,2 14,8 20,8" />
                        </svg>
                        Lihat Struk
                    </a>
                    <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-outline-primary" target="_blank">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="me-1">
                            <polyline points="6,9 6,2 18,2 18,9" />
                            <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                            <rect x="6" y="14" width="12" height="8" />
                        </svg>
                        Cetak Struk
                    </a>
                    <a href="{{ route('sales.pos') }}" class="btn btn-success">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" class="me-1">
                            <path d="M3 3h18l-1 13H4L3 3z" />
                            <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                            <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                        </svg>
                        Penjualan Baru
                    </a>
                </div>
            </div>
        </div>

        <!-- Danger Zone -->
        @if($sale->status === 'completed')
        <div class="mt-3 card border-danger">
            <div class="text-white card-header bg-danger">
                <h3 class="card-title">Zona Bahaya</h3>
            </div>
            <div class="card-body">
                <p class="text-muted">Membatalkan transaksi akan mengembalikan stok produk dan mengubah status
                    transaksi.</p>
                <button class="btn btn-danger w-100" onclick="cancelSale()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" class="me-1">
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
    if (confirm('Apakah Anda yakin ingin membatalkan transaksi ini?\n\nPeringatan:\n- Stok produk akan dikembalikan\n- Status transaksi akan berubah menjadi "Dibatalkan"\n- Tindakan ini tidak dapat dibatalkan')) {
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
