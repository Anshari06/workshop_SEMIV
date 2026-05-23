@extends('layouts.payment')

@section('title', 'Scanner QR Vendor')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-qrcode-scan"></i>
            </span>
            Scan QR Customer
        </h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4">
                <div class="col-lg-4">
                    <div class="card h-100 border rounded-4 bg-light">
                        <div class="card-body text-center p-4 d-flex flex-column justify-content-center gap-3">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                style="width: 80px; height: 80px;">
                                <i class="mdi mdi-qrcode-scan" style="font-size: 38px;"></i>
                            </div>

                            <div>
                                <h4 class="fw-semibold mb-2">Scanner Vendor</h4>
                                <p class="text-muted mb-0">
                                    Arahkan kamera ke QR Code customer untuk melihat menu pesanan dan status pembayaran.
                                </p>
                            </div>

                            <div class="alert alert-info mb-0 py-2 small">
                                Setelah QR terbaca, scanner akan berhenti dan hasil akan ditampilkan otomatis.
                            </div>

                            <div class="d-flex justify-content-center gap-2 flex-wrap">
                                <button type="button" class="btn btn-primary" id="startCameraBtn">
                                    <i class="mdi mdi-camera me-1"></i> Start Kamera
                                </button>
                                <button type="button" class="btn btn-outline-secondary" id="resetCameraBtn">
                                    Reset
                                </button>
                                <button type="button" class="btn btn-outline-danger" id="stopCameraBtn" disabled>
                                    Stop Kamera
                                </button>
                            </div>

                            <div id="qr-reader" class="border rounded-3 bg-white p-3 mt-2" style="min-height: 280px;"></div>

                            <div class="small text-muted mt-1" id="scannerStateText">
                                Status scanner: belum aktif.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1">Hasil Pembacaan QR</h4>
                            <div class="text-muted">Menampilkan menu yang dipesan customer dan status pembayarannya.</div>
                        </div>
                        <span class="badge text-bg-success px-3 py-2" id="resultBadge">Ready</span>
                    </div>

                    <div class="alert alert-success d-inline-flex align-items-center gap-2 mb-3" id="resultAlert">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span id="resultAlertText">Siap scan QR Code customer.</span>
                    </div>

                    <div class="card border rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">ID Pesanan</div>
                                        <div class="fw-bold fs-5 text-dark" id="orderId">-</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">Nama Customer</div>
                                        <div class="fw-bold fs-5 text-dark" id="customerName">-</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">Status Bayar</div>
                                        <span class="badge text-bg-secondary px-3 py-2" id="paymentStatus">Belum dibaca</span>
                                    </div>
                                </div>
                            </div>

                            <hr class="my-4">

                            <div class="table-responsive">
                                <table class="table align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Menu</th>
                                            <th class="text-center" style="width: 120px;">Qty</th>
                                            <th class="text-end" style="width: 180px;">Harga</th>
                                            <th class="text-end" style="width: 180px;">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody id="orderItemsBody">
                                        <tr>
                                            <td colspan="4" class="text-center text-muted py-4">
                                                Hasil pesanan akan muncul di sini setelah QR terbaca.
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <div class="card border rounded-4">
                        <div class="card-body p-4">
                            <h5 class="mb-3">Ringkasan Pesanan</h5>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <div class="text-muted small text-uppercase fw-semibold">Total Item</div>
                                        <div class="fw-bold fs-4 text-dark" id="summaryTotalItem">0</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <div class="text-muted small text-uppercase fw-semibold">Total Bayar</div>
                                        <div class="fw-bold fs-4 text-dark" id="summaryTotal">Rp 0</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <div class="text-muted small text-uppercase fw-semibold">Metode Bayar</div>
                                        <div class="fw-bold fs-4 text-dark" id="summaryMethod">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        (function() {
            const startBtn = document.getElementById('startCameraBtn');
            const stopBtn = document.getElementById('stopCameraBtn');
            const resetBtn = document.getElementById('resetCameraBtn');
            const scannerStateText = document.getElementById('scannerStateText');
            const resultAlertText = document.getElementById('resultAlertText');
            const resultAlert = document.getElementById('resultAlert');
            const resultBadge = document.getElementById('resultBadge');

            const orderIdEl = document.getElementById('orderId');
            const customerNameEl = document.getElementById('customerName');
            const paymentStatusEl = document.getElementById('paymentStatus');
            const orderItemsBody = document.getElementById('orderItemsBody');
            const summaryTotalItem = document.getElementById('summaryTotalItem');
            const summaryTotal = document.getElementById('summaryTotal');
            const summaryMethod = document.getElementById('summaryMethod');

            const currency = (value) => new Intl.NumberFormat('id-ID').format(value || 0);

            const qrReaderId = 'qr-reader';
            let qrScanner = null;
            let isScanning = false;
            let hasResultLock = false;

            function getScannerApi() {
                if (typeof window.Html5Qrcode === 'undefined') {
                    return null;
                }

                return {
                    Html5Qrcode: window.Html5Qrcode,
                    Html5QrcodeScannerState: window.Html5QrcodeScannerState,
                };
            }

            async function loadHtml5QrcodeLibrary() {
                if (typeof window.Html5Qrcode !== 'undefined') {
                    return;
                }

                const urls = [
                    'https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js',
                    'https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/html5-qrcode.min.js'
                ];

                for (const url of urls) {
                    try {
                        await new Promise((resolve, reject) => {
                            const script = document.createElement('script');
                            script.src = url;
                            script.async = true;
                            script.onload = () => resolve(true);
                            script.onerror = () => reject(new Error('Load failed: ' + url));
                            document.head.appendChild(script);
                        });

                        if (typeof window.Html5Qrcode !== 'undefined') {
                            return;
                        }
                    } catch (err) {
                        console.warn(err);
                    }
                }

                throw new Error('Library html5-qrcode gagal dimuat dari CDN.');
            }

            function beepShort() {
                try {
                    const context = new(window.AudioContext || window.webkitAudioContext)();
                    const oscillator = context.createOscillator();
                    const gainNode = context.createGain();

                    oscillator.type = 'sine';
                    oscillator.frequency.setValueAtTime(900, context.currentTime);
                    gainNode.gain.setValueAtTime(0.08, context.currentTime);

                    oscillator.connect(gainNode);
                    gainNode.connect(context.destination);

                    oscillator.start();
                    oscillator.stop(context.currentTime + 0.12);
                } catch (err) {
                    console.warn('Beep tidak tersedia di browser ini.', err);
                }
            }

            async function stopScanner() {
                if (!qrScanner || !isScanning) {
                    return;
                }

                try {
                    await qrScanner.stop();

                    const scannerApi = getScannerApi();
                    if (scannerApi?.Html5QrcodeScannerState &&
                        qrScanner.getState &&
                        qrScanner.getState() !== scannerApi.Html5QrcodeScannerState.NOT_STARTED) {
                        await qrScanner.clear();
                    }
                } catch (error) {
                    console.error('Gagal stop scanner:', error);
                } finally {
                    isScanning = false;
                    stopBtn.disabled = true;
                    startBtn.disabled = false;
                }
            }

            function setAlert(type, message) {
                resultAlert.className = 'alert d-inline-flex align-items-center gap-2 mb-3 alert-' + type;
                resultAlertText.textContent = message;
            }

            function extractOrderId(decodedText) {
                const text = String(decodedText || '').trim();
                if (/^\d+$/.test(text)) {
                    return text;
                }

                const legacy = text.match(/ORDER:(\d+)/i);
                if (legacy && legacy[1]) {
                    return legacy[1];
                }

                return null;
            }

            function renderOrder(order) {
                orderIdEl.textContent = '#' + order.kode;
                customerNameEl.textContent = order.customer_name || '-';
                paymentStatusEl.textContent = order.status_text || '-';
                paymentStatusEl.className = 'badge text-bg-' + (order.status_class || 'secondary') + ' px-3 py-2';

                summaryTotalItem.textContent = String(order.total_item || 0);
                summaryTotal.textContent = 'Rp ' + currency(order.total || 0);
                summaryMethod.textContent = order.metode_pembayaran || '-';

                const rows = (order.items || []).map((item) => {
                    return '<tr>' +
                        '<td>' + (item.menu || '-') + '</td>' +
                        '<td class="text-center">' + (item.qty || 0) + '</td>' +
                        '<td class="text-end">Rp ' + currency(item.harga || 0) + '</td>' +
                        '<td class="text-end">Rp ' + currency(item.subtotal || 0) + '</td>' +
                        '</tr>';
                });

                orderItemsBody.innerHTML = rows.join('');
            }

            async function fetchOrderAndRender(orderId) {
                const response = await fetch(`{{ route('vendor.scanner.order', ['pesananId' => '__ID__']) }}`.replace('__ID__', encodeURIComponent(orderId)), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) {
                    const payload = await response.json().catch(() => ({}));
                    throw new Error(payload.message || 'Pesanan tidak ditemukan untuk vendor ini.');
                }

                const payload = await response.json();
                renderOrder(payload.order);
            }

            async function onQrDetected(decodedText) {
                if (hasResultLock) {
                    return;
                }

                hasResultLock = true;
                const orderId = extractOrderId(decodedText);

                if (!orderId) {
                    setAlert('danger', 'QR tidak valid. QR harus berisi id pesanan.');
                    scannerStateText.textContent = 'Status scanner: QR tidak valid.';
                    hasResultLock = false;
                    return;
                }

                beepShort();
                await stopScanner();

                try {
                    await fetchOrderAndRender(orderId);
                    setAlert('success', 'QR berhasil dibaca. Menu pesanan dan status bayar sudah ditampilkan.');
                    resultBadge.textContent = 'Scanned';
                    resultBadge.className = 'badge text-bg-success px-3 py-2';
                    scannerStateText.textContent = 'Status scanner: berhenti setelah QR berhasil dibaca.';
                } catch (error) {
                    setAlert('danger', error.message);
                    resultBadge.textContent = 'Error';
                    resultBadge.className = 'badge text-bg-danger px-3 py-2';
                    scannerStateText.textContent = 'Status scanner: QR terbaca tapi data pesanan gagal diambil.';
                    hasResultLock = false;
                }
            }

            async function startScanner() {
                if (isScanning) {
                    return;
                }

                if (!window.isSecureContext) {
                    setAlert('danger', 'Kamera butuh HTTPS atau localhost. Buka aplikasi via localhost/https.');
                    return;
                }

                if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                    setAlert('danger', 'Browser tidak mendukung akses kamera.');
                    return;
                }

                try {
                    await loadHtml5QrcodeLibrary();
                } catch (err) {
                    setAlert('danger', err.message);
                    return;
                }

                const scannerApi = getScannerApi();
                if (!scannerApi) {
                    setAlert('danger', 'Library html5-qrcode tidak tersedia.');
                    return;
                }

                const {
                    Html5Qrcode
                } = scannerApi;

                if (!qrScanner) {
                    qrScanner = new Html5Qrcode(qrReaderId);
                }

                try {
                    const cameras = await Html5Qrcode.getCameras();
                    if (!cameras || cameras.length === 0) {
                        setAlert('danger', 'Kamera tidak ditemukan.');
                        return;
                    }

                    const preferredCamera = cameras.find((cam) => /back|rear|environment/i.test(cam.label)) || cameras[0];

                    await qrScanner.start(
                        preferredCamera.id, {
                            fps: 10,
                            qrbox: {
                                width: 250,
                                height: 250
                            }
                        },
                        onQrDetected,
                        () => {}
                    );

                    isScanning = true;
                    startBtn.disabled = true;
                    stopBtn.disabled = false;
                    hasResultLock = false;
                    scannerStateText.textContent = 'Status scanner: kamera aktif, arahkan ke QR customer.';
                    setAlert('info', 'Scanner aktif. Silakan arahkan QR ke kamera.');
                    resultBadge.textContent = 'Scanning';
                    resultBadge.className = 'badge text-bg-primary px-3 py-2';
                } catch (error) {
                    console.error('Gagal mulai kamera:', error);
                    const message = error && error.message ? error.message : 'Gagal memulai kamera.';
                    setAlert('danger', 'Gagal memulai kamera: ' + message);
                }
            }

            function resetResultView() {
                orderIdEl.textContent = '-';
                customerNameEl.textContent = '-';
                paymentStatusEl.textContent = 'Belum dibaca';
                paymentStatusEl.className = 'badge text-bg-secondary px-3 py-2';
                orderItemsBody.innerHTML = `
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">
                            Hasil pesanan akan muncul di sini setelah QR terbaca.
                        </td>
                    </tr>`;
                summaryTotalItem.textContent = '0';
                summaryTotal.textContent = 'Rp 0';
                summaryMethod.textContent = '-';
                resultBadge.textContent = 'Ready';
                resultBadge.className = 'badge text-bg-success px-3 py-2';
                setAlert('success', 'Siap scan QR Code customer.');
                hasResultLock = false;
            }

            startBtn.addEventListener('click', startScanner);
            stopBtn.addEventListener('click', async () => {
                await stopScanner();
                scannerStateText.textContent = 'Status scanner: dihentikan manual.';
                setAlert('secondary', 'Scanner dihentikan.');
                resultBadge.textContent = 'Stopped';
                resultBadge.className = 'badge text-bg-secondary px-3 py-2';
            });
            resetBtn.addEventListener('click', () => {
                resetResultView();
                scannerStateText.textContent = 'Status scanner: siap digunakan kembali.';
            });

            resetResultView();
        })();
    </script>
@endsection
