<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <svg class="bi me-2" width="40" height="32">
            <use xlink:href="#bootstrap"></use>
        </svg>
        <img src="{{ asset('assets/images/apple.png') }}" alt="Apple Logo" height="32" class="me-2">
        <span class="fs-4">Apel Store</span>
    </a>

    @php
        $isLoggedIn = auth()->check();
        $activeRoleUser = $isLoggedIn
            ? auth()->user()?->roleuser?->firstWhere('status', 1) ?? auth()->user()?->roleuser?->first()
            : null;

        $roleId = $activeRoleUser?->idrole ?? session('user_role');
        $roleName = strtolower(trim((string) ($activeRoleUser?->role?->nama_role ?? '')));

        $isVendor = $roleName === 'vendor' || (string) $roleId === '2';
        $isAdmin = in_array($roleName, ['admin', 'administrator'], true) || (string) $roleId === '1';
        $isCustomer = !$isLoggedIn || (!$isVendor && !$isAdmin);

        $isCartOrCheckout =
            request()->routeIs('customer.cart') ||
            request()->routeIs('customer.checkout') ||
            request()->routeIs('payment.show');
    @endphp

    <hr>
    <ul class="nav nav-pills flex-column mb-auto">

        @if ($isCustomer)
            <li class="nav-item">
                <a href="{{ route('customer.dashboard') }}"
                    class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('customer.dashboard') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#home"></use>
                    </svg>
                    Home
                </a>
            </li>
            <li>
                <a href="{{ route('customer.cart') }}"
                    class="nav-link {{ $isCartOrCheckout ? 'active' : 'text-white' }}"
                    aria-current="{{ $isCartOrCheckout ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#table"></use>
                    </svg>
                    Shopping Cart
                </a>
            </li>
            <li>
                <a href="{{ route('customer.dashboard') }}"
                    class="nav-link {{ request()->routeIs('customer.menu') || request()->routeIs('customer.menu.items') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('customer.menu') || request()->routeIs('customer.menu.items') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Products
                </a>
            </li>
        @endif
        {{--  --}}
        @if ($isVendor)
            <li class="nav-item">
                <a href="{{ route('vendor.dashboard') }}"
                    class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('vendor.dashboard') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#home"></use>
                    </svg>
                    Dashboard
                </a>
            </li>
            <li>
                <a href="{{route('vendor.pesanan')}}"
                    class="nav-link {{ request()->routeIs('vendor.pesanan') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('vendor.pesanan') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#list"></use>
                    </svg>
                    Pesanan
                </a>
            </li>
            <li>
                <a href="{{route ('vendor.menu')}}"
                    class="nav-link {{ request()->routeIs('vendor.menu.*') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('vendor.menu.*') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Edit Menu
                </a>
            </li>
        @endif
        {{--  --}}
        @if ($isAdmin)
            <li class="nav-item">
                <a href="{{ route('dashboard') }}"
                    class="nav-link {{ request()->routeIs('dashboard') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('dashboard') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#home"></use>
                    </svg>
                    Dashboard Admin
                </a>
            </li>
            <li>
                <a href="{{ route('kategori.index') }}"
                    class="nav-link {{ request()->routeIs('kategori.*') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('kategori.*') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#grid"></use>
                    </svg>
                    Kategori
                </a>
            </li>
            <li>
                <a href="{{ route('buku.index') }}"
                    class="nav-link {{ request()->routeIs('buku.*') ? 'active' : 'text-white' }}"
                    aria-current="{{ request()->routeIs('buku.*') ? 'page' : 'false' }}">
                    <svg class="bi me-2" width="16" height="16">
                        <use xlink:href="#table"></use>
                    </svg>
                    Buku
                </a>
            </li>
        @endif

        <hr>

        @if (!$isLoggedIn)
            <li>
                <a href="{{ route('login') }}"
                    class="btn w-100 d-flex align-items-center justify-content-center {{ request()->routeIs('login') ? 'btn-light text-dark fw-semibold' : 'btn-outline-light' }}"
                    aria-current="{{ request()->routeIs('login') ? 'page' : 'false' }}">
                    <svg class="bi" width="16" height="16">
                        <use xlink:href="#grid"></use>
                    </svg>
                    <span>Login</span>
                </a>
            </li>
        @else
            <li>
                <a href="{{ route('logout') }}" class="btn btn-outline-light w-100">Logout</a>
            </li>
        @endif
    </ul>

    @if ($isLoggedIn)
        <hr>
        <div class="d-flex align-items-center">
            <img src="https://github.com/mdo.png" alt="" width="32" height="32"
                class="rounded-circle me-2">
            <div class="small">
                <div class="fw-semibold">{{ auth()->user()->username ?? auth()->user()->email }}</div>
                <div class="text-light-emphasis">{{ $activeRoleUser?->role?->nama_role ?? 'User' }}</div>
            </div>
        </div>
    @endif
</div>
