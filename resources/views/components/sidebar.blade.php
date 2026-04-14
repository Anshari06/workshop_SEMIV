<div>
    <!-- It is quality rather than quantity that matters. - Lucius Annaeus Seneca -->
    <nav class="sidebar sidebar-offcanvas" id="sidebar">
        <ul class="nav">
            <li class="nav-item nav-profile">
                <a href="#" class="nav-link">
                    <div class="nav-profile-image">
                        <img src="{{ asset('assets/images/faces/face1.jpg') }}" alt="profile" />
                        <span class="login-status online"></span>
                        <!--change to offline or busy as needed-->
                    </div>
                    <div class="nav-profile-text d-flex flex-column">
                        <span
                            class="font-weight-bold mb-2">{{ Auth::user()->username ?? (Auth::user()->email ?? 'User') }}</span>
                        <span
                            class="text-secondary text-small">{{ Auth::user()->roleuser->first()?->role->nama_role ?? '-' }}</span>
                    </div>
                </a>
            </li>
            <li class="nav-item {{ Request::is('dashboard') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('dashboard') }}">
                    <span class="menu-title">Dashboard</span>
                    <i class="mdi mdi-home menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ Request::is('kategori*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('kategori.index') }}">
                    <span class="menu-title">Kategori Buku</span>
                    <i class="mdi mdi-book-open menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ Request::is('buku*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('buku.index') }}">
                    <span class="menu-title">Data Buku</span>
                    <i class="mdi mdi-book-open-page-variant menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ Request::is('Cetak*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('cetak.sertifikat') }}">
                    <span class="menu-title">Cetak Sertifikat</span>
                    <i class="mdi mdi-printer menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ Request::is('Cetak*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('cetak.undangan') }}">
                    <span class="menu-title">Cetak Undangan</span>
                    <i class="mdi mdi-printer menu-icon"></i>
                </a>
            </li>
            <li class="nav-item {{ Request::is('tag-harga*') ? 'active' : '' }}">
                <a class="nav-link" href="{{ route('tag-harga.index') }}">
                    <span class="menu-title">Tag Harga</span>
                    <i class="mdi mdi-tag-check menu-icon"></i>
                </a>
            </li>

            <li class="nav-item {{ Request::is('jspage*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#jspage-menu" aria-expanded="{{ Request::is('jspage*') ? 'true' : 'false' }}" aria-controls="jspage-menu">
                    <span class="menu-title">Page Js</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-file-chart menu-icon"></i>
                </a>
                <div class="collapse {{ Request::is('jspage*') ? 'show' : '' }}" id="jspage-menu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('jspage.index') ? 'active' : '' }}" href="{{ route('jspage.index') }}">HTML Table</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('jspage.datatables') ? 'active' : '' }}" href="{{ route('jspage.datatables') }}">DataTables</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('jspage.kota') ? 'active' : '' }}" href="{{ route('jspage.kota') }}">Kota</a>
                        </li>
                    </ul>
                </div>
            </li>

            <li class="nav-item {{ Request::is('ajax-axios*') ? 'active' : '' }}">
                <a class="nav-link" data-bs-toggle="collapse" href="#ajax-axios-menu" aria-expanded="{{ Request::is('ajax-axios*') ? 'true' : 'false' }}" aria-controls="ajax-axios-menu">
                    <span class="menu-title">Modul 5</span>
                    <i class="menu-arrow"></i>
                    <i class="mdi mdi-map menu-icon"></i>
                </a>
                <div class="collapse {{ Request::is('ajax-axios*') ? 'show' : '' }}" id="ajax-axios-menu">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('ajax-axios.index') ? 'active' : '' }}" href="{{ route('ajax-axios.index') }}">AJAX Region</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ Request::routeIs('ajax-axios.axios') ? 'active' : '' }}" href="{{ route('ajax-axios.axios') }}">Axios Region</a>
                        </li>
                    </ul>
                </div>
            </li>
            

        </ul>
    </nav>
</div>

