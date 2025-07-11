@extends('layouts.master')

@section('title', 'Riwayat Pergerakan Stok')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 fw-bold">Riwayat Pergerakan Stok</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Manajemen Stok</a></li>
        <li class="breadcrumb-item active">Riwayat Pergerakan</li>
    </ol>

    @include('partials.alert')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Riwayat Pergerakan Stok</h5>
                <a href="{{ route('stock.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($movements->count() > 0)
            <!-- Filter Section -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="filter-type" class="form-label">Filter Tipe:</label>
                    <select class="form-select" id="filter-type">
                        <option value="">Semua Tipe</option>
                        <option value="in">Stok Masuk</option>
                        <option value="out">Stok Keluar</option>
                        <option value="adjustment">Penyesuaian</option>
                        <option value="opname">Stock Opname</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label for="filter-product" class="form-label">Filter Produk:</label>
                    <input type="text" class="form-control" id="filter-product" placeholder="Cari produk...">
                </div>
                <div class="col-md-3">
                    <label for="filter-date-from" class="form-label">Dari Tanggal:</label>
                    <input type="date" class="form-control" id="filter-date-from">
                </div>
                <div class="col-md-3">
                    <label for="filter-date-to" class="form-label">Sampai Tanggal:</label>
                    <input type="date" class="form-control" id="filter-date-to">
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-striped" id="movementsTable">
                    <thead class="table-dark">
                        <tr>
                            <th>Tanggal</th>
                            <th>Produk</th>
                            <th>Tipe</th>
                            <th>Jumlah</th>
                            <th>Stok Sebelum</th>
                            <th>Stok Sesudah</th>
                            <th>Harga Satuan</th>
                            <th>Total Nilai</th>
                            <th>Referensi</th>
                            <th>User</th>
                            <th>Catatan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($movements as $movement)
                        <tr>
                            <td>
                                <div class="fw-bold">{{ $movement->movement_date->format('d/m/Y') }}</div>
                                <small class="text-muted">{{ $movement->movement_date->format('H:i') }}</small>
                            </td>
                            <td>
                                <div class="fw-bold">{{ $movement->product->name }}</div>
                                <small class="text-muted">{{ $movement->product->code }}</small>
                            </td>
                            <td>
                                <span class="badge fs-6
                                    @if($movement->type === 'in') bg-success
                                    @elseif($movement->type === 'out') bg-danger
                                    @elseif($movement->type === 'adjustment') bg-warning
                                    @else bg-info
                                    @endif">
                                    {{ $movement->type_name }}
                                </span>
                            </td>
                            <td class="fw-bold
                                @if($movement->quantity > 0) text-success
                                @elseif($movement->quantity < 0) text-danger
                                @endif">
                                {{ $movement->quantity_display }}
                            </td>
                            <td>{{ number_format($movement->previous_stock) }}</td>
                            <td class="fw-bold">{{ number_format($movement->current_stock) }}</td>
                            <td>{{ $movement->formatted_unit_cost }}</td>
                            <td>{{ $movement->formatted_total_cost }}</td>
                            <td>
                                <small class="text-muted">{{ $movement->reference_display }}</small>
                            </td>
                            <td>
                                <small>{{ $movement->user->name }}</small>
                            </td>
                            <td>
                                @if($movement->notes)
                                <small class="text-muted">{{ Str::limit($movement->notes, 30) }}</small>
                                @else
                                <small class="text-muted">-</small>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Summary Cards -->
            <div class="row mt-4">
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Stok Masuk</h6>
                            <h4 id="total-in">{{ $movements->where('type', 'in')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Stok Keluar</h6>
                            <h4 id="total-out">{{ $movements->where('type', 'out')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Penyesuaian</h6>
                            <h4 id="total-adjustment">{{ $movements->where('type', 'adjustment')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body text-center">
                            <h6 class="card-title">Total Pergerakan</h6>
                            <h4 id="total-movements">{{ $movements->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-4"></i>
                <h5 class="text-muted">Belum Ada Pergerakan Stok</h5>
                <p class="text-muted mb-4">Mulai dengan menambahkan stok masuk atau keluar untuk melihat riwayat
                    pergerakan.</p>
                <div class="d-flex justify-content-center gap-2">
                    @can('stock.in')
                    <a href="{{ route('stock.in.form') }}" class="btn btn-success">
                        <i class="fas fa-plus-circle me-2"></i>Stok Masuk
                    </a>
                    @endcan
                    @can('stock.out')
                    <a href="{{ route('stock.out.form') }}" class="btn btn-danger">
                        <i class="fas fa-minus-circle me-2"></i>Stok Keluar
                    </a>
                    @endcan
                </div>
            </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    @if($movements->count() > 0)
    // Initialize DataTable
    const table = $('#movementsTable').DataTable({
        responsive: true,
        pageLength: 25,
        order: [[0, 'desc']], // Sort by date descending
        columnDefs: [
            { orderable: false, targets: [10] } // Disable sorting for notes column
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data per halaman",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 data",
            infoFiltered: "(difilter dari _MAX_ total data)",
            paginate: {
                first: "Pertama",
                last: "Terakhir",
                next: "Selanjutnya",
                previous: "Sebelumnya"
            },
            emptyTable: "Tidak ada data yang tersedia"
        }
    });

    // Custom filters
    $('#filter-type').on('change', function() {
        table.column(2).search(this.value).draw();
    });

    $('#filter-product').on('keyup', function() {
        table.column(1).search(this.value).draw();
    });

    // Date range filter
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            const dateFrom = $('#filter-date-from').val();
            const dateTo = $('#filter-date-to').val();

            if (!dateFrom && !dateTo) return true;

            const dateStr = data[0]; // Date column
            const date = new Date(dateStr.split('/').reverse().join('-'));

            const from = dateFrom ? new Date(dateFrom) : null;
            const to = dateTo ? new Date(dateTo) : null;

            if (from && to) {
                return date >= from && date <= to;
            } else if (from) {
                return date >= from;
            } else if (to) {
                return date <= to;
            }

            return true;
        }
    );

    $('#filter-date-from, #filter-date-to').on('change', function() {
        table.draw();
    });
    @endif
});
</script>
@endpush
