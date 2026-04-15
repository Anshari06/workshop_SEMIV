@extends('layouts.payment')

@section('title', 'Detail Pesanan Vendor')

@push('styles')
    <style>
        .page-shell {
            background: #f8fafc;
            border: 1px solid #e9ecef;
            border-radius: 14px;
            padding: 1rem;
        }

        .metric-card {
            border: 1px solid #e9ecef;
            border-radius: 12px;
            box-shadow: none;
        }

        .page-title {
            background: #fff;
            border: 1px solid #e9ecef;
            border-radius: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="page-shell">
            <div class="page-title d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 p-3 mb-3">
                <div>
                    <h1 class="h4 mb-1">Detail Pesanan {{ $order['kode'] }}</h1>
                    <p class="mb-0 text-muted">Customer: {{ $order['customer'] }} | Total item: {{ $order['item_count'] }}</p>
                </div>
                <a href="{{ route('vendor.pesanan') }}" class="btn btn-light border">Kembali ke Pesanan</a>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Kode Pesanan</p>
                            <h6 class="mb-0">{{ $order['kode'] }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Tanggal</p>
                            <h6 class="mb-0">{{ optional($order['created_at'])->format('d M Y H:i') ?? '-' }}</h6>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Status</p>
                            @if ($order['status'] === 1)
                                <span class="badge bg-success">Lunas</span>
                            @else
                                <span class="badge bg-warning text-dark">Pending</span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-3">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Subtotal Vendor</p>
                            <h6 class="mb-0">Rp {{ number_format($order['subtotal'], 0, ',', '.') }}</h6>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card metric-card">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Item Pesanan</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-center">Qty</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-end">Subtotal</th>
                                    <th>Catatan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($order['items'] as $item)
                                    <tr>
                                        <td>{{ $item->menu->nama_menu ?? 'Unknown' }}</td>
                                        <td class="text-center">{{ $item->jumlah }}</td>
                                        <td class="text-end">Rp {{ number_format($item->menu->harga ?? 0, 0, ',', '.') }}</td>
                                        <td class="text-end">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                                        <td>{{ $item->catatan ?: '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
