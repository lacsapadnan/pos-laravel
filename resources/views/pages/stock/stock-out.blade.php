@extends('layouts.master')

@section('title', 'Stok Keluar')

@section('content')
<div class="px-4 container-fluid">
    <h1 class="mt-4 fw-bold">Stok Keluar</h1>
    <ol class="mb-4 breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Manajemen Stok</a></li>
        <li class="breadcrumb-item active">Stok Keluar</li>
    </ol>

    @include('partials.alert')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Form Stok Keluar</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.out') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
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
                            <div class="mb-3 col-md-6">
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
                    <h5 class="mb-0 fw-bold">Informasi</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning alert-dismissible" role="alert">
                        <div class="alert-icon">
                            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-circle -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="icon alert-icon icon-2">
                                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                                <path d="M12 8v4"></path>
                                <path d="M12 16h.01"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="alert-heading">Perhatian:</h4>
                            <div class="alert-description">
                                <p>Gunakan form ini untuk mencatat pengurangan stok produk. Pastikan stok mencukupi
                                    sebelum
                                    melakukan pengurangan.</p>
                            </div>
                            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
                        </div>
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