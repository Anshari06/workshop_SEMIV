@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store"></i>
            </span> Data Toko
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Daftar Toko <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Daftar Toko</h4>
                        <a href="{{ route('toko.create') }}" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-plus"></i> Tambah Toko
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Barcode</th>
                                    <th>Nama Toko</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Accuracy</th>
                                    <th class="text-center">QR Code</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($tokos as $toko)
                                    <tr>
                                        <td><strong>{{ $toko->barcode }}</strong></td>
                                        <td>{{ $toko->nama_toko }}</td>
                                        <td>{{ $toko->latitude }}</td>
                                        <td>{{ $toko->longitude }}</td>
                                        <td>{{ $toko->accuracy }} m</td>
                                        <td class="text-center">
                                            @if ($toko->qr_svg)
                                                <div style="display:inline-block">{!! $toko->qr_svg !!}</div>
                                            @else
                                                <span class="text-muted small">QR unavailable</span>
                                            @endif
                                        </td>
                                        <td>
                                            <a href="{{ route('toko.edit', $toko->barcode) }}" class="btn btn-sm btn-warning">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="{{ route('toko.destroy', $toko->barcode) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus toko ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    <i class="mdi mdi-delete"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada data toko.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
