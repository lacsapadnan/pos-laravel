@extends('layouts.master')

@section('title', 'Manajemen Stok')

@section('content')
<div class="px-4 container-fluid">
    <h1 class="mt-4 fw-bold">Manajemen Stok</h1>
    <ol class="mb-4 breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Stok</li>
    </ol>

    @include('partials.alert')

    <!-- Stock Summary Cards -->
    <div class="mb-4 row">
        <div class="col-xl-3 col-md-6">
            <div class="mb-4 text-white card bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Total Produk</div>
                            <div class="mb-0 h4">{{ number_format($stockSummary['total_products']) }}</div>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="mb-4 text-white card bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Nilai Total Stok</div>
                            <div class="mb-0 h4">Rp {{ number_format($stockSummary['total_stock_value'], 0, ',', '.') }}
                            </div>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="mb-4 text-white card bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Stok Rendah</div>
                            <div class="mb-0 h4">{{ number_format($stockSummary['low_stock_count']) }}</div>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="mb-4 text-white card bg-danger">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Stok Habis</div>
                            <div class="mb-0 h4">{{ number_format($stockSummary['out_of_stock_count']) }}</div>
                        </div>
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="mb-4 row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Aksi Stok</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('stock.in')
                        <div class="mb-3 col-md-3">
                            <a href="{{ route('stock.in.form') }}" class="btn btn-success w-100">
                                <i class="fas fa-plus-circle me-2"></i>Stok Masuk
                            </a>
                        </div>
                        @endcan
                        @can('stock.out')
                        <div class="mb-3 col-md-3">
                            <a href="{{ route('stock.out.form') }}" class="btn btn-danger w-100">
                                <i class="fas fa-minus-circle me-2"></i>Stok Keluar
                            </a>
                        </div>
                        @endcan
                        @can('stock.adjustment')
                        <div class="mb-3 col-md-3">
                            <a href="{{ route('stock.adjustment.form') }}" class="btn btn-warning w-100">
                                <i class="fas fa-edit me-2"></i>Penyesuaian Stok
                            </a>
                        </div>
                        @endcan
                        <div class="mb-3 col-md-3">
                            <a href="{{ route('stock.movements') }}" class="btn btn-info w-100">
                                <i class="fas fa-history me-2"></i>Riwayat Pergerakan
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Recent Stock Movements -->
        <div class="col-lg-8">
            <div class="mb-4 card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="mb-0 fw-bold">Pergerakan Stok Terbaru</h5>
                        <a href="{{ route('stock.movements') }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentMovements->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Produk</th>
                                    <th>Tipe</th>
                                    <th>Jumlah</th>
                                    <th>Stok Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMovements as $movement)
                                <tr>
                                    <td>{{ $movement->movement_date->format('d/m/Y H:i') }}</td>
                                    <td>{{ $movement->product->name }}</td>
                                    <td>
                                        <span class="badge
                                            @if($movement->type === 'in') bg-success
                                            @elseif($movement->type === 'out') bg-danger
                                            @elseif($movement->type === 'adjustment') bg-warning
                                            @else bg-info
                                            @endif text-white">
                                            {{ $movement->type_name }}
                                        </span>
                                    </td>
                                    <td class="
                                        @if($movement->quantity > 0) text-success
                                        @elseif($movement->quantity < 0) text-danger
                                        @endif">
                                        {{ $movement->quantity_display }}
                                    </td>
                                    <td>{{ number_format($movement->current_stock) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="py-4 text-center">
                        <i class="mb-3 fas fa-inbox fa-3x text-muted"></i>
                        <p class="text-muted">Belum ada pergerakan stok</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="col-lg-4">
            <div class="mb-4 card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Produk Stok Rendah</h5>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                    @foreach($lowStockProducts as $product)
                    <div class="py-2 d-flex justify-content-between align-items-center border-bottom">
                        <div>
                            <div class="fw-bold">{{ $product->name }}</div>
                            <small class="text-muted">{{ $product->code }}</small>
                        </div>
                        <div class="text-end">
                            <div class="fw-bold text-danger">{{ number_format($product->stock) }}</div>
                            <small class="text-muted">Min: {{ number_format($product->minimum_stock) }}</small>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="py-4 text-center">
                        <i class="mb-3 fas fa-check-circle fa-3x text-success"></i>
                        <p class="text-muted">Semua produk memiliki stok yang cukup</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Movement Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0 fw-bold">Statistik Pergerakan</h5>
                </div>
                <div class="card-body">
                    <div class="text-center row">
                        <div class="col-6 border-end">
                            <div class="mb-0 h4 text-success">{{ number_format($movementStats['stock_in']) }}</div>
                            <small class="text-muted">Stok Masuk</small>
                        </div>
                        <div class="col-6">
                            <div class="mb-0 h4 text-danger">{{ number_format($movementStats['stock_out']) }}</div>
                            <small class="text-muted">Stok Keluar</small>
                        </div>
                    </div>
                    <hr>
                    <div class="text-center row">
                        <div class="col-6 border-end">
                            <div class="mb-0 h6 text-warning">{{ number_format($movementStats['adjustments']) }}</div>
                            <small class="text-muted">Penyesuaian</small>
                        </div>
                        <div class="col-6">
                            <div class="mb-0 h6 text-info">{{ number_format($movementStats['opnames']) }}</div>
                            <small class="text-muted">Stock Opname</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
