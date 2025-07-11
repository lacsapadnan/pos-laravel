@extends('layouts.master')

@section('title', 'Riwayat Penjualan')

@section('action')
<a href="{{ route('sales.pos') }}" class="btn btn-primary">
    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
        stroke-width="2">
        <path d="M3 3h18l-1 13H4L3 3z" />
        <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
        <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
    </svg>
    Kembali ke POS
</a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Riwayat Transaksi Penjualan</h3>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="sales-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>ID Transaksi</th>
                        <th>Tanggal</th>
                        <th>Konsumen</th>
                        <th>Kasir</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($sales as $sale)
                    <tr>
                        <td>
                            <span class="fw-bold">{{ $sale->transaction_id }}</span>
                        </td>
                        <td>
                            {{ $sale->created_at->format('d/m/Y H:i') }}
                            <br>
                            <small class="text-muted">{{ $sale->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($sale->customer)
                            <div>
                                <strong>{{ $sale->customer->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $sale->customer->phone }}</small>
                            </div>
                            @else
                            <span class="text-muted">Konsumen Umum</span>
                            @endif
                        </td>
                        <td>
                            <div>
                                <strong>{{ $sale->user->name }}</strong>
                                <br>
                                <small class="text-muted">{{ $sale->user->email }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="fw-bold text-success">
                                Rp {{ number_format($sale->total_amount, 0, ',', '.') }}
                            </span>
                            <br>
                            <small class="text-muted">
                                {{ $sale->salesItems->sum('quantity') }} item
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $sale->formatted_payment_method }}</span>
                            <br>
                            <small class="text-muted">
                                Bayar: Rp {{ number_format($sale->paid_amount, 0, ',', '.') }}
                            </small>
                            @if($sale->change_amount > 0)
                            <br>
                            <small class="text-success">
                                Kembalian: Rp {{ number_format($sale->change_amount, 0, ',', '.') }}
                            </small>
                            @endif
                        </td>
                        <td>
                            @if($sale->status === 'completed')
                            <span class="badge bg-success">{{ $sale->formatted_status }}</span>
                            @elseif($sale->status === 'pending')
                            <span class="badge bg-warning">{{ $sale->formatted_status }}</span>
                            @else
                            <span class="badge bg-danger">{{ $sale->formatted_status }}</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('sales.show', $sale->id) }}" class="btn btn-sm btn-outline-info"
                                    title="Detail">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                        <circle cx="12" cy="12" r="3" />
                                    </svg>
                                </a>
                                <a href="{{ route('sales.receipt', $sale->id) }}" class="btn btn-sm btn-outline-success"
                                    title="Struk">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                                        <polyline points="14,2 14,8 20,8" />
                                        <line x1="16" y1="13" x2="8" y2="13" />
                                        <line x1="16" y1="17" x2="8" y2="17" />
                                        <polyline points="10,9 9,9 8,9" />
                                    </svg>
                                </a>
                                <a href="{{ route('sales.print', $sale->id) }}" class="btn btn-sm btn-outline-primary"
                                    title="Cetak" target="_blank">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <polyline points="6,9 6,2 18,2 18,9" />
                                        <path
                                            d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2" />
                                        <rect x="6" y="14" width="12" height="8" />
                                    </svg>
                                </a>
                                @if($sale->status === 'completed')
                                <button class="btn btn-sm btn-outline-danger" onclick="cancelSale({{ $sale->id }})"
                                    title="Batalkan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2">
                                        <circle cx="12" cy="12" r="10" />
                                        <path d="M15 9l-6 6M9 9l6 6" />
                                    </svg>
                                </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <div class="text-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="1.5" class="mb-2">
                                    <path d="M3 3h18l-1 13H4L3 3z" />
                                    <path d="M16 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                                    <path d="M7 16a1 1 0 1 1 2 0a1 1 0 0 1 -2 0" />
                                </svg>
                                <p>Belum ada transaksi penjualan</p>
                                <a href="{{ route('sales.pos') }}" class="btn btn-primary">Mulai Penjualan</a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($sales->hasPages())
        <div class="d-flex justify-content-center mt-3">
            {{ $sales->links() }}
        </div>
        @endif
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
    @if($sales->count() > 0)
        $('#sales-table').DataTable({
            "paging": false,
            "info": false,
            "searching": true,
            "ordering": true,
            "order": [[1, "desc"]],
            "columnDefs": [
                { "orderable": false, "targets": [7] }
            ],
            "language": {
                "search": "Cari:",
                "lengthMenu": "Tampilkan _MENU_ entri",
                "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                "infoEmpty": "Menampilkan 0 sampai 0 dari 0 entri",
                "infoFiltered": "(disaring dari _MAX_ total entri)",
                "zeroRecords": "Tidak ada data yang cocok",
                "emptyTable": "Tidak ada data tersedia dalam tabel",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                }
            }
        });
    @endif
});

function cancelSale(saleId) {
    if (confirm('Apakah Anda yakin ingin membatalkan transaksi ini? Stok produk akan dikembalikan.')) {
        // Create a form and submit it
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("sales.cancel", ":id") }}'.replace(':id', saleId);

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
