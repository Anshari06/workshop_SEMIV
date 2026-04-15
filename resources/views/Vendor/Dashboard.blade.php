@extends('layouts.payment')

@section('title', 'Dashboard Vendor')

@push('styles')
    <style>
        .vendor-hero {
            background: linear-gradient(135deg, #3b454b 0%, #4f5f72 100%);
            color: #fff;
            border-radius: 16px;
            overflow: hidden;
        }

        .metric-card {
            border: 0;
            border-radius: 14px;
            box-shadow: 0 8px 22px rgba(15, 23, 42, 0.08);
        }

        .metric-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 1.15rem;
        }

        .bg-soft-success {
            background: rgba(22, 163, 74, 0.12);
            color: #15803d;
        }

        .bg-soft-warning {
            background: rgba(245, 158, 11, 0.15);
            color: #b45309;
        }

        .bg-soft-primary {
            background: rgba(37, 99, 235, 0.12);
            color: #1d4ed8;
        }

        .bg-soft-danger {
            background: rgba(220, 38, 38, 0.12);
            color: #b91c1c;
        }

        .quick-action .btn {
            border-radius: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="vendor-hero p-4 p-md-5 mb-4">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h1 class="h3 mb-2">Dashboard {{ $vendor->nama_vendor }}</h1>
                    <p class="mb-0 text-white-50">Pantau pesanan, pendapatan, dan aktivitas toko kamu dari satu tempat.</p>
                </div>
                <div class="text-md-end">
                    <div class="small text-white-50">Hari ini</div>
                    <div class="h5 mb-0">{{ now()->format('d M Y') }}</div>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Pesanan Lunas</p>
                                <h3 class="mb-0">{{ $paidCount }}</h3>
                            </div>
                            <span class="metric-icon bg-soft-success"><i class="bi bi-check2-circle"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Menunggu Bayar</p>
                                <h3 class="mb-0">{{ $pendingCount }}</h3>
                            </div>
                            <span class="metric-icon bg-soft-warning"><i class="bi bi-hourglass-split"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Total Pesanan</p>
                                <h3 class="mb-0">{{ $pesanan->count() }}</h3>
                            </div>
                            <span class="metric-icon bg-soft-primary"><i class="bi bi-basket"></i></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <p class="text-muted mb-1">Pendapatan</p>
                                <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                            </div>
                            <span class="metric-icon bg-soft-danger"><i class="bi bi-cash-stack"></i></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12 col-xl-8">
                <div class="card metric-card">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Pesanan Terbaru</h5>
                        <a href="#" class="btn btn-sm btn-outline-secondary">Lihat Semua</a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Customer</th>
                                        <th class="text-center">Menu</th>
                                        <th class="text-center">Item</th>
                                        <th class="text-end">Total</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pesanan as $order)
                                        <tr>
                                            <td><strong>#{{ $order->pesanan->id }}</strong></td>
                                            <td>{{ $order->pesanan->nama ?? 'Guest' }}</td>
                                            <td class="text-center">{{$order->menu->nama_menu ?? 'Unknown'}}</td>
                                            <td class="text-center">{{ $order->jumlah }}</td>
                                            <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if ($order->pesanan->status == 1)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted py-3">Belum ada pesanan</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12 col-xl-4">
                <div class="card metric-card quick-action mb-3">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Aksi Cepat</h5>
                    </div>
                    <div class="card-body d-grid gap-2">
                        <a href="#" class="btn btn-success"><i class="bi bi-plus-lg me-2"></i>Tambah Menu</a>
                        <a href="#" class="btn btn-outline-primary"><i class="bi bi-list-ul me-2"></i>Kelola Menu</a>
                        <a href="#" class="btn btn-outline-dark"><i class="bi bi-receipt me-2"></i>Lihat Pesanan</a>
                    </div>
                </div>

                <div class="card metric-card">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Performa Singkat</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $totalCount = $pesanan->count();
                            $paidPercent = $totalCount > 0 ? round(($paidCount / $totalCount) * 100) : 0;
                            $pendingPercent = $totalCount > 0 ? round(($pendingCount / $totalCount) * 100) : 0;
                        @endphp
                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Order Lunas</span>
                            <strong>{{ $paidPercent }}%</strong>
                        </div>
                        <div class="progress mb-3" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: {{ $paidPercent }}%"></div>
                        </div>

                        <div class="mb-2 d-flex justify-content-between">
                            <span class="text-muted">Pembayaran Pending</span>
                            <strong>{{ $pendingPercent }}%</strong>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-warning" role="progressbar" style="width: {{ $pendingPercent }}%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
