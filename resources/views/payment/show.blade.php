@extends('layouts.payment')

@section('title', 'Pembayaran Pesanan')

@section('content')
    @php
        $orderCode = $orderCode ?? 'INV-' . now()->format('YmdHis');
        $customerName = $customerName ?? 'Guest Customer';
        $orderDate = $orderDate ?? now()->format('d M Y H:i');
        $subtotal = $subtotal ?? 0;
        $serviceFee = $serviceFee ?? 2000;
        $total = $total ?? $subtotal + $serviceFee;

        $items = $items ?? [
            ['nama_menu' => 'Americano', 'qty' => 1, 'harga' => 18000],
            ['nama_menu' => 'Latte', 'qty' => 1, 'harga' => 22000],
        ];
    @endphp

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-10">
                <div class="card payment-card">
                    <div class="card-body p-4 p-md-5">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                            <div>
                                <h1 class="h4 mb-1">Pembayaran Pesanan</h1>
                                <p class="text-muted mb-0">Silakan cek detail pesanan sebelum melanjutkan pembayaran.</p>
                            </div>
                            <span class="badge text-bg-primary mt-3 mt-md-0 px-3 py-2">{{ $orderCode }}</span>
                        </div>

                        <div class="row g-4">
                            <div class="col-md-7">
                                <div class="border rounded-4 p-3 p-md-4 h-100">
                                    <p class="section-title mb-3">Detail Item</p>

                                    <div class="table-responsive">
                                        <table class="table align-middle mb-0">
                                            <thead>
                                                <tr>
                                                    <th>Menu</th>
                                                    <th class="text-center">Qty</th>
                                                    <th class="text-end">Harga</th>
                                                    <th class="text-end">Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($items as $item)
                                                    @php
                                                        $lineSubtotal = ($item['qty'] ?? 0) * ($item['harga'] ?? 0);
                                                    @endphp
                                                    <tr>
                                                        <td>{{ $item['nama_menu'] ?? '-' }}</td>
                                                        <td class="text-center">{{ $item['qty'] ?? 0 }}</td>
                                                        <td class="text-end">Rp {{ number_format($item['harga'] ?? 0, 0, ',', '.') }}</td>
                                                        <td class="text-end">Rp {{ number_format($lineSubtotal, 0, ',', '.') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-5">
                                <div class="border rounded-4 p-3 p-md-4 h-100">
                                    <p class="section-title mb-3">Ringkasan</p>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Nama Pemesan</small>
                                        <strong>{{ $customerName }}</strong>
                                    </div>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">Tanggal Pesanan</small>
                                        <strong>{{ $orderDate }}</strong>
                                    </div>

                                    <hr>

                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-muted">Subtotal</span>
                                        <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted">Biaya Layanan</span>
                                        <span>Rp {{ number_format($serviceFee, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center border-top pt-3 mb-4">
                                        <strong>Total Bayar</strong>
                                        <strong class="text-primary">Rp {{ number_format($total, 0, ',', '.') }}</strong>
                                    </div>

                                    <button type="button" id="pay-button" class="btn btn-primary w-100 py-2">Bayar Sekarang</button>
                                    <small class="text-muted d-block mt-2">Kamu akan diarahkan ke Midtrans Snap untuk memilih metode pembayaran.</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ $midtransClientKey }}"></script>
    <script>
        const successRedirectUrl = '{{ route('customer.dashboard') }}';

        document.getElementById('pay-button').addEventListener('click', function() {
            window.snap.pay('{{ $snapToken }}', {
                onSuccess: function(result) {
                    alert('Pembayaran berhasil.');
                    window.location.href = successRedirectUrl;
                },
                onPending: function(result) {
                    alert('Pembayaran pending. Silakan selesaikan pembayaran.');
                },
                onError: function(result) {
                    alert('Pembayaran gagal. Silakan coba lagi.');
                    console.error(result);
                },
                onClose: function() {
                    console.log('Popup Midtrans ditutup sebelum menyelesaikan pembayaran.');
                }
            });
        });
    </script>
@endsection
