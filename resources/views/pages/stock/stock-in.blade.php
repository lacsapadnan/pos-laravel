@extends('layouts.master')

@section('title', 'Stok Masuk')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 fw-bold">Stok Masuk</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="{{ route('stock.index') }}">Manajemen Stok</a></li>
        <li class="breadcrumb-item active">Stok Masuk</li>
    </ol>

    @include('partials.alert')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Form Stok Masuk</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('stock.in') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="product_id" class="form-label fw-bold">Produk</label>
                                <select class="form-select @error('product_id') is-invalid @enderror" id="product_id"
                                    name="product_id" required>
                                    <option value="">Pilih Produk</option>
                                    @foreach($products as $product)
                                    <option value="{{ $product->id }}" {{ old('product_id')==$product->id ? 'selected' :
                                        '' }}>
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
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="unit_cost" class="form-label fw-bold">Harga Satuan (Opsional)</label>
                                <input type="number" class="form-control @error('unit_cost') is-invalid @enderror"
                                    id="unit_cost" name="unit_cost" value="{{ old('unit_cost') }}" min="0" step="0.01">
                                @error('unit_cost')
                                <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
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
                            <button type="submit" class="btn btn-success">
                                <i class="fas fa-save me-2"></i>Simpan Stok Masuk
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
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Stok Masuk</strong><br>
                        Gunakan form ini untuk mencatat penambahan stok produk. Stok akan otomatis bertambah sesuai
                        jumlah yang diinput.
                    </div>

                    <h6 class="fw-bold">Tips:</h6>
                    <ul class="small">
                        <li>Pastikan produk yang dipilih sudah benar</li>
                        <li>Isi harga satuan jika diperlukan untuk perhitungan nilai stok</li>
                        <li>Tambahkan catatan untuk referensi di masa depan</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto calculate total cost
    document.getElementById('quantity').addEventListener('input', calculateTotal);
    document.getElementById('unit_cost').addEventListener('input', calculateTotal);

    function calculateTotal() {
        const quantity = parseFloat(document.getElementById('quantity').value) || 0;
        const unitCost = parseFloat(document.getElementById('unit_cost').value) || 0;
        const total = quantity * unitCost;

        // You can add a total display here if needed
    }
</script>
@endpush
