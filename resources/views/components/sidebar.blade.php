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

        </ul>
    </nav>
</div>
