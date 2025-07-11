@extends('layouts.master')

@section('title', 'Stok Keluar')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 fw-bold">Stok Keluar</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Manajemen Stok</a></li>
        <li class="breadcrumb-item active">Stok Keluar</li>
    </ol>

    @include('partials.alert')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Form Stok Keluar</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.out') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label fw-bold">Produk</label>
                                <select class="form-select @error('product_id') is-invalid @enderror" id="product_id"
                                    name="product_id" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" data-stock="{{ $product->stock }}" {{
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
                                <label for="quantity" class="form-label fw-bold">Jumlah</label>
                                <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                    id="quantity" name="quantity" value="{{ old('quantity') }}" min="1" required>
                                @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <div class="form-text">
                                    <span id="stock-info" class="text-muted">Pilih produk untuk melihat stok
                                        tersedia</span>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="notes" class="form-label fw-bold">Catatan (Opsional)</label>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                rows="3" maxlength="500">{{ old('notes') }}</textarea>
                            @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Maksimal 500 karakter</div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('stock.index') }}" class="btn btn-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-danger">
                                <i class="fas fa-save me-2"></i>Simpan Stok Keluar
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
                        <strong>Stok Keluar</strong><br>
                        Gunakan form ini untuk mencatat pengurangan stok produk. Pastikan stok mencukupi sebelum
                        melakukan pengurangan.
                    </div>

                    <h6 class="fw-bold">Tips:</h6>
                    <ul class="small">
                        <li>Pastikan produk yang dipilih sudah benar</li>
                        <li>Periksa stok tersedia sebelum input jumlah</li>
                        <li>Tambahkan catatan untuk alasan pengurangan stok</li>
                        <li>Stok tidak boleh menjadi negatif</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.getElementById('product_id').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const stock = selectedOption.getAttribute('data-stock');
        const stockInfo = document.getElementById('stock-info');
        const quantityInput = document.getElementById('quantity');

        if (stock) {
            stockInfo.innerHTML = `Stok tersedia: <span class="fw-bold text-primary">${parseInt(stock).toLocaleString()}</span>`;
            quantityInput.max = stock;
        } else {
            stockInfo.innerHTML = 'Pilih produk untuk melihat stok tersedia';
            quantityInput.removeAttribute('max');
        }
    });

    document.getElementById('quantity').addEventListener('input', function() {
        const selectedOption = document.getElementById('product_id').options[document.getElementById('product_id').selectedIndex];
        const stock = parseInt(selectedOption.getAttribute('data-stock')) || 0;
        const quantity = parseInt(this.value) || 0;

        if (quantity > stock) {
            this.setCustomValidity(`Jumlah tidak boleh lebih dari stok tersedia (${stock})`);
        } else {
            this.setCustomValidity('');
        }
    });
</script>
@endpush
