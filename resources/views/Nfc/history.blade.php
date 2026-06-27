@extends('layouts.apps')
@section('content')
<style>
    .table-modern { border-collapse: separate; border-spacing: 0; }
    .table-modern thead th { background: #f8fafc; border-bottom: 2px solid #e5e7eb; padding: 12px 16px; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #9ca3af; font-weight: 700; }
    .table-modern tbody td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    .table-modern tbody tr:hover td { background: #f8fafc; }
    .table-modern tbody tr:last-child td { border-bottom: none; }
    .page-header { display: flex; justify-content: space-between; align-items: center; }
    .badge-success { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .badge-default { background: #f3f4f6; color: #6b7280; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
</style>

<div class="page-header mb-4">
    <div>
        <h3 class="page-title mb-0">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-history"></i>
            </span> Riwayat Absensi
        </h3>
        <div style="font-size:13px;color:#9ca3af;margin-top:4px;margin-left:44px">
            Semua record absensi NFC
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('nfc.index') }}" class="btn btn-primary" style="border-radius:12px">
            <i class="mdi mdi-nfc me-1"></i> Scan NFC
        </a>
        <a href="{{ route('nfc.today') }}" class="btn btn-outline-primary" style="border-radius:12px">
            <i class="mdi mdi-calendar-today me-1"></i> Hari Ini
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Nama User</th>
                        <th>NFC UID</th>
                        <th>Waktu</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($attendances as $att)
                        <tr>
                            <td style="color:#9ca3af;font-weight:600">{{ $loop->iteration }}</td>
                            <td>
                                <div style="font-weight:700;color:#1f2937">{{ $att->user->username ?? ($att->user->email ?? '-') }}</div>
                            </td>
                            <td><code style="background:#f0f0ff;color:#4b49ac;padding:2px 8px;border-radius:6px;font-size:12px">{{ $att->user->nfc_uid ?? '-' }}</code></td>
                            <td style="font-weight:700;color:#374151">{{ \Carbon\Carbon::parse($att->scanned_at)->format('H:i:s') }}</td>
                            <td style="color:#6b7280">{{ \Carbon\Carbon::parse($att->scanned_at)->locale('id')->format('d M Y') }}</td>
                            <td><span class="badge-success"><i class="mdi mdi-check-circle me-1"></i> Hadir</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-5">
                                <i class="mdi mdi-clipboard-text-off" style="font-size:3rem;display:block;margin-bottom:8px;opacity:0.3"></i>
                                Belum ada riwayat absensi.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-3">
            {{ $attendances->links() }}
        </div>
    </div>
</div>
@endsection
