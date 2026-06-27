@extends('layouts.apps')
@section('content')
<style>
    .stat-box { background: #fff; border-radius: 16px; padding: 20px; text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,0.06); border: 1px solid #f0f0f0; }
    .stat-number { font-size: 36px; font-weight: 900; color: #4b49ac; }
    .stat-number.green { color: #22c55e; }
    .stat-number.red { color: #ef4444; }
    .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #9ca3af; font-weight: 600; margin-top: 4px; }
    .table-modern { border-collapse: separate; border-spacing: 0; }
    .table-modern thead th { background: #f8fafc; border-bottom: 2px solid #e5e7eb; padding: 12px 16px; font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #9ca3af; font-weight: 700; }
    .table-modern tbody td { padding: 14px 16px; border-bottom: 1px solid #f0f0f0; vertical-align: middle; }
    .table-modern tbody tr:hover td { background: #f8fafc; }
    .table-modern tbody tr:last-child td { border-bottom: none; }
    .badge-hadir { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: 700; }
    .page-header { display: flex; justify-content: space-between; align-items: center; }
</style>

<div class="page-header mb-4">
    <div>
        <h3 class="page-title mb-0">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-calendar-today"></i>
            </span> Absensi Hari Ini
        </h3>
        <div style="font-size:13px;color:#9ca3af;margin-top:4px;margin-left:44px">
            {{ $today->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
        </div>
    </div>
    <div class="d-flex gap-2">
        <a href="{{ route('nfc.index') }}" class="btn btn-primary" style="border-radius:12px">
            <i class="mdi mdi-nfc me-1"></i> Scan NFC
        </a>
        <a href="{{ route('nfc.history') }}" class="btn btn-outline-primary" style="border-radius:12px">
            <i class="mdi mdi-history me-1"></i> Riwayat
        </a>
    </div>
</div>

{{-- Stats --}}
<div class="row g-3 mb-4">
    <div class="col-4">
        <div class="stat-box">
            <div class="stat-number">{{ $totalUsers }}</div>
            <div class="stat-label">User NFC</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-box">
            <div class="stat-number green">{{ $attendances->count() }}</div>
            <div class="stat-label">Sudah Hadir</div>
        </div>
    </div>
    <div class="col-4">
        <div class="stat-box">
            <div class="stat-number red">{{ max(0, $totalUsers - $attendances->count()) }}</div>
            <div class="stat-label">Belum Absen</div>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-3">Daftar Hadir</h4>
        <div class="table-responsive">
            <table class="table table-modern">
                <thead>
                    <tr>
                        <th style="width:50px">#</th>
                        <th>Nama User</th>
                        <th>NFC UID</th>
                        <th>Waktu Absen</th>
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
                            <td><span class="badge-hadir"><i class="mdi mdi-check-circle me-1"></i> Hadir</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <i class="mdi mdi-nfc-off" style="font-size:3rem;display:block;margin-bottom:8px;opacity:0.3"></i>
                                Belum ada yang absen hari ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
