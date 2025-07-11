@extends('layouts.master')

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid">
    <div class="mb-4 row">
        <div class="mb-3 col-md-4">
            <div class="border-0 shadow-sm card h-100">
                <div class="text-center card-body">
                    <div class="mb-2">
                        <i class="bi bi-box-seam" style="font-size:2rem;color:#00A63E;"></i>
                    </div>
                    <h5 class="mb-1 card-title">Total Produk</h5>
                    <h2 class="fw-bold">{{ $totalProducts }}</h2>
                </div>
            </div>
        </div>
        <div class="mb-3 col-md-4">
            <div class="border-0 shadow-sm card h-100">
                <div class="text-center card-body">
                    <div class="mb-2">
                        <i class="bi bi-people" style="font-size:2rem;color:#00A63E;"></i>
                    </div>
                    <h5 class="mb-1 card-title">Total Pelanggan</h5>
                    <h2 class="fw-bold">{{ $totalCustomers }}</h2>
                </div>
            </div>
        </div>
        <div class="mb-3 col-md-4">
            <div class="border-0 shadow-sm card h-100">
                <div class="text-center card-body">
                    <div class="mb-2">
                        <i class="bi bi-cash-stack" style="font-size:2rem;color:#00A63E;"></i>
                    </div>
                    <h5 class="mb-1 card-title">Pendapatan Hari Ini</h5>
                    <h2 class="fw-bold">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</h2>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="border-0 shadow-sm card">
                <div class="card-body">
                    <h5 class="mb-4 card-title">Penjualan Bulan Ini</h5>
                    <canvas id="salesChart" height="100"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('addon-style')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
@endpush

@push('addon-script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const ctx = document.getElementById('salesChart').getContext('2d');
        const salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($chartLabels),
                datasets: [{
                    label: 'Revenue',
                    data: @json($chartData),
                    borderColor: '#00A63E',
                    backgroundColor: 'rgba(0,166,62,0.1)',
                    fill: true,
                    tension: 0.3,
                    pointRadius: 4,
                    pointBackgroundColor: '#00A63E',
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Rp ' + context.parsed.y.toLocaleString('id-ID');
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endpush
