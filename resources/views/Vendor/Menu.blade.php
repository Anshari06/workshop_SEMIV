@extends('layouts.payment')

@section('title', 'Menu Vendor')

@push('styles')
    <style>
        .menu-shell {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 14px;
            padding: 1.25rem;
        }

        .menu-header {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            background: #fff;
            padding: 1rem;
        }

        .metric-card {
            background: #fff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: none;
        }

        .menu-thumb {
            width: 56px;
            height: 56px;
            object-fit: cover;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
            background: #f8fafc;
        }

        .menu-card {
            border: 1px solid #e5e7eb;
            border-radius: 12px;
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="menu-shell">
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

            <div class="menu-header d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3 mb-3">
                <div>
                    <h1 class="h4 mb-1">Menu {{ $vendor->nama_vendor }}</h1>
                    <p class="mb-0 text-muted">Kelola daftar menu dengan tampilan sederhana dan cepat.</p>
                </div>
                <div class="d-flex gap-2">
                    <a href="{{ route('vendor.dashboard') }}" class="btn btn-light border">Dashboard</a>
                    <button class="btn btn-primary" data-bs-toggle="collapse" data-bs-target="#formTambahMenu" aria-expanded="false" aria-controls="formTambahMenu">Tambah Menu</button>
                </div>
            </div>

            <div class="collapse mb-3" id="formTambahMenu">
                <div class="card metric-card">
                    <div class="card-body">
                        <h6 class="mb-3">Tambah Menu Baru</h6>
                        <form action="{{ route('vendor.menu.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                            @csrf
                            <div class="col-12 col-md-5">
                                <label class="form-label">Nama Menu</label>
                                <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Nasi Goreng" required>
                            </div>
                            <div class="col-12 col-md-3">
                                <label class="form-label">Harga</label>
                                <input type="number" name="harga" class="form-control" min="0" placeholder="20000" required>
                            </div>
                            <div class="col-12 col-md-4">
                                <label class="form-label">Gambar</label>
                                <input type="file" name="path_gambar" class="form-control" accept="image/*">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary">Simpan Menu</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Total Menu</p>
                            <h4 class="mb-0">{{ $menus->count() }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Pesanan Masuk</p>
                            <h4 class="mb-0">{{ $totalOrders }}</h4>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-sm-6 col-xl-4">
                    <div class="card metric-card h-100">
                        <div class="card-body">
                            <p class="text-muted mb-1">Pendapatan (Lunas)</p>
                            <h4 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h4>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card metric-card mb-3">
                <div class="card-header bg-white border-0 d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Daftar Menu</h5>
                    <small class="text-muted">Tabel ringkas menu vendor</small>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Menu</th>
                                    <th class="text-end">Harga</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($menus as $menu)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-3">
                                                <img src="{{ $menu->image_url }}" alt="{{ $menu->nama_menu }}" class="menu-thumb" onerror="this.src='{{ asset('assets/images/apple.png') }}'">
                                                <div>
                                                    <div class="fw-semibold">{{ $menu->nama_menu }}</div>
                                                    <small class="text-muted">ID Menu: {{ $menu->id }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                        <td class="text-center">
                                            <div class="d-flex justify-content-center gap-2">
                                                <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}">Edit</button>
                                                <form action="{{ route('vendor.menu.delete', $menu->id) }}" method="POST" onsubmit="return confirm('Yakin hapus menu ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-4">Belum ada menu untuk vendor ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                @forelse ($menus as $menu)
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="card menu-card h-100">
                            <img src="{{ $menu->image_url }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $menu->nama_menu }}" onerror="this.src='{{ asset('assets/images/apple.png') }}'">
                            <div class="card-body d-flex flex-column">
                                <h6 class="card-title mb-1">{{ $menu->nama_menu }}</h6>
                                <p class="text-primary fw-semibold mb-3">Rp {{ number_format($menu->harga, 0, ',', '.') }}</p>
                                <div class="mt-auto d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary flex-grow-1" data-bs-toggle="modal" data-bs-target="#editMenuModal{{ $menu->id }}">Edit</button>
                                    <form action="{{ route('vendor.menu.delete', $menu->id) }}" method="POST" class="flex-grow-1" onsubmit="return confirm('Yakin hapus menu ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100">Hapus</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-light border mb-0">Belum ada menu. Klik tombol <strong>Tambah Menu</strong> untuk mulai.</div>
                    </div>
                @endforelse
            </div>

            @foreach ($menus as $menu)
                <div class="modal fade" id="editMenuModal{{ $menu->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Menu</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <form action="{{ route('vendor.menu.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Menu</label>
                                        <input type="text" name="nama_menu" class="form-control" value="{{ $menu->nama_menu }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Harga</label>
                                        <input type="number" name="harga" class="form-control" min="0" value="{{ $menu->harga }}" required>
                                    </div>
                                    <div class="mb-2">
                                        <label class="form-label">Ganti Gambar (opsional)</label>
                                        <input type="file" name="path_gambar" class="form-control" accept="image/*">
                                    </div>
                                    <small class="text-muted">Kosongkan jika tidak ingin mengganti gambar.</small>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Batal</button>
                                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
