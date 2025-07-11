@extends('layouts.master')

@section('title', 'Manajemen Stok')

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 fw-bold">Manajemen Stok</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
        <li class="breadcrumb-item active">Manajemen Stok</li>
    </ol>

    @include('partials.alert')

    <!-- Stock Summary Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Total Produk</div>
                            <div class="h4 mb-0">{{ number_format($stockSummary['total_products']) }}</div>
                        </div>
                        <i class="fas fa-boxes fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-success text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Nilai Total Stok</div>
                            <div class="h4 mb-0">Rp {{ number_format($stockSummary['total_stock_value'], 0, ',', '.') }}
                            </div>
                        </div>
                        <i class="fas fa-money-bill-wave fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-warning text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Stok Rendah</div>
                            <div class="h4 mb-0">{{ number_format($stockSummary['low_stock_count']) }}</div>
                        </div>
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-danger text-white mb-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <div class="small text-white-50">Stok Habis</div>
                            <div class="h4 mb-0">{{ number_format($stockSummary['out_of_stock_count']) }}</div>
                        </div>
                        <i class="fas fa-times-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Aksi Stok</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        @can('stock.in')
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('stock.in.form') }}" class="btn btn-success w-100">
                                <i class="fas fa-plus-circle me-2"></i>Stok Masuk
                            </a>
                        </div>
                        @endcan
                        @can('stock.out')
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('stock.out.form') }}" class="btn btn-danger w-100">
                                <i class="fas fa-minus-circle me-2"></i>Stok Keluar
                            </a>
                        </div>
                        @endcan
                        @can('stock.adjustment')
                        <div class="col-md-3 mb-3">
                            <a href="{{ route('stock.adjustment.form') }}" class="btn btn-warning w-100">
                                <i class="fas fa-edit me-2"></i>Penyesuaian Stok
                            </a>
                        </div>
                        @endcan
                        <div class="col-md-3 mb-3">
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
            <div class="card mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="fw-bold mb-0">Pergerakan Stok Terbaru</h5>
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
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">Belum ada pergerakan stok</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Low Stock Products -->
        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Produk Stok Rendah</h5>
                </div>
                <div class="card-body">
                    @if($lowStockProducts->count() > 0)
                    @foreach($lowStockProducts as $product)
                    <div class="d-flex justify-content-between align-items-center border-bottom py-2">
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
                    <div class="text-center py-4">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">Semua produk memiliki stok yang cukup</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Movement Statistics -->
            <div class="card">
                <div class="card-header">
                    <h5 class="fw-bold mb-0">Statistik Pergerakan</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="h4 text-success mb-0">{{ number_format($movementStats['stock_in']) }}</div>
                            <small class="text-muted">Stok Masuk</small>
                        </div>
                        <div class="col-6">
                            <div class="h4 text-danger mb-0">{{ number_format($movementStats['stock_out']) }}</div>
                            <small class="text-muted">Stok Keluar</small>
                        </div>
                    </div>
                    <hr>
                    <div class="row text-center">
                        <div class="col-6 border-end">
                            <div class="h6 text-warning mb-0">{{ number_format($movementStats['adjustments']) }}</div>
                            <small class="text-muted">Penyesuaian</small>
                        </div>
                        <div class="col-6">
                            <div class="h6 text-info mb-0">{{ number_format($movementStats['opnames']) }}</div>
                            <small class="text-muted">Stock Opname</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection