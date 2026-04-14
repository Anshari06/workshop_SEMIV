@extends('layouts.payment')

@section('title', 'Checkout Berhasil')

@section('content')
    
    <div class="container-fluid">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h3 class="mb-2">Checkout Berhasil</h3>
                <p class="text-muted mb-4">
                    Pesanan atas nama <strong>{{ $customerName }}</strong> ({{ $customerPhone }}) berhasil dibuat.
                    Karena keranjang berisi beberapa vendor, pesanan dipecah menjadi beberapa invoice.
                </p>

                <div class="list-group mb-4">
                    @foreach ($orders as $order)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <div class="fw-semibold">Invoice #INV-{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</div>
                                <small class="text-muted">Total: Rp {{ number_format($order->total, 0, ',', '.') }}</small>
                            </div>
                            <a href="{{ route('payment.show', ['pesanan' => $order->id]) }}" class="btn btn-primary btn-sm">
                                Bayar
                            </a>
                        </div>
                    @endforeach
                </div>

                <div class="d-flex gap-2">
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">Belanja Lagi</a>
                </div>
            </div>
        </div>
    </div>
@endsection
