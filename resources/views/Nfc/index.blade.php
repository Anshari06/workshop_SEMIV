@extends('layouts.apps')
@section('content')
<style>
    .nfc-scan-zone {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 32px;
        color: #fff;
        text-align: center;
        position: relative;
        overflow: hidden;
    }
    .nfc-scan-zone::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: rotate-bg 6s linear infinite;
    }
    @keyframes rotate-bg {
        0% { transform: rotate(0deg); }
        100% { transform: rotate(360deg); }
    }
    .nfc-ring {
        width: 140px;
        height: 140px;
        border: 5px solid rgba(255,255,255,0.3);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
        position: relative;
        transition: all 0.3s ease;
    }
    .nfc-ring::after {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        border: 3px solid rgba(255,255,255,0.5);
        animation: ping-ring 1.5s ease-out infinite;
    }
    .nfc-ring.scanning { border-color: #4ade80; }
    .nfc-ring.scanning::after { border-color: #4ade80; }
    .nfc-ring.success { border-color: #4ade80; background: rgba(74,222,128,0.15); }
    .nfc-ring.error { border-color: #f87171; background: rgba(248,113,113,0.15); }
    .nfc-ring.registered { border-color: #fbbf24; background: rgba(251,191,36,0.15); }
    @keyframes ping-ring {
        0% { transform: scale(1); opacity: 1; }
        100% { transform: scale(1.5); opacity: 0; }
    }
    .btn-scan {
        background: #fff;
        color: #667eea;
        border: none;
        padding: 14px 36px;
        border-radius: 50px;
        font-size: 16px;
        font-weight: 700;
        cursor: pointer;
        transition: all 0.2s;
        position: relative;
        z-index: 1;
    }
    .btn-scan:hover { background: #f0f0ff; transform: scale(1.03); }
    .btn-scan:disabled { opacity: 0.6; cursor: not-allowed; transform: none; }
    .btn-scan:active { transform: scale(0.97); }
    .result-card {
        background: #fff;
        border-radius: 16px;
        padding: 24px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.08);
        border: 1px solid #f0f0f0;
        margin-top: 20px;
    }
    .result-card.success { border-color: #4ade80; }
    .result-card.error { border-color: #f87171; }
    .result-card.registered { border-color: #fbbf24; }
    .success-name { font-size: 22px; font-weight: 800; color: #166534; }
    .success-time { font-size: 48px; font-weight: 900; color: #22c55e; line-height: 1; }
    .stat-box {
        background: #fff;
        border-radius: 16px;
        padding: 20px;
        text-align: center;
        box-shadow: 0 2px 12px rgba(0,0,0,0.06);
        border: 1px solid #f0f0f0;
    }
    .stat-number { font-size: 36px; font-weight: 900; color: #4b49ac; }
    .stat-number.green { color: #22c55e; }
    .stat-number.red { color: #ef4444; }
    .stat-label { font-size: 11px; text-transform: uppercase; letter-spacing: .08em; color: #9ca3af; font-weight: 600; margin-top: 4px; }
    .badge-api { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 20px; font-size: 12px; font-weight: 600; }
    .badge-api.ok { background: #dcfce7; color: #166534; }
    .badge-api.no { background: #fee2e2; color: #991b1b; }
    .info-card { background: #f8fafc; border-radius: 12px; padding: 16px; border: 1px solid #e5e7eb; }
    .info-row { display: flex; justify-content: space-between; padding: 8px 0; border-bottom: 1px solid #f0f0f0; }
    .info-row:last-child { border-bottom: none; }
    .info-label { font-size: 12px; color: #9ca3af; font-weight: 600; text-transform: uppercase; }
    .info-value { font-size: 14px; color: #374151; font-weight: 700; }
</style>

<div class="page-header">
    <h3 class="page-title">
        <span class="page-title-icon bg-gradient-primary text-white me-2">
            <i class="mdi mdi-nfc"></i>
        </span> NFC Absensi
    </h3>
    <nav aria-label="breadcrumb">
        <ul class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('nfc.today') }}">NFC</a></li>
            <li class="breadcrumb-item active" aria-current="page">Scan</li>
        </ul>
    </nav>
</div>

<div id="alertArea"></div>

{{-- DEBUG: Raw NFC Data --}}
<div class="result-card mb-4" id="debugCard" style="display:none">
    <div class="d-flex justify-content-between align-items-center mb-2">
        <p style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:700;margin:0">Raw NFC Data</p>
        <button onclick="document.getElementById('debugCard').style.display='none'" class="btn btn-sm btn-outline-secondary">Tutup</button>
    </div>
    <pre id="debugContent" style="font-size:11px;white-space:pre-wrap;word-break:break-all;color:#374151;margin:0;background:#f8fafc;padding:12px;border-radius:8px"></pre>
</div>

<div class="row g-4">

    {{-- LEFT: Scanner --}}
    <div class="col-lg-5">
        <div class="nfc-scan-zone">
            <div id="nfcRing" class="nfc-ring">
                <i id="nfcIcon" class="mdi mdi-nfc" style="font-size:3.5rem;color:#fff;position:relative;z-index:1"></i>
            </div>
            <p id="scanStatus" style="position:relative;z-index:1;font-size:15px;opacity:0.9;margin-bottom:16px">
                Tempelkan kartu NFC ke HP
            </p>
            <div style="position:relative;z-index:1">
                <button type="button" id="btnScanNfc" class="btn-scan">
                    <i class="mdi mdi-nfc-search-variant me-2"></i> Mulai Scan
                </button>
                <button type="button" id="btnStopNfc" class="btn-scan" style="background:rgba(255,255,255,0.15);color:#fff;display:none;margin-top:8px">
                    <i class="mdi mdi-close me-2"></i> Batal
                </button>
            </div>
            <div style="position:relative;z-index:1;margin-top:20px">
                <div id="apiSupport" class="badge-api no">
                    <i class="mdi mdi-loading mdi-spin"></i> Memeriksa NFC...
                </div>
            </div>
        </div>

        {{-- Result Card --}}
        <div class="result-card" id="resultCard" style="display:none">
            <div id="resultSuccess" style="display:none;text-align:center">
                <div style="margin-bottom:16px">
                    <i class="mdi mdi-check-circle" style="font-size:48px;color:#22c55e"></i>
                </div>
                <div class="success-name mb-2" id="resUserName">-</div>
                <div class="success-time" id="resTime">--:--:--</div>
                <div style="font-size:12px;color:#9ca3af;margin-top:8px" id="resDate">-</div>
            </div>
            <div id="resultRegistered" style="display:none;text-align:center">
                <div style="margin-bottom:16px">
                    <i class="mdi mdi-clock-alert" style="font-size:48px;color:#f59e0b"></i>
                </div>
                <div style="font-size:16px;font-weight:700;color:#92400e" id="resAlreadyName">-</div>
                <div style="font-size:14px;color:#b45309;margin-top:8px" id="resAlreadyTime">Sudah absen: --:--:--</div>
            </div>
            <div id="resultError" style="display:none;text-align:center">
                <div style="margin-bottom:16px">
                    <i class="mdi mdi-alert-circle" style="font-size:48px;color:#ef4444"></i>
                </div>
                <div style="font-size:16px;font-weight:700;color:#991b1b">UID Tidak Terdaftar</div>
                <div style="font-size:13px;color:#dc2626;margin-top:8px">NFC UID ini belum didaftarkan ke user manapun.</div>
            </div>
        </div>

        {{-- Input Manual --}}
        <div class="info-card mt-3">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:700;margin-bottom:12px">
                Input Manual (Tanpa NFC)
            </div>
            <div class="input-group">
                <input type="text" id="manualUid" class="form-control" placeholder="Contoh: C3:F6:46:18" style="border-radius:50px 0 0 50px">
                <button type="button" id="btnManual" class="btn btn-primary" style="border-radius:0 50px 50px 0;padding:0 20px">
                    Absen
                </button>
            </div>
        </div>
    </div>

    {{-- RIGHT: Stats & Info --}}
    <div class="col-lg-7">
        {{-- Stats Today --}}
        <div class="row g-3 mb-4">
            <div class="col-4">
                <div class="stat-box">
                    <div class="stat-number">{{ \App\Models\User::whereNotNull('nfc_uid')->count() }}</div>
                    <div class="stat-label">User NFC</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-box">
                    <div class="stat-number green">{{ \App\Models\Attendance::whereDate('scanned_at', \Carbon\Carbon::today('Asia/Jakarta'))->count() }}</div>
                    <div class="stat-label">Hadir Hari Ini</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-box">
                    <div class="stat-number red">{{ max(0, \App\Models\User::whereNotNull('nfc_uid')->count() - \App\Models\Attendance::whereDate('scanned_at', \Carbon\Carbon::today('Asia/Jakarta'))->count()) }}</div>
                    <div class="stat-label">Belum Absen</div>
                </div>
            </div>
        </div>

        {{-- Info --}}
        <div class="info-card mb-3">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:700;margin-bottom:12px">
                Informasi Scan
            </div>
            <div class="info-row">
                <span class="info-label">NFC UID</span>
                <span class="info-value" id="infoUid">-</span>
            </div>
            <div class="info-row">
                <span class="info-label">Waktu Scan</span>
                <span class="info-value" id="infoTime">-</span>
            </div>
            <div class="info-row">
                <span class="info-label">Status</span>
                <span class="info-value" id="infoStatus">-</span>
            </div>
        </div>

        {{-- Quick Links --}}
        <div class="row g-2 mb-3">
            <div class="col-6">
                <a href="{{ route('nfc.today') }}" class="btn btn-outline-primary w-100" style="border-radius:12px;padding:12px">
                    <i class="mdi mdi-calendar-today me-1"></i> Hadir Hari Ini
                </a>
            </div>
            <div class="col-6">
                <a href="{{ route('nfc.history') }}" class="btn btn-outline-primary w-100" style="border-radius:12px;padding:12px">
                    <i class="mdi mdi-history me-1"></i> Riwayat Absensi
                </a>
            </div>
        </div>

        {{-- Petunjuk --}}
        <div class="info-card">
            <div style="font-size:11px;text-transform:uppercase;letter-spacing:.08em;color:#9ca3af;font-weight:700;margin-bottom:12px">
                Petunjuk Penggunaan
            </div>
            <div style="font-size:13px;color:#374151">
                <div style="display:flex;gap:10px;margin-bottom:10px">
                    <div style="width:28px;height:28px;border-radius:50%;background:#667eea;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0">1</div>
                    <div>Klik <strong>"Mulai Scan"</strong> pada tombol di atas</div>
                </div>
                <div style="display:flex;gap:10px;margin-bottom:10px">
                    <div style="width:28px;height:28px;border-radius:50%;background:#667eea;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0">2</div>
                    <div>Tempelkan kartu NFC ke bagian <strong>belakang HP</strong> (< 4 cm)</div>
                </div>
                <div style="display:flex;gap:10px;margin-bottom:10px">
                    <div style="width:28px;height:28px;border-radius:50%;background:#667eea;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0">3</div>
                    <div>Tunggu hingga muncul notifikasi <strong>Berhasil / Sudah Absen / UID Tidak Terdaftar</strong></div>
                </div>
                <div style="display:flex;gap:10px">
                    <div style="width:28px;height:28px;border-radius:50%;background:#667eea;color:#fff;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:13px;flex-shrink:0">4</div>
                    <div>Setiap user hanya bisa absen <strong>1x per hari</strong></div>
                </div>
            </div>
            <div class="alert alert-light mt-3 mb-0" style="font-size:12px">
                <i class="mdi mdi-information-outline me-1"></i>
                Web NFC hanya berjalan di <strong>Android Chrome v89+</strong>. iOS tidak didukung.
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
    var btnScan  = document.getElementById('btnScanNfc');
    var btnStop  = document.getElementById('btnStopNfc');
    var nfcRing  = document.getElementById('nfcRing');
    var nfcIcon  = document.getElementById('nfcIcon');
    var status   = document.getElementById('scanStatus');
    var resultCard = document.getElementById('resultCard');
    var resultSuccess = document.getElementById('resultSuccess');
    var resultRegistered = document.getElementById('resultRegistered');
    var resultError = document.getElementById('resultError');
    var apiSupport = document.getElementById('apiSupport');
    var infoUid = document.getElementById('infoUid');
    var infoTime = document.getElementById('infoTime');
    var infoStatus = document.getElementById('infoStatus');
    var debugCard = document.getElementById('debugCard');
    var debugContent = document.getElementById('debugContent');

    var ndef = null;
    var isScanning = false;

    // Check NFC support
    if ('NDEFReader' in window) {
        apiSupport.className = 'badge-api ok';
        apiSupport.innerHTML = '<i class="mdi mdi-check-circle"></i> NFC Tersedia';
    } else {
        apiSupport.className = 'badge-api no';
        apiSupport.innerHTML = '<i class="mdi mdi-close-circle"></i> NFC Tidak Tersedia';
        btnScan.disabled = true;
        btnScan.innerHTML = '<i class="mdi mdi-close-circle me-2"></i> NFC Tidak Tersedia';
    }

    function showAlert(type, msg){
        var area = document.getElementById('alertArea');
        var icon = type==='success'?'check-circle':type==='danger'?'alert-circle':type==='warning'?'alert':'info';
        area.innerHTML = '<div class="alert alert-'+type+' alert-dismissible fade show d-flex align-items-center gap-2"><i class="mdi mdi-'+icon+'"></i> '+msg+'<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>';
    }

    function resetUI(){
        nfcRing.className = 'nfc-ring';
        resultCard.style.display = 'none';
        resultSuccess.style.display = 'none';
        resultRegistered.style.display = 'none';
        resultError.style.display = 'none';
    }

    function showSuccess(uid, name, time, date){
        resetUI();
        nfcRing.classList.add('success');
        nfcIcon.className = 'mdi mdi-check';
        resultCard.style.display = 'block';
        resultCard.className = 'result-card success';
        resultSuccess.style.display = 'block';
        document.getElementById('resUserName').textContent = name;
        document.getElementById('resTime').textContent = time;
        document.getElementById('resDate').textContent = date;
        infoUid.textContent = uid;
        infoTime.textContent = time;
        infoStatus.textContent = 'Berhasil';
        infoStatus.style.color = '#22c55e';
        showAlert('success', 'Absensi berhasil! Selamat, ' + name + '.');
    }

    function showAlready(uid, name, time){
        resetUI();
        nfcRing.classList.add('registered');
        nfcIcon.className = 'mdi mdi-clock-outline';
        resultCard.style.display = 'block';
        resultCard.className = 'result-card registered';
        resultRegistered.style.display = 'block';
        document.getElementById('resAlreadyName').textContent = name;
        document.getElementById('resAlreadyTime').textContent = 'Sudah absen: ' + time;
        infoUid.textContent = uid;
        infoTime.textContent = time;
        infoStatus.textContent = 'Sudah Absen';
        infoStatus.style.color = '#f59e0b';
        showAlert('warning', name + ' sudah absen hari ini.');
    }

    function showNotFound(uid){
        resetUI();
        nfcRing.classList.add('error');
        nfcIcon.className = 'mdi mdi-alert-circle';
        resultCard.style.display = 'block';
        resultCard.className = 'result-card error';
        resultError.style.display = 'block';
        infoUid.textContent = uid;
        infoTime.textContent = '-';
        infoStatus.textContent = 'Tidak Terdaftar';
        infoStatus.style.color = '#ef4444';
        showAlert('danger', 'NFC UID tidak terdaftar. Hubungi admin untuk mendaftarkan kartu.');
    }

    function startScan(){
        if (!('NDEFReader' in window)) return;
        if (isScanning) return;

        ndef = new NDEFReader();
        btnScan.style.display = 'none';
        btnStop.style.display = 'inline-flex';
        nfcRing.classList.add('scanning');
        status.textContent = 'Mendeteksi NFC...';
        isScanning = true;
        resetUI();

        ndef.scan().then(function(){
            ndef.onreadingerror = function(){
                status.textContent = 'Kartu tidak terbaca. Coba tempelkan lagi.';
                nfcRing.classList.remove('scanning');
                showAlert('danger', 'Kartu NFC tidak terbaca. Pastikan kartu proprietary (seperti KRL/e-money) tidak bisa digunakan. Coba NFC tag kosong.');
            };
            ndef.onreading = function(event){
                var nfcUid = null;

                if (event.serialNumber && event.serialNumber.trim()) {
                    nfcUid = event.serialNumber.trim().toUpperCase();
                }

                if (!nfcUid || nfcUid === 'UNKNOWN') {
                    if (event.message && event.message.records) {
                        for (var i = 0; i < event.message.records.length; i++) {
                            var rec = event.message.records[i];
                            if (rec.recordType === 'text' || rec.recordType === 'url') {
                                try {
                                    var text = new TextDecoder().decode(rec.data).trim();
                                    if (text) { nfcUid = text; break; }
                                } catch(e){}
                            }
                        }
                    }
                }

                if (!nfcUid || nfcUid === 'UNKNOWN') {
                    nfcUid = 'NFC-' + Date.now();
                }

                nfcRing.classList.remove('scanning');
                stopScan();

                // Debug info
                debugContent.textContent = 'serialNumber: ' + (event.serialNumber || 'null') + '\nUID used: ' + nfcUid;
                debugCard.style.display = 'block';

                status.textContent = 'NFC terdeteksi! Memproses...';
                sendToServer(nfcUid);
            };
        }).catch(function(err){
            btnScan.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            nfcRing.classList.remove('scanning');
            status.textContent = 'Gagal memulai NFC.';
            isScanning = false;
            var msg = err.message || 'Tidak diketahui';
            if (msg.includes('NotAllowedError')) {
                showAlert('danger', 'Izin NFC ditolak. Aktifkan NFC di pengaturan HP.');
            } else {
                showAlert('danger', 'Gagal NFC: ' + msg);
            }
        });
    }

    function stopScan(){
        if (ndef) { ndef.onreading = null; ndef.onreadingerror = null; }
        ndef = null;
        isScanning = false;
        btnScan.style.display = 'inline-flex';
        btnStop.style.display = 'none';
        nfcRing.classList.remove('scanning');
        status.textContent = 'Scan selesai.';
    }

    function sendToServer(nfcUid){
        status.textContent = 'Mengirim ke server...';
        var csrfToken = document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').content : '{{ csrf_token() }}';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', '/nfc/scan', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', csrfToken);
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.timeout = 15000;

        xhr.onreadystatechange = function(){
            if (xhr.readyState === 4) {
                btnScan.style.display = 'inline-flex';
                btnStop.style.display = 'none';
                isScanning = false;

                var now = new Date();
                var timeStr = now.toLocaleTimeString('id-ID', {hour:'2-digit',minute:'2-digit',second:'2-digit'});
                var dateStr = now.toLocaleDateString('id-ID', {day:'2-digit',month:'long',year:'numeric'});

                if (xhr.status === 0) {
                    status.textContent = 'Koneksi gagal.';
                    showAlert('danger', 'Gagal terhubung ke server. Pastikan HP terhubung ke internet dan ngrok aktif.');
                    return;
                }

                try {
                    var json = JSON.parse(xhr.responseText);
                    if (xhr.status === 200) {
                        if (json.status === true) {
                            var t = (json.scanned_at || '').split(' ');
                            showSuccess(nfcUid, json.user.name, t[1] || timeStr, t[0] || dateStr);
                        } else if (json.status === 'already') {
                            showAlready(nfcUid, json.user.name, json.user.scanned_at || timeStr);
                        } else {
                            showNotFound(nfcUid);
                        }
                    } else if (xhr.status === 404) {
                        showNotFound(nfcUid);
                    } else if (xhr.status === 419) {
                        showAlert('danger', 'Token expired. Refresh halaman ini.');
                    } else {
                        showAlert('danger', 'Server error (' + xhr.status + '): ' + (json.message || 'Unknown'));
                    }
                } catch(e) {
                    status.textContent = 'Error parsing response.';
                    showAlert('danger', 'Server error (' + xhr.status + '). Response: ' + xhr.responseText.substring(0,100));
                }
            }
        };

        xhr.onerror = function(){
            btnScan.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            isScanning = false;
            status.textContent = 'Koneksi gagal.';
            showAlert('danger', 'Gagal terhubung ke server. Pastikan:\n1. Ngrok tunnel aktif\n2. Server Laravel berjalan\n3. HP terhubung ke internet');
        };

        xhr.ontimeout = function(){
            btnScan.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            isScanning = false;
            status.textContent = 'Timeout.';
            showAlert('danger', 'Koneksi timeout. Coba lagi.');
        };

        xhr.send(JSON.stringify({nfc_uid: nfcUid}));
    }

    // Event listeners
    btnScan.addEventListener('click', startScan);
    btnStop.addEventListener('click', stopScan);

    // Manual input
    document.getElementById('btnManual').addEventListener('click', function(){
        var uid = document.getElementById('manualUid').value.trim();
        if (!uid) { showAlert('danger', 'Masukkan NFC UID.'); return; }
        resetUI();
        nfcRing.classList.add('scanning');
        status.textContent = 'Memproses...';
        sendToServer(uid);
        document.getElementById('manualUid').value = '';
    });

    document.getElementById('manualUid').addEventListener('keypress', function(e){
        if (e.key === 'Enter') document.getElementById('btnManual').click();
    });
})();
</script>
@endpush