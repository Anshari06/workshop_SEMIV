@extends('layouts.payment')

@section('title', 'QR Pembayaran')

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-7 col-lg-6">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-body p-4 p-md-5 text-center">
                        <h1 class="h4 mb-2">Pembayaran Berhasil</h1>
                        <p class="text-muted mb-4">Tunjukkan QR ini sebagai bukti pesanan lunas.</p>

                        <div class="mb-3">
                            <img src="{{ $qrDataUri }}" alt="QR Pesanan {{ $orderCode }}" class="img-fluid" style="max-width: 280px;">
                        </div>

                        <div class="bg-light rounded-3 p-3 mb-4 text-start">
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Kode Pesanan</span>
                                <strong>{{ $orderCode }}</strong>
                            </div>
                            <div class="d-flex justify-content-between mb-1">
                                <span class="text-muted">Nama Pemesan</span>
                                <strong>{{ $pesanan->nama }}</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span class="text-muted">Total</span>
                                <strong>Rp {{ number_format($pesanan->total, 0, ',', '.') }}</strong>
                            </div>
                        </div>

                        <a href="{{ route('customer.dashboard') }}" class="btn btn-primary w-100">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
