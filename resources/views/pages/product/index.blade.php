@extends('layouts.master')

@section('title', 'Data Produk')

@section('action')
@can('product.create')
<a href="{{ route('product.create') }}" class="btn btn-primary">Tambah Data</a>
@endcan
@endsection

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="products-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Gambar</th>
                        <th>Kode</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th>Satuan</th>
                        <th>Harga Modal</th>
                        <th>Harga Jual</th>
                        <th>Stok</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>
                            @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                                class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                            <div class="rounded bg-secondary d-flex align-items-center justify-content-center"
                                style="width: 50px; height: 50px;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" class="text-white">
                                    <rect width="18" height="18" x="3" y="3" rx="2" ry="2" />
                                    <circle cx="9" cy="9" r="2" />
                                    <path d="m21 15-3.086-3.086a2 2 0 0 0-2.828 0L6 21" />
                                </svg>
                            </div>
                            @endif
                        </td>
                        <td><span class="text-white badge bg-dark">{{ $product->code }}</span></td>
                        <td>
                            <div>
                                <strong>{{ $product->name }}</strong>
                                @if($product->description)
                                <small class="text-muted d-block">{{ Str::limit($product->description, 50) }}</small>
                                @endif
                            </div>
                        </td>
                        <td>
                            <span class="text-white badge bg-info">{{ $product->category->name }}</span>
                        </td>
                        <td>
                            <span class="text-white badge bg-primary">{{ $product->unit->abbreviation }}</span>
                        </td>
                        <td>{{ $product->formatted_capital_price }}</td>
                        <td>{{ $product->formatted_price }}</td>
                        <td>
                            <span class="badge
                                        @if($product->stock_status === 'Habis') bg-danger
                                        @elseif($product->stock_status === 'Stok Rendah') bg-warning
                                        @else bg-success
                                        @endif text-white">
                                {{ $product->stock }} - {{ $product->stock_status }}
                            </span>
                        </td>
                        <td>
                            @if($product->status)
                            <span class="text-white badge bg-success">Aktif</span>
                            @else
                            <span class="text-white badge bg-danger">Tidak Aktif</span>
                            @endif
                        </td>
                        <td>
                            @canany(['product.view', 'product.update', 'product.delete'])
                            @can('product.view')
                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-sm btn-info">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                                    <circle cx="12" cy="12" r="3" />
                                </svg>
                                Detail
                            </a>
                            @endcan
                            @can('product.update')
                            <a href="{{ route('product.edit', $product->id) }}" class="btn btn-sm btn-warning">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M7 7h-1a2 2 0 0 0 -2 2v9a2 2 0 0 0 2 2h9a2 2 0 0 0 2 -2v-1" />
                                    <path d="M20.385 6.585a2.1 2.1 0 0 0 -2.97 -2.97l-8.415 8.385v3h3l8.385 -8.415z" />
                                    <path d="M16 5l3 3" />
                                </svg>
                                Edit
                            </a>
                            @endcan
                            @can('product.delete')
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#delete-product-{{ $product->id }}">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24"
                                    fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <path d="M4 7l16 0" />
                                    <path d="M10 11l0 6" />
                                    <path d="M14 11l0 6" />
                                    <path d="M5 7l1 12a2 2 0 0 0 2 2h8a2 2 0 0 0 2 -2l1 -12" />
                                    <path d="M9 7v-3a1 1 0 0 1 1 -1h4a1 1 0 0 1 1 1v3" />
                                </svg>
                                Hapus
                            </button>

                            <x-modal.delete-confirm id="delete-product-{{ $product->id }}"
                                :route="route('product.destroy', $product->id)" item="{{ $product->name }}"
                                title="Hapus Produk?" description="Produk yang dihapus tidak bisa dikembalikan." />
                            @endcan
                            @else
                            <span class="text-muted">Tidak ada aksi</span>
                            @endcanany
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('addon-script')
<script>
    $(document).ready(function() {
            $('#products-table').DataTable({
                "pageLength": 25,
                "order": [[ 0, "asc" ]],
                "columnDefs": [
                    { "orderable": false, "targets": [1, 9] }
                ]
            });
        });
</script>
@endpush