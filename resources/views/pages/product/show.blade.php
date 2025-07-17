@extends('layouts.master')

@section('title', 'Detail Produk')

@section('action')
<div class="btn-group">
    @canany(['product.update', 'product.delete'])
    @can('product.update')
    <a href="{{ route('product.edit', $product->id) }}" class="btn btn-warning">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
            <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
            <path d="M16 5l3 3" />
        </svg>
        Edit Produk
    </a>
    @endcan
    @can('product.delete')
    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#delete-product-{{ $product->id }}">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M4 7l16 0" />
            <path d="M10 11l0 6" />
            <path d="M14 11l0 6" />
            <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
            <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
        </svg>
        Hapus
    </button>

    <x-modal.delete-confirm id="delete-product-{{ $product->id }}" :route="route('product.destroy', $product->id)"
        item="{{ $product->name }}" title="Hapus Produk?" description="Produk yang dihapus tidak bisa dikembalikan." />
    @endcan
    @endcanany
    <a href="{{ route('product.index') }}" class="btn btn-secondary">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M19 12H5" />
            <path d="M12 19l-7-7 7-7" />
        </svg>
        Kembali
    </a>
</div>
@endsection

@section('content')
<div class="row">
    <div class="col-md-4">
        <!-- Product Image -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Gambar Produk</h3>
            </div>
            <div class="text-center card-body">
                @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                    class="rounded border img-fluid" style="max-height: 300px; object-fit: cover;">
                @else
                <div class="mx-auto rounded bg-secondary d-flex align-items-center justify-content-center"
                    style="width: 200px; height: 200px;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="text-white">
                        <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                        <circle cx="9" cy="9" r="2" />
                        <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                    </svg>
                </div>
                <p class="mt-2 text-muted">Tidak ada gambar</p>
                @endif
            </div>
        </div>

        <!-- Stock Status -->
        <div class="mt-3 card">
            <div class="card-header">
                <h3 class="card-title">Status Stok</h3>
            </div>
            <div class="card-body">
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Stok Saat Ini:</span>
                    <span class="badge
                            @if($product->stock_status === 'Habis') bg-danger
                            @elseif($product->stock_status === 'Stok Rendah') bg-warning
                            @else bg-success
                            @endif text-white">
                        {{ $product->stock }}
                    </span>
                </div>
                <div class="mb-2 d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Minimum Stok:</span>
                    <span class="text-white badge bg-info">{{ $product->minimum_stock }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="fw-bold">Status:</span>
                    <span class="badge
                            @if($product->stock_status === 'Habis') bg-danger
                            @elseif($product->stock_status === 'Stok Rendah') bg-warning
                            @else bg-success
                            @endif text-white">
                        {{ $product->stock_status }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-8">
        <!-- Product Information -->
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Informasi Dasar</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Nama Produk</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                {{ $product->name }}
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Kode Produk</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                <span class="text-white badge bg-dark">{{ $product->code }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Kategori</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                <span class="text-white badge bg-info">{{ $product->category->name }}</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Satuan</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                <span class="text-white badge bg-primary">{{ $product->unit->name }} ({{
                                    $product->unit->abbreviation }})</span>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Harga Modal</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                <strong class="text-success">{{ $product->formatted_capital_price }}</strong>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Harga Jual</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                <strong class="text-success">{{ $product->formatted_price }}</strong>
                            </div>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Status Produk</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                @if($product->status)
                                <span class="text-white badge bg-success">Aktif</span>
                                @else
                                <span class="text-white badge bg-danger">Tidak Aktif</span>
                                @endif
                            </div>
                        </div>
                        @if($product->description)
                        <hr>
                        <div class="row">
                            <div class="col-sm-4">
                                <h5 class="mb-0 fw-bold">Deskripsi</h5>
                            </div>
                            <div class="col-sm-8 text-secondary">
                                {{ $product->description }}
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
        $('#delete-product-{{ $product->id }}').on('show.bs.modal', function(e) {
            var button = $(e.relatedTarget);
            var productId = button.data('product-id');
            $(this).find('.modal-footer .btn-danger').attr('href', '{{ route('product.destroy', ':id') }}'.replace(':id', productId));
        });
    });
</script>
@endpush