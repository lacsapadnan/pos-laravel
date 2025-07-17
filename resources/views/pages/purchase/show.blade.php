@extends('layouts.master')

@section('title', 'Detail Pembelian')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Detail Pembelian</h3>
    </div>
    <div class="card-body">
        <dl class="row mb-4">
            <dt class="col-sm-3">Kode</dt>
            <dd class="col-sm-9">{{ $purchase->code }}</dd>
            <dt class="col-sm-3">Tanggal</dt>
            <dd class="col-sm-9">{{ $purchase->date }}</dd>
            <dt class="col-sm-3">Supplier</dt>
            <dd class="col-sm-9">{{ $purchase->supplier->name ?? '-' }}</dd>
            <dt class="col-sm-3">Keterangan</dt>
            <dd class="col-sm-9">{{ $purchase->note }}</dd>
            <dt class="col-sm-3">Total</dt>
            <dd class="col-sm-9">Rp {{ number_format($purchase->total, 0, ',', '.') }}</dd>
        </dl>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Produk</th>
                        <th>Qty</th>
                        <th>Harga</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($purchase->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product->name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            <a href="{{ route('purchases.index') }}" class="btn btn-secondary">Kembali</a>
        </div>
    </div>
</div>
@endsection