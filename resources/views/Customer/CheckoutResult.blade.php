@extends('layouts.payment')

@section('title', 'Riwayat QR Pembayaran')

@section('content')
    
    <div class="container-fluid">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h3 class="mb-2">Riwayat QR Pembayaran</h3>
                <p class="text-muted mb-4">Masukkan nomor HP yang dipakai saat checkout untuk membuka kembali QR code pesanan.</p>

                <form method="GET" action="{{ route('payment.history') }}" class="row g-2 align-items-end">
                    <div class="col-md-6">
                        <label for="phone" class="form-label">Nomor HP Customer</label>
                        <input type="text" id="phone" name="phone" class="form-control" value="{{ $phone }}" placeholder="08xxxxxxxxxx" required>
                    </div>
                    <div class="col-md-3">
                        <button type="submit" class="btn btn-primary w-100">Tampilkan Riwayat</button>
                    </div>
                </form>
            </div>
        </div>

        @if ($phone !== '')
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="mb-3">Hasil pencarian: {{ $phone }}</h5>

                    @if ($orders->isEmpty())
                        <div class="alert alert-warning mb-0">Belum ada pesanan untuk nomor HP ini.</div>
                    @else
                        <div class="table-responsive">
                            <table class="table align-middle mb-0">
                                <thead>
                                    <tr>
                                        <th>Invoice</th>
                                        <th>Nama</th>
                                        <th>Status Bayar</th>
                                        <th>Total</th>
                                        <th class="text-end">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        @php
                                            $statusText = match ((int) $order->status) {
                                                1 => 'Lunas',
                                                2 => 'Gagal / Dibatalkan',
                                                default => 'Belum Lunas',
                                            };

                                            $statusClass = match ((int) $order->status) {
                                                1 => 'text-bg-success',
                                                2 => 'text-bg-danger',
                                                default => 'text-bg-warning',
                                            };
                                        @endphp
                                        <tr>
                                            <td>#INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</td>
                                            <td>{{ $order->nama }}</td>
                                            <td><span class="badge {{ $statusClass }}">{{ $statusText }}</span></td>
                                            <td>Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                            <td class="text-end">
                                                @if ((int) $order->status === 1)
                                                    <a href="{{ route('payment.qr', ['pesanan' => $order->id]) }}" class="btn btn-sm btn-primary">Lihat QR</a>
                                                @else
                                                    <a href="{{ route('payment.show', ['pesanan' => $order->id]) }}" class="btn btn-sm btn-outline-secondary">Bayar</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
@endsection
