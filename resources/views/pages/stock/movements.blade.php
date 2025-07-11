@extends('layouts.master')

@section('title', 'Riwayat Pergerakan Stok')

@section('content')
<div class="px-4 container-fluid">
    <h1 class="mt-4 fw-bold">Riwayat Pergerakan Stok</h1>
    <ol class="mb-4 breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Manajemen Stok</a></li>
        <li class="breadcrumb-item active">Riwayat Pergerakan</li>
    </ol>

    @include('partials.alert')

    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center w-100">
                <h5 class="mb-0 fw-bold">Riwayat Pergerakan Stok</h5>
                <a href="{{ route('stock.index') }}" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-arrow-left me-2"></i>Kembali ke Dashboard
                </a>
            </div>
        </div>
        <div class="card-body">
            @if($movements->count() > 0)
            <!-- Filter Section -->
            <div class="mb-4 row">
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
            <!-- Summary Cards -->
            <div class="my-4 row">
                <div class="col-md-3">
                    <div class="text-white card bg-success">
                        <div class="text-center card-body">
                            <h6 class="card-title">Total Stok Masuk</h6>
                            <h4 id="total-in">{{ $movements->where('type', 'in')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card bg-danger">
                        <div class="text-center card-body">
                            <h6 class="card-title">Total Stok Keluar</h6>
                            <h4 id="total-out">{{ $movements->where('type', 'out')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card bg-warning">
                        <div class="text-center card-body">
                            <h6 class="card-title">Total Penyesuaian</h6>
                            <h4 id="total-adjustment">{{ $movements->where('type', 'adjustment')->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="text-white card bg-info">
                        <div class="text-center card-body">
                            <h6 class="card-title">Total Pergerakan</h6>
                            <h4 id="total-movements">{{ $movements->count() }}</h4>
                        </div>
                    </div>
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
            @else
            <div class="py-5 text-center">
                <i class="mb-4 fas fa-inbox fa-4x text-muted"></i>
                <h5 class="text-muted">Belum Ada Pergerakan Stok</h5>
                <p class="mb-4 text-muted">Mulai dengan menambahkan stok masuk atau keluar untuk melihat riwayat
                    pergerakan.</p>
                <div class="gap-2 d-flex justify-content-center">
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

@push('addon-script')
<script>
    $(document).ready(function() {
    @if($movements->count() > 0)
    // Initialize DataTable
    const table = $('#movementsTable').DataTable({
        responsive: true,
        pageLength: 10,
        columnDefs: [
            { orderable: false, targets: [8] } // Disable sorting for notes column
        ],
        dom: "<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
             "<'row'<'col-sm-12'tr>>" +
             "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        language: {
            search: "Cari:",
            emptyTable: "Tidak ada data yang tersedia",
            processing: "Sedang memproses..."
        },
        processing: true
    });

    // Custom search function
    function customSearch() {
        table.draw();
        updateSummaryCards();
    }

    // Type filter mapping
    const typeMap = {
        '': '',
        'in': 'Masuk',
        'out': 'Keluar',
        'adjustment': 'Penyesuaian',
        'opname': 'Stock Opname'
    };

    $('#filter-type').off('change').on('change', function() {
        const searchTerm = typeMap[$(this).val()];
        table.column(2).search(searchTerm, true, false).draw();
        updateSummaryCards();
    });

    // Product filter
    $('#filter-product').on('keyup', function() {
        table.column(1).search(this.value).draw();
        updateSummaryCards();
    });

    // Date range filter with robust date extraction
    $.fn.dataTable.ext.search.push(
        function(settings, data, dataIndex) {
            const dateFrom = $('#filter-date-from').val();
            const dateTo = $('#filter-date-to').val();

            if (!dateFrom && !dateTo) return true;

            // Extract only the date part (dd/mm/yyyy) from the first column, ignoring the time
            const match = data[0].match(/(\d{2}\/\d{2}\/\d{4})/);
            if (!match) return true;
            const dateStr = match[1];
            const dateParts = dateStr.split('/');
            if (dateParts.length !== 3) return true;

            // Convert table date to yyyymmdd number
            const rowDateNum = parseInt(dateParts[2] + dateParts[1].padStart(2, '0') + dateParts[0].padStart(2, '0'));
            const fromNum = dateFrom ? parseInt(dateFrom.replace(/-/g, '')) : null;
            const toNum = dateTo ? parseInt(dateTo.replace(/-/g, '')) : null;

            if (fromNum && toNum) {
                return rowDateNum >= fromNum && rowDateNum <= toNum;
            } else if (fromNum) {
                return rowDateNum >= fromNum;
            } else if (toNum) {
                return rowDateNum <= toNum;
            }

            return true;
        }
    );

    // Update summary cards
    function updateSummaryCards() {
        const visibleRows = table.rows({ search: 'applied' }).nodes();
        let totalIn = 0;
        let totalOut = 0;
        let totalAdjustment = 0;

        $(visibleRows).each(function() {
            const type = $(this).find('td:eq(2) span.badge').text().trim().toLowerCase();
            if (type.includes('masuk')) totalIn++;
            else if (type.includes('keluar')) totalOut++;
            else if (type.includes('penyesuaian')) totalAdjustment++;
        });

        $('#total-in').text(totalIn);
        $('#total-out').text(totalOut);
        $('#total-adjustment').text(totalAdjustment);
        $('#total-movements').text(visibleRows.length);
    }

    // Bind filter events
    $('#filter-date-from, #filter-date-to').on('change', function() {
        customSearch();
    });

    // Use event delegation for dynamically added clear-filters button
    $(document).on('click', '.clear-filters', function() {
        $('#filter-type').val('');
        $('#filter-product').val('');
        $('#filter-date-from').val('');
        $('#filter-date-to').val('');
        table.search('').columns().search('').draw();
        updateSummaryCards();
    });

    // Initial cards update
    updateSummaryCards();

    // Add clear filters button
    $('.card-header .d-flex').append(
        '<button class="btn btn-outline-secondary btn-sm clear-filters ms-2">' +
        '<i class="fas fa-times me-1"></i>Reset Filter</button>'
    );
    @endif
});
</script>
@endpush
