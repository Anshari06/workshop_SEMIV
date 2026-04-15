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
                    <h1 class="h3 mb-2">Pesanan {{ $vendor->nama_vendor }}</h1>
                    <p class="mb-0 text-white-50">Pantau detail pesanan yang masuk, item yang dipesan, dan status pembayaran.</p>
                </div>
                <a href="{{ route('vendor.dashboard') }}" class="btn btn-light">Kembali ke Dashboard</a>
            </div>

        <div class="row g-3 mb-4">
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pesanan Masuk</p>
                        <h3 class="mb-0">{{ $totalOrders }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Pesanan Lunas</p>
                        <h3 class="mb-0">{{ $paidCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Menunggu Bayar</p>
                        <h3 class="mb-0">{{ $pendingCount }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-12 col-sm-6 col-xl-3">
                <div class="card metric-card h-100">
                    <div class="card-body">
                        <p class="text-muted mb-1">Total Pendapatan</p>
                        <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <div class="col-12">
                <div class="card metric-card">
                    <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Tabel Pesanan</h5>
                        <span class="text-muted small">Menampilkan pesanan berdasarkan detail item vendor</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Customer</th>
                                        <th class="text-center">Tanggal</th>
                                        <th class="text-center">Total Item</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Status</th>
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
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada pesanan yang masuk.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card metric-card">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Detail Item Pesanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Kode</th>
                                        <th>Menu</th>
                                        <th class="text-center">Qty</th>
                                        <th class="text-end">Harga</th>
                                        <th class="text-end">Subtotal</th>
                                        <th class="text-center">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($pesanan as $order)
                                        <tr>
                                            <td>#{{ str_pad((string) $order->id_pesanan, 6, '0', STR_PAD_LEFT) }}</td>
                                            <td>
                                                <div class="fw-semibold">{{ $order->menu->nama_menu ?? 'Unknown' }}</div>
                                                <small class="text-muted">{{ $order->pesanan->nama ?? 'Guest' }}</small>
                                            </td>
                                            <td class="text-center">{{ $order->jumlah }}</td>
                                            <td class="text-end">Rp {{ number_format($order->menu->harga ?? 0, 0, ',', '.') }}</td>
                                            <td class="text-end">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</td>
                                            <td class="text-center">
                                                @if (($order->pesanan->status ?? 0) == 1)
                                                    <span class="badge bg-success">Lunas</span>
                                                @else
                                                    <span class="badge bg-warning text-dark">Pending</span>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center text-muted py-4">Belum ada detail item pesanan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection