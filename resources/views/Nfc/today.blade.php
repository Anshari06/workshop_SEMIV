@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-nfc"></i>
            </span> Absensi NFC - Hari Ini
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('nfc.index') }}">NFC</a></li>
                <li class="breadcrumb-item active" aria-current="page">Hari Ini</li>
            </ul>
        </nav>
    </div>

    <div class="row mb-3">
        <div class="col-md-4">
            <div class="card text-center py-3">
                <div style="font-size:11px;text-transform:uppercase;color:#64748b;font-weight:700">Total User NFC</div>
                <div style="font-size:36px;font-weight:800;color:#4b49ac">{{ $totalUsers }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center py-3">
                <div style="font-size:11px;text-transform:uppercase;color:#64748b;font-weight:700">Sudah Absen</div>
                <div style="font-size:36px;font-weight:800;color:#2dc76d">{{ $attendances->count() }}</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center py-3">
                <div style="font-size:11px;text-transform:uppercase;color:#64748b;font-weight:700">Belum Absen</div>
                <div style="font-size:36px;font-weight:800;color:#dc3545">{{ max(0, $totalUsers - $attendances->count()) }}</div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Daftar Absensi — {{ $today->format('d M Y') }}</h4>
                <a href="{{ route('nfc.history') }}" class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-history me-1"></i> Semua Riwayat
                </a>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama User</th>
                            <th>NFC UID</th>
                            <th>Waktu Absen</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $att)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $att->user->username ?? $att->user->email ?? '-' }}</td>
                                <td><code>{{ $att->user->nfc_uid ?? '-' }}</code></td>
                                <td>{{ $att->scanned_at->format('H:i:s') }}</td>
                                <td><span class="badge bg-success">Hadir</span></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada yang absen hari ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
