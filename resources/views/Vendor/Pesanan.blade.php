@extends('layouts.payment')

@section('title', 'Dashboard Vendor')

@push('styles')
    <style>
        .orders-shell {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem;
        }

        .orders-header {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 1rem;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: none;
        }

        .metric-label {
            color: #6b7280;
            font-size: 0.875rem;
            margin-bottom: 0.25rem;
        }

        .table thead th {
            border-bottom: 1px solid #e5e7eb;
            color: #374151;
            font-weight: 600;
            font-size: 0.875rem;
            white-space: nowrap;
        }

        .table tbody td {
            border-bottom: 1px solid #f1f5f9;
            vertical-align: middle;
        }

        .btn-back {
            border: 1px solid #d1d5db;
            background: #fff;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #9ca3af;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.08);
        }

        .status-badge {
            min-width: 78px;
        }

        .table tbody tr {
            transition: background-color 0.2s ease;
        }

        .table tbody tr:hover {
            background-color: #f8fafc;
        }

        .btn-outline-primary {
            transition: all 0.2s ease;
        }

        .btn-outline-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(37, 99, 235, 0.18);
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="orders-shell">
            <div class="orders-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div>
                    <h1 class="h4 mb-1">Pesanan {{ $vendor->nama_vendor }}</h1>
                    <p class="mb-0 text-muted">Pantau pesanan masuk dan lihat detail item per pesanan.</p>
                </div>
                <a href="{{ route('vendor.dashboard') }}" class="btn btn-back">Kembali ke Dashboard</a>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="metric-label">Pesanan Masuk</p>
                            <h4 class="mb-0">{{ $totalOrders }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="metric-label">Pesanan Lunas</p>
                            <h4 class="mb-0">{{ $paidCount }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="metric-label">Menunggu Bayar</p>
                            <h4 class="mb-0">{{ $pendingCount }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="metric-label">Total Pendapatan</p>
                            <h4 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card metric-card">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Pesanan</h5>
                    <span class="text-muted small">Klik detail untuk melihat isi pesanan</span>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Kode</th>
                                    <th>Customer</th>
                                    <th class="text-center">Tanggal</th>
                                    <th class="text-center">Total Item</th>
                                    <th class="text-end">Subtotal</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pesananGroups as $order)
                                    <tr>
                                        <td><strong>{{ $order['kode'] }}</strong></td>
                                        <td>{{ $order['customer'] }}</td>
                                        <td class="text-center">{{ optional($order['created_at'])->format('d M Y H:i') ?? '-' }}</td>
                                        <td class="text-center">{{ $order['item_count'] }} item</td>
                                        <td class="text-end">Rp {{ number_format($order['subtotal'], 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            @if ($order['status'] === 1)
                                                <span class="badge bg-success status-badge">Lunas</span>
                                            @else
                                                <span class="badge bg-warning text-dark status-badge">Pending</span>
                                            @endif
                                        </td>
                                        <td class="text-center">
                                            <a href="{{ route('vendor.pesanan.detail', $order['pesanan_id']) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada pesanan yang masuk.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
