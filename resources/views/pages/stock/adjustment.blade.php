@extends('layouts.master')

@section('title', 'Penyesuaian Stok')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 fw-bold">Penyesuaian Stok</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Manajemen Stok</a></li>
        <li class="breadcrumb-item active">Penyesuaian Stok</li>
    </ol>

    @include('partials.alert')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Form Penyesuaian Stok</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.adjustment') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label fw-bold">Produk</label>
                                <select class="form-select @error('product_id') is-invalid @enderror" id="product_id"
                                    name="product_id" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}"
                                        data-name="{{ $product->name }}" data-code="{{ $product->code }}" {{
                                        old('product_id')==$product->id ? 'selected' : '' }}>
                                        {{ $product->name }} ({{ $product->code }}) - Stok: {{
                                        number_format($product->stock) }}
                                    </option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="new_stock" class="form-label fw-bold">Stok Baru</label>
                                <input type="number" class="form-control @error('new_stock') is-invalid @enderror"
                                    id="new_stock" name="new_stock" value="{{ old('new_stock') }}" min="0" required>
                                @error('new_stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="stock-info" class="text-muted">Pilih produk untuk melihat stok saat
                                        ini</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h6 class="fw-bold mb-3">Informasi Penyesuaian</h6>
                                    <div class="row" id="adjustment-info" style="display: none;">
                                        <div class="col-md-4">
                                            <small class="text-muted">Stok Saat Ini:</small>
                                            <div class="fw-bold" id="current-stock">-</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Stok Baru:</small>
                                            <div class="fw-bold" id="new-stock-display">-</div>
                                        </div>
                                        <div class="col-md-4">
                                            <small class="text-muted">Selisih:</small>
                                            <div class="fw-bold" id="difference">-</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Alasan Penyesuaian</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                rows="3" maxlength="500" required>{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Wajib diisi. Maksimal 500 karakter</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('stock.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-warning">
                                <i class="fas fa-save me-2"></i>Simpan Penyesuaian
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Penyesuaian Stok</strong><br>
                        Gunakan fitur ini untuk menyesuaikan stok yang tidak sesuai dengan kondisi fisik. Operasi ini
                        akan tercatat dalam riwayat pergerakan stok.
                    </div>

                    <h6 class="fw-bold">Kapan Menggunakan:</h6>
                    <ul class="small">
                        <li>Stok sistem tidak sesuai dengan fisik</li>
                        <li>Setelah stock opname</li>
                        <li>Koreksi kesalahan input</li>
                        <li>Produk rusak atau hilang</li>
                    </ul>

                    <h6 class="fw-bold">Perhatian:</h6>
                    <ul class="small text-danger">
                        <li>Operasi ini tidak dapat dibatalkan</li>
                        <li>Wajib memberikan alasan yang jelas</li>
                        <li>Akan tercatat dalam audit trail</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    let currentStock = 0;

    document.getElementById('product_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        const stockInfo = document.getElementById('stock-info');
        const adjustmentInfo = document.getElementById('adjustment-info');

        if (stock) {
            currentStock = parseInt(stock);
            stockInfo.innerHTML = `Stok saat ini: <span class="fw-bold text-primary">${currentStock.toLocaleString()}</span>`;
            document.getElementById('current-stock').textContent = currentStock.toLocaleString();
            adjustmentInfo.style.display = 'block';
            updateAdjustmentInfo();
        } else {
            stockInfo.innerHTML = 'Pilih produk untuk melihat stok saat ini';
            adjustmentInfo.style.display = 'none';
            currentStock = 0;
        }
    });

    document.getElementById('new_stock').addEventListener('input', function() {
        updateAdjustmentInfo();
    });

    function updateAdjustmentInfo() {
        const newStock = parseInt(document.getElementById('new_stock').value) || 0;
        const difference = newStock - currentStock;

        document.getElementById('new-stock-display').textContent = newStock.toLocaleString();

        const differenceElement = document.getElementById('difference');
        if (difference > 0) {
            differenceElement.innerHTML = `<span class="text-success">+${difference.toLocaleString()}</span>`;
        } else if (difference < 0) {
            differenceElement.innerHTML = `<span class="text-danger">${difference.toLocaleString()}</span>`;
        } else {
            differenceElement.innerHTML = `<span class="text-muted">0</span>`;
        }
    }
</script>
@endpush
