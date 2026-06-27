@extends('layouts.apps')
@section('content')
<style>
    .nfc-card {
        background: #fff;
        border-radius: 12px;
        padding: 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        border: 1px solid #e5e7eb;
    }
    .nfc-icon-scan {
        width: 120px;
        height: 120px;
        border: 4px dashed #4b49ac;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        transition: all 0.3s ease;
    }
    .nfc-icon-scan.scanning {
        border-color: #2dc76d;
        background: #f0fff4;
        animation: pulse-scan 1.5s infinite;
    }
    .nfc-icon-scan.success {
        border-color: #2dc76d;
        background: #f0fff4;
    }
    .nfc-icon-scan.danger {
        border-color: #dc3545;
        background: #fff5f5;
    }
    .nfc-icon-scan.registered {
        border-color: #f59e0b;
        background: #fffbeb;
    }
    @keyframes pulse-scan {
        0%, 100% { transform: scale(1); }
        50% { transform: scale(1.08); }
    }
    .scan-btn {
        background: #4b49ac;
        color: #fff;
        border: none;
        padding: 14px 32px;
        border-radius: 8px;
        font-size: 16px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
    }
    .scan-btn:hover { background: #3a3780; }
    .scan-btn:disabled { background: #a0a0a0; cursor: not-allowed; }
    .scan-btn-danger { background: #dc3545; }
    .scan-btn-danger:hover { background: #c82333; }
    .result-box {
        background: #f8fafc;
        border: 1px solid #e5e7eb;
        border-radius: 8px;
        padding: 16px;
        margin-top: 16px;
    }
    .result-box.success { border-color: #2dc76d; background: #f0fff4; }
    .result-box.danger { border-color: #dc3545; background: #fff5f5; }
    .result-box.registered { border-color: #f59e0b; background: #fffbeb; }
    .result-box.waiting { border-color: #64748b; background: #f8fafc; }
    .info-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 12px;
    }
    .info-item {
        background: rgba(255,255,255,0.7);
        border: 1px solid rgba(255,255,255,0.3);
        border-radius: 8px;
        padding: 10px 12px;
    }
    .info-item label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        font-weight: 600;
        margin-bottom: 2px;
    }
    .info-item .value {
        font-size: 15px;
        font-weight: 700;
        color: #1f2937;
    }
    .badge-support { background: #dcfce7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    .badge-unsupport { background: #fee2e2; color: #991b1b; padding: 4px 12px; border-radius: 20px; font-size: 13px; font-weight: 600; }
    .section-label { font-size: 11px; text-transform: uppercase; letter-spacing: .05em; color: #64748b; font-weight: 700; margin-bottom: 10px; }
    .user-success-name { font-size: 24px; font-weight: 800; color: #166534; }
    .user-success-time { font-size: 32px; font-weight: 800; color: #2dc76d; }
    .user-success-date { font-size: 14px; color: #64748b; }
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

<div class="row g-4">

    {{-- LEFT: Scanner --}}
    <div class="col-lg-5">
        <div class="nfc-card text-center">
            <p class="section-label">Scan NFC Card</p>

            <div id="nfcIconArea" class="nfc-icon-scan">
                <i class="mdi mdi-nfc" style="font-size:3rem;color:#4b49ac"></i>
            </div>

            <p id="scanStatus" class="text-muted mb-3">Tekan tombol untuk memulai scan NFC</p>

            <div class="d-flex justify-content-center gap-2 flex-wrap mb-3">
                <button type="button" id="btnScanNfc" class="scan-btn">
                    <i class="mdi mdi-nfc-search-variant me-1"></i> Mulai Scan NFC
                </button>
                <button type="button" id="btnStopNfc" class="scan-btn scan-btn-danger" style="display:none">
                    <i class="mdi mdi-close me-1"></i> Stop
                </button>
            </div>

            <div id="nfcSupportInfo" class="mb-2"></div>

            <hr class="my-3">

            <p class="section-label">Atau Input Manual (Tanpa NFC)</p>
            <div class="d-flex gap-2 justify-content-center">
                <input type="text" id="manualNfcUid" class="form-control" placeholder="Contoh: 04:AB:CD:12:34" style="max-width:220px">
                <button type="button" id="btnManualScan" class="scan-btn" style="padding:10px 20px;font-size:14px">
                    <i class="mdi mdi-keyboard-return me-1"></i> Submit
                </button>
            </div>

            <div id="nfcResult" class="result-box waiting" style="display:none">
                <p class="section-label mb-2">Hasil</p>
                <div id="resultContent">
                    <div class="info-grid">
                        <div class="info-item">
                            <label>NFC UID</label>
                            <div class="value" id="nfcSerial">-</div>
                        </div>
                        <div class="info-item">
                            <label>Status</label>
                            <div class="value" id="nfcStatus">-</div>
                        </div>
                        <div class="info-item" style="grid-column:1/-1;display:none" id="nfcUserItem">
                            <label>Nama User</label>
                            <div class="value user-success-name" id="nfcUserName">-</div>
                        </div>
                        <div class="info-item" style="grid-column:1/-1;display:none" id="nfcTimeItem">
                            <label>Waktu Absen</label>
                            <div class="value user-success-time" id="nfcTime">-</div>
                            <div class="user-success-date" id="nfcDate">-</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- RIGHT: Info & Today's Stats --}}
    <div class="col-lg-7">
        <div class="nfc-card mb-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="section-label mb-0">Statistik Hari Ini</p>
                <a href="{{ route('nfc.today') }}" class="btn btn-sm btn-outline-primary">
                    <i class="mdi mdi-arrow-right"></i> Detail
                </a>
            </div>
            <div class="info-grid">
                <div class="info-item text-center">
                    <label>Total User NFC</label>
                    <div class="value" style="font-size:28px" id="totalNfcUsers">{{ \App\Models\User::whereNotNull('nfc_uid')->count() }}</div>
                </div>
                <div class="info-item text-center">
                    <label>Sudah Absen Hari Ini</label>
                    <div class="value" style="font-size:28px;color:#2dc76d" id="todayCount">{{ \App\Models\Attendance::whereDate('scanned_at', \Carbon\Carbon::today('Asia/Jakarta'))->count() }}</div>
                </div>
            </div>
        </div>

        <div class="nfc-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <p class="section-label mb-0">Petunjuk Penggunaan</p>
            </div>

            <div class="mb-3">
                <h6 style="font-size:14px;font-weight:700;color:#1f2937" class="mb-2">
                    <i class="mdi mdi-cellphone text-primary me-1"></i> Syarat Perangkat
                </h6>
                <ul style="font-size:13px;color:#4b5563;padding-left:18px">
                    <li>Hanya berjalan di <strong>Android Chrome v89+</strong></li>
                    <li>Perangkat harus memiliki <strong>chip NFC</strong></li>
                    <li>Wajib menggunakan <strong>HTTPS</strong> atau <strong>localhost</strong></li>
                    <li><strong>iOS Safari tidak mendukung</strong> Web NFC API</li>
                </ul>
            </div>

            <div class="mb-3">
                <h6 style="font-size:14px;font-weight:700;color:#1f2937" class="mb-2">
                    <i class="mdi mdi-format-list-numbered text-primary me-1"></i> Cara Pakai
                </h6>
                <ol style="font-size:13px;color:#4b5563;padding-left:18px">
                    <li>Klik tombol <strong>"Mulai Scan NFC"</strong></li>
                    <li>Dekatkan NFC card ke bagian <strong>belakang HP</strong> (< 4 cm)</li>
                    <li>Hasil absensi akan muncul secara otomatis</li>
                    <li><strong>1 user hanya bisa 1x absen/hari</strong></li>
                </ol>
            </div>

            <div class="alert alert-light border">
                <small class="text-muted">
                    <i class="mdi mdi-information-outline me-1"></i>
                    Web NFC API memungkinkan halaman web membaca NFC tag langsung dari browser.
                    <strong>Setiap NFC UID hanya bisa absen 1x per hari.</strong>
                </small>
            </div>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
(function(){
    const btnScan  = document.getElementById('btnScanNfc');
    const btnStop  = document.getElementById('btnStopNfc');
    const status   = document.getElementById('scanStatus');
    const iconArea = document.getElementById('nfcIconArea');
    const resultBox= document.getElementById('nfcResult');
    const serialEl = document.getElementById('nfcSerial');
    const statusEl = document.getElementById('nfcStatus');
    const userItem = document.getElementById('nfcUserItem');
    const userName = document.getElementById('nfcUserName');
    const timeItem = document.getElementById('nfcTimeItem');
    const timeEl   = document.getElementById('nfcTime');
    const dateEl   = document.getElementById('nfcDate');
    const nfcInfo  = document.getElementById('nfcSupportInfo');

    let ndef = null;
    let isScanning = false;

    function showAlert(type, msg){
        var area = document.getElementById('alertArea');
        var icon = type==='success'?'check-circle':type==='danger'?'alert-circle':type==='warning'?'alert':'info';
        area.innerHTML = '<div class="alert alert-'+type+' d-flex align-items-center gap-2"><i class="mdi mdi-'+icon+'"></i> '+msg+'</div>';
        setTimeout(function(){area.innerHTML='';}, 8000);
    }

    function checkSupport(){
        if (!('NDEFReader' in window)) {
            btnScan.disabled = true;
            nfcInfo.innerHTML = '<span class="badge-unsupport"><i class="mdi mdi-close-circle me-1"></i> Web NFC API tidak tersedia</span>';
            showAlert('danger', 'Browser tidak mendukung Web NFC. Gunakan input manual di bawah atau buka di Android Chrome.');
            return false;
        }
        nfcInfo.innerHTML = '<span class="badge-support"><i class="mdi mdi-check-circle me-1"></i> Web NFC API tersedia</span>';
        return true;
    }

    function showResult(type, serial, userNameVal, timeVal, dateVal){
        serialEl.textContent = serial;
        resultBox.style.display = 'block';

        iconArea.className = 'nfc-icon-scan';

        if (type === 'success'){
            statusEl.textContent = '✅ Absensi Berhasil';
            statusEl.style.color = '#166534';
            resultBox.className = 'result-box success';
            iconArea.classList.add('success');
            userItem.style.display = 'block';
            userName.textContent = userNameVal;
            timeItem.style.display = 'block';
            timeEl.textContent = timeVal;
            dateEl.textContent = dateVal;
            showAlert('success', 'Absensi berhasil! Selamat, ' + userNameVal);
        } else if (type === 'already') {
            statusEl.textContent = '⚠️ Sudah Absen';
            statusEl.style.color = '#92400e';
            resultBox.className = 'result-box registered';
            iconArea.classList.add('registered');
            userItem.style.display = 'block';
            userName.textContent = userNameVal;
            timeItem.style.display = 'block';
            timeEl.textContent = 'Sudah absen: ' + timeVal;
            timeEl.style.fontSize = '16px';
            dateEl.textContent = '';
            showAlert('warning', userNameVal + ' sudah absen hari ini.');
        } else {
            statusEl.textContent = '❌ UID Tidak Terdaftar';
            statusEl.style.color = '#991b1b';
            resultBox.className = 'result-box danger';
            iconArea.classList.add('danger');
            userItem.style.display = 'none';
            timeItem.style.display = 'none';
            showAlert('danger', 'NFC UID tidak terdaftar di sistem.');
        }
    }

    function submitScan(nfcUid){
        var xhr = new XMLHttpRequest();
        xhr.open('POST', "{{ route('nfc.scan') }}", true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');
        xhr.setRequestHeader('Accept', 'application/json');
        xhr.onload = function(){
            try {
                var json = JSON.parse(xhr.responseText);
                if (json.status === true){
                    showResult('success', nfcUid, json.user.name, json.scanned_at.split(' ')[1], json.scanned_at.split(' ')[0]);
                } else if (json.status === 'already'){
                    showResult('already', nfcUid, json.user.name, json.user.scanned_at, '');
                } else {
                    showResult('danger', nfcUid, null, null, null);
                }
            } catch(e){
                showAlert('danger', 'Gagal memproses response server.');
                iconArea.classList.remove('scanning');
            }
            btnScan.disabled = false;
            btnScan.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            isScanning = false;
        };
        xhr.onerror = function(){
            showAlert('danger', 'Gagal terhubung ke server.');
            btnScan.disabled = false;
            btnScan.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            isScanning = false;
            iconArea.classList.remove('scanning');
        };
        xhr.send(JSON.stringify({nfc_uid: nfcUid}));
    }

    function startScan(){
        if (!checkSupport()) return;
        if (isScanning) return;

        ndef = new NDEFReader();
        btnScan.disabled = true;
        btnScan.style.display = 'none';
        btnStop.style.display = 'inline-flex';
        status.textContent = 'Dekatkan NFC card ke belakang HP (< 4 cm)...';
        status.className = 'text-primary mb-3';
        iconArea.classList.add('scanning');
        iconArea.classList.remove('success','danger','registered');
        resultBox.style.display = 'none';
        isScanning = true;

        ndef.scan().then(function(){
            ndef.onreadingerror = function(){
                showAlert('danger', 'Gagal membaca NFC. Coba dekatkan card lagi.');
            };

            ndef.onreading = function(event){
                var nfcUid = event.serialNumber || 'unknown';

                // Try to read text record
                if (event.message && event.message.records){
                    var records = event.message.records;
                    for (var i = 0; i < records.length; i++){
                        if (records[i].recordType === 'text'){
                            try {
                                var decoder = new TextDecoder();
                                var text = decoder.decode(records[i].data);
                                if (text.trim()) nfcUid = text.trim();
                            } catch(e){}
                            break;
                        }
                    }
                }

                iconArea.classList.remove('scanning');
                stopScan();

                showAlert('info', 'NFC terdeteksi! UID: ' + nfcUid + ' — Memproses...');
                submitScan(nfcUid);
            };
        }).catch(function(err){
            btnScan.disabled = false;
            btnScan.style.display = 'inline-flex';
            btnStop.style.display = 'none';
            status.textContent = 'Tekan tombol untuk memulai scan';
            status.className = 'text-muted mb-3';
            iconArea.classList.remove('scanning');
            isScanning = false;
            showAlert('danger', 'Gagal memulai NFC: ' + err.message);
        });
    }

    function stopScan(){
        if (ndef) {
            ndef.onreading = null;
            ndef.onreadingerror = null;
        }
        ndef = null;
        isScanning = false;
        btnScan.disabled = false;
        btnScan.style.display = 'inline-flex';
        btnStop.style.display = 'none';
        status.textContent = 'Scan selesai.';
        status.className = 'text-muted mb-3';
        iconArea.classList.remove('scanning');
    }

    btnScan.addEventListener('click', startScan);
    btnStop.addEventListener('click', stopScan);

    // Manual input fallback
    document.getElementById('btnManualScan').addEventListener('click', function(){
        var uid = document.getElementById('manualNfcUid').value.trim();
        if (!uid){
            showAlert('danger', 'Masukkan NFC UID terlebih dahulu.');
            return;
        }
        submitScan(uid);
    });

    document.getElementById('manualNfcUid').addEventListener('keypress', function(e){
        if (e.key === 'Enter'){
            document.getElementById('btnManualScan').click();
        }
    });

    checkSupport();
})();
</script>
@endpush
