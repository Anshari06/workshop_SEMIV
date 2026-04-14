@extends('layouts.payment')

@section('title', 'Shopping Cart')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Shopping Cart</h2>
                <p class="text-muted mb-0">Kamu bisa gabungkan pesanan dari banyak vendor.</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">Lanjut Pilih Vendor</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (empty($cart))
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <h5 class="mb-2">Keranjang masih kosong</h5>
                    <p class="text-muted mb-3">Pilih menu dari vendor untuk mulai belanja.</p>
                    <a href="{{ route('customer.dashboard') }}" class="btn btn-primary">Pilih Vendor</a>
                </div>
            </div>
        @else
            <div class="row g-4">
                <div class="col-lg-8">
                    @foreach ($cart as $vendorId => $vendorCart)
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $vendorCart['vendor_name'] }}</h5>
                                <form action="{{ route('customer.cart.vendor.delete', $vendorId) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus Vendor</button>
                                </form>
                            </div>
                            <div class="card-body">
                                @foreach ($vendorCart['items'] as $item)
                                    <div class="d-flex justify-content-between align-items-start border rounded p-3 mb-2">
                                        <div>
                                            <div class="fw-semibold">{{ $item['nama_menu'] }}</div>
                                            <small class="text-muted">Rp {{ number_format($item['harga'], 0, ',', '.') }} / item</small>
                                        </div>
                                        <div class="text-end">
                                            <div class="fw-semibold mb-2">Rp {{ number_format($item['subtotal'], 0, ',', '.') }}</div>
                                            <div class="d-flex align-items-center gap-1">
                                                <form action="{{ route('customer.cart.item.update', [$vendorId, $item['menu_id']]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="qty" value="{{ max(1, $item['qty'] - 1) }}">
                                                    <button class="btn btn-sm btn-outline-secondary" type="submit">-</button>
                                                </form>
                                                <span class="px-2">{{ $item['qty'] }}</span>
                                                <form action="{{ route('customer.cart.item.update', [$vendorId, $item['menu_id']]) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="qty" value="{{ $item['qty'] + 1 }}">
                                                    <button class="btn btn-sm btn-outline-secondary" type="submit">+</button>
                                                </form>
                                                <form action="{{ route('customer.cart.item.delete', [$vendorId, $item['menu_id']]) }}" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="btn btn-sm btn-outline-danger" type="submit">Hapus</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                <div class="text-end fw-bold mt-2">
                                    Subtotal Vendor: Rp {{ number_format($vendorCart['subtotal'], 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm sticky-top" style="top: 1rem;">
                        <div class="card-body">
                            <h5 class="card-title">Checkout</h5>
                            <div class="d-flex justify-content-between mb-3">
                                <span>Grand Total</span>
                                <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                            </div>

                            <form action="{{ route('customer.checkout') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label for="customer_name" class="form-label">Nama Pemesan</label>
                                    <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                                </div>

                                <div class="mb-3">
                                    <label for="customer_phone" class="form-label">No HP</label>
                                    <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                                </div>

                                <button type="submit" class="btn btn-success w-100">Checkout Semua Vendor</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection
