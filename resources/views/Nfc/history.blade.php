@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-nfc"></i>
            </span> Riwayat Absensi NFC
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('nfc.index') }}">NFC</a></li>
                <li class="breadcrumb-item active" aria-current="page">Riwayat</li>
            </ul>
        </nav>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="card-title mb-0">Riwayat Absensi</h4>
                <a href="{{ route('nfc.today') }}" class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-calendar-today me-1"></i> Hari Ini
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
                            <th>Tanggal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $att)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $att->user->username ?? $att->user->email ?? '-' }}</td>
                                <td><code>{{ $att->user->nfc_uid ?? '-' }}</code></td>
                                <td>{{ $att->scanned_at->format('H:i:s') }}</td>
                                <td>{{ $att->scanned_at->format('d M Y') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-4">Belum ada riwayat absensi.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-3">
                    {{ $attendances->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
