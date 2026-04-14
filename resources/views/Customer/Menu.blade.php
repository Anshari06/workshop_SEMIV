@extends('layouts.payment')

@section('title', 'Menu Vendor')

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h2 class="mb-1">Menu {{ $vendor->nama_vendor }}</h2>
                <p class="text-muted mb-0">Pilih menu lalu tambahkan ke keranjang.</p>
            </div>
            <a href="{{ route('customer.dashboard') }}" class="btn btn-outline-secondary">Kembali</a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row g-4">
            <div class="col-lg-8">
                <div class="row g-3">
                    @forelse ($vendor->menus as $menu)
                        @php
                            $rawPath = trim((string) ($menu->path_gambar ?? ''), '/');
                            $imagePath = 'https://via.placeholder.com/640x360?text=No+Image';

                            if ($rawPath !== '') {
                                $candidates = [
                                    $rawPath,
                                    'assets/images/' . $rawPath,
                                    'assets/images/MENU/' . basename($rawPath),
                                ];

                                foreach ($candidates as $candidate) {
                                    if (file_exists(public_path($candidate))) {
                                        $imagePath = asset($candidate);
                                        break;
                                    }
                                }
                            }
                        @endphp
                        <div class="col-md-6">
                            <div class="card h-100 border-0 shadow-sm">
                                <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $menu->nama_menu }}"
                                    style="height: 180px; object-fit: cover;">
                                <div class="card-body d-flex flex-column">
                                    <h5 class="card-title mb-1">{{ $menu->nama_menu }}</h5>
                                    <p class="text-primary fw-bold mb-3">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>

                                    <div class="input-group mt-auto">
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="changeQty({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, -1)">-</button>
                                        <input type="text" class="form-control text-center" id="qty-{{ $menu->id }}"
                                            value="0" readonly>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="changeQty({{ $menu->id }}, '{{ addslashes($menu->nama_menu) }}', {{ $menu->harga }}, 1)">+</button>
                                    </div>
                                    <small class="text-muted mt-2">Jumlah otomatis masuk keranjang.</small>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-12">
                            <div class="alert alert-warning mb-0">Belum ada menu untuk vendor ini.</div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">Keranjang</h5>
                        <div id="cart-items" class="mb-3">
                            <p class="text-muted mb-0">Belum ada item dipilih.</p>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between mb-3">
                            <span>Total</span>
                            <strong id="cart-total">Rp 0</strong>
                        </div>

                        <form id="checkout-form" action="{{ route('customer.store') }}" method="POST">
                            @csrf
                            <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                            <div id="cart-hidden-inputs"></div>

                            <div class="mb-3">
                                <label for="customer_name" class="form-label">Nama Pemesan</label>
                                <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                            </div>

                            <div class="mb-3">
                                <label for="customer_phone" class="form-label">No HP</label>
                                <input type="text" class="form-control" id="customer_phone" name="customer_phone" required>
                            </div>

                            <button type="submit" class="btn btn-success w-100" id="checkout-button" disabled>
                                Masukkan ke Keranjang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const cart = {};

        function changeQty(menuId, menuName, price, diff) {
            const input = document.getElementById(`qty-${menuId}`);
            let current = parseInt(input.value, 10) || 0;
            current = Math.max(0, current + diff);
            input.value = current;

            if (current <= 0) {
                delete cart[menuId];
            } else {
                cart[menuId] = {
                    name: menuName,
                    price: price,
                    qty: current,
                };
            }

            renderCart();
        }

        function updateCartQty(menuId, diff) {
            if (!cart[menuId]) {
                return;
            }

            const nextQty = cart[menuId].qty + diff;
            if (nextQty <= 0) {
                delete cart[menuId];
                const qtyInput = document.getElementById(`qty-${menuId}`);
                if (qtyInput) {
                    qtyInput.value = 0;
                }
            } else {
                cart[menuId].qty = nextQty;
                const qtyInput = document.getElementById(`qty-${menuId}`);
                if (qtyInput) {
                    qtyInput.value = nextQty;
                }
            }

            renderCart();
        }

        function removeFromCart(menuId) {
            if (!cart[menuId]) {
                return;
            }

            delete cart[menuId];
            const qtyInput = document.getElementById(`qty-${menuId}`);
            if (qtyInput) {
                qtyInput.value = 0;
            }
            renderCart();
        }

        function renderCart() {
            const cartItems = document.getElementById('cart-items');
            const cartTotal = document.getElementById('cart-total');
            const hiddenInputs = document.getElementById('cart-hidden-inputs');
            const checkoutButton = document.getElementById('checkout-button');

            let html = '';
            let total = 0;

            hiddenInputs.innerHTML = '';

            const ids = Object.keys(cart);
            if (ids.length === 0) {
                cartItems.innerHTML = '<p class="text-muted mb-0">Belum ada item dipilih.</p>';
                cartTotal.textContent = 'Rp 0';
                checkoutButton.disabled = true;
                return;
            }

            ids.forEach((id) => {
                const item = cart[id];
                const subtotal = item.price * item.qty;
                total += subtotal;

                html += `
                    <div class="border rounded-3 p-2 mb-2">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                            <div class="fw-semibold">${item.name}</div>
                            <small class="text-muted">Rp ${item.price.toLocaleString('id-ID')} / item</small>
                            </div>
                            <div class="text-end fw-semibold">Rp ${subtotal.toLocaleString('id-ID')}</div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-outline-secondary" onclick="updateCartQty(${id}, -1)">-</button>
                                <button type="button" class="btn btn-light" disabled>${item.qty}</button>
                                <button type="button" class="btn btn-outline-secondary" onclick="updateCartQty(${id}, 1)">+</button>
                            </div>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFromCart(${id})">Hapus</button>
                        </div>
                    </div>
                `;

                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = `items[${id}]`;
                hidden.value = item.qty;
                hiddenInputs.appendChild(hidden);
            });

            cartItems.innerHTML = html;
            cartTotal.textContent = `Rp ${total.toLocaleString('id-ID')}`;
            checkoutButton.disabled = false;
        }
    </script>
@endpush
