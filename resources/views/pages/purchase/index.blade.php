@extends('layouts.master')

@section('title', 'Data Pembelian')

@section('action')
@can('purchase.create')
<a href="{{ route('purchases.create') }}" class="btn btn-primary">Tambah Pembelian</a>
@endcan
@endsection

@push('addon-style')
<link href="https://cdn.datatables.net/1.13.4/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
@endpush

@section('content')
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table id="purchases-table" class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Invoice Pembelian</th>
                        <th>Tanggal</th>
                        <th>Supplier</th>
                        <th>Total</th>
                        <th>Keterangan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $purchase)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $purchase->code }}</td>
                        <td>{{ $purchase->date }}</td>
                        <td>{{ $purchase->supplier->name ?? '-' }}</td>
                        <td>Rp {{ number_format($purchase->total, 0, ',', '.') }}</td>
                        <td>{{ $purchase->note ?? '-' }}</td>
                        <td>
                            @can('purchase.show')
                            <a href="{{ route('purchases.show', $purchase->id) }}"
                                class="btn btn-sm btn-info">Detail</a>
                            @endcan
                            @can('purchase.delete')
                            <button class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                data-bs-target="#delete-purchase-{{ $purchase->id }}">
                                Hapus
                            </button>
                            <x-modal.delete-confirm id="delete-purchase-{{ $purchase->id }}"
                                :route="route('purchases.destroy', $purchase->id)"
                                item="{{ $purchase->code ?? $purchase->id }}" title="Hapus Pembelian?"
                                description="Pembelian yang dihapus tidak bisa dikembalikan. Stok akan dikurangi sesuai item pembelian." />
                            @endcan
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
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.4/js/dataTables.bootstrap5.min.js"></script>
<script>
    $(document).ready(function() {
        $('#purchases-table').DataTable({
            "pageLength": 25,
            "order": [[ 0, "asc" ]],
            "columnDefs": [
                { "orderable": false, "targets": [5] }
            ]
        });
    });
</script>
@endpush