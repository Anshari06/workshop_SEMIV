@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-history"></i>
            </span> Riwayat Kunjungan
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('Geotag.index') }}">Geotag</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Daftar Kunjungan</h4>
                        <a href="{{ route('Geotag.index') }}" class="btn btn-sm btn-primary">
                            <i class="mdi mdi-plus"></i> Kunjungan Baru
                        </a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Toko</th>
                                    <th>Barcode</th>
                                    <th>Jarak (m)</th>
                                    <th>Threshold Efektif (m)</th>
                                    <th>Status</th>
                                    <th>Waktu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($kunjungans as $kj)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $kj->nama_toko }}</td>
                                        <td><strong>{{ $kj->barcode_toko }}</strong></td>
                                        <td>{{ number_format($kj->jarak, 1) }}</td>
                                        <td>{{ number_format($kj->threshold_efektif, 1) }}</td>
                                        <td>
                                            @if ($kj->status === 'diterima')
                                                <span class="badge bg-success">DITERIMA</span>
                                            @else
                                                <span class="badge bg-danger">DITOLAK</span>
                                            @endif
                                        </td>
                                        <td>{{ $kj->created_at->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">Belum ada riwayat kunjungan.</td>
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