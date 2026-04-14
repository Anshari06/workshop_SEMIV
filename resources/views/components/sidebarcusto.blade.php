<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
      <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"></use></svg>
      <img src="{{ asset('assets/images/apple.png') }}" alt="Apple Logo" height="32" class="me-2">
      <span class="fs-4">Apel Store</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      @php
        $isCartOrCheckout = request()->routeIs('customer.cart')
          || request()->routeIs('customer.checkout')
          || request()->routeIs('payment.show');
      @endphp
      <li class="nav-item">
        <a href="{{ route('customer.dashboard') }}"
          class="nav-link {{ request()->routeIs('customer.dashboard') ? 'active' : 'text-white' }}"
          aria-current="{{ request()->routeIs('customer.dashboard') ? 'page' : 'false' }}">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#home"></use></svg>
          Home
        </a>
      </li>
      <li>
        <a href="{{ route('customer.cart') }}"
          class="nav-link {{ $isCartOrCheckout ? 'active' : 'text-white' }}"
          aria-current="{{ $isCartOrCheckout ? 'page' : 'false' }}">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#table"></use></svg>
          Shopping Cart
        </a>
      </li>
      <li>
        <a href="#"
          class="nav-link {{ request()->routeIs('customer.menu') || request()->routeIs('customer.menu.items') ? 'active' : 'text-white' }}"
          aria-current="{{ request()->routeIs('customer.menu') || request()->routeIs('customer.menu.items') ? 'page' : 'false' }}">
          <svg class="bi me-2" width="16" height="16"><use xlink:href="#grid"></use></svg>
          Products
        </a>
      </li>
      <hr>
      <li>
        <a href="{{ route('login') }}"
          class="btn w-100 d-flex align-items-center justify-content-center {{ request()->routeIs('login') ? 'btn-light text-dark fw-semibold' : 'btn-outline-light' }}"
          aria-current="{{ request()->routeIs('login') ? 'page' : 'false' }}">
          <svg class="bi" width="16" height="16"><use xlink:href="#grid"></use></svg>
          <span>Login</span>
        </a>
      </li>
    </ul>
    <hr>
    <div class="dropdown">
      <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
        <img src="https://github.com/mdo.png" alt="" width="32" height="32" class="rounded-circle me-2">
        <strong>mdo</strong>
      </a>
      <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
        <li><hr class="dropdown-divider"></li>
      </ul>
    </div>
</div>