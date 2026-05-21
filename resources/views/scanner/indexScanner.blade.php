@extends('layouts.apps')

@section('content')
    <style>
        .scanner-input {
            position: absolute;
            left: -9999px;
            width: 1px;
            height: 1px;
            opacity: 0;
            pointer-events: none;
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-camera"></i>
            </span> Data Scanner
        </h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-lg-5">
            <div class="row g-4 align-items-stretch">
                <div class="col-lg-4">
                    <div class="card h-100 border bg-light rounded-4" id="scannerArea" role="button" tabindex="0"
                        aria-label="Area scan barcode">
                        <div class="card-body text-center p-4 d-flex flex-column justify-content-center gap-3">
                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                style="width: 76px; height: 76px;">
                                <i class="mdi mdi-barcode-scan" style="font-size: 36px;"></i>
                            </div>
                            <div>
                                <h4 class="fw-semibold mb-2">Scanner Siap</h4>
                                <p class="text-muted mb-0">Arahkan scanner ke label barcode lalu tekan hasil scan akan
                                    muncul otomatis.</p>
                            </div>
                            <div class="alert alert-info mb-0 py-2 small">
                                Barcode yang cocok akan memunculkan data barang dari tabel <strong>barang</strong>.
                            </div>
                            <div class="d-flex flex-wrap justify-content-center gap-2">
                                <button type="button" class="btn btn-outline-primary" id="startCameraBtn">Start
                                    Kamera</button>
                                <button type="button" class="btn btn-outline-secondary" id="resetButton">Reset</button>
                                <button type="button" class="btn btn-outline-danger d-none" id="stopCameraBtn">Stop
                                    Kamera</button>
                            </div>
                            <div id="scanner-container" style="width:100%; height:300px;"></div>
                            <div id="html5qr-feedback" class="small text-muted mt-2 d-none">Kamera aktif, arahkan ke
                                barcode.</div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-1">Hasil Pembacaan</h4>
                            <div class="text-muted">Data diambil dari tabel barang berdasarkan ID barcode.</div>
                        </div>
                        <span class="badge text-bg-success px-3 py-2">Ready</span>
                    </div>

                    <div class="alert alert-success d-inline-flex align-items-center gap-2 mb-3" id="scannerStatus">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span id="scannerStatusText">Siap scan. Fokuskan kursor ke area scan lalu pindai barcode.</span>
                    </div>

                    <input type="text" id="barcodeInput" class="scanner-input" autocomplete="off" autocapitalize="off"
                        spellcheck="false" inputmode="none">
                    <input type="hidden" id="scannedValue" value="">

                    <div class="card border rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div id="emptyState" class="alert alert-light border mb-0">
                                Hasil scan akan muncul di sini setelah barcode berhasil terbaca.
                            </div>

                            <div id="resultState" class="d-none">
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <div class="text-muted small text-uppercase fw-semibold">ID Barang</div>
                                        <div class="fw-bold fs-5 text-dark" id="resultId">-</div>
                                    </div>
                                    <div class="col-md-5">
                                        <div class="text-muted small text-uppercase fw-semibold">Nama Barang</div>
                                        <div class="fw-bold fs-5 text-dark" id="resultName">-</div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="text-muted small text-uppercase fw-semibold">Harga</div>
                                        <div class="fw-bold fs-5 text-dark" id="resultPrice">-</div>
                                    </div>
                                </div>

                                <hr class="my-4">

                                <div class="p-3 rounded-3 bg-light border">
                                    <div class="text-muted small text-uppercase fw-semibold mb-1">Barcode terakhir</div>
                                    <div class="fs-5 fw-bold text-dark" id="resultBarcode">-</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h5 class="mb-0">Riwayat Singkat</h5>
                        <span class="badge text-bg-primary" id="scanCount">0 scan</span>
                    </div>

                    <div class="list-group rounded-3 border" id="historyList">
                        <div class="list-group-item text-secondary">Belum ada hasil scan.</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto"></audio>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        (function() {
            const startBtn = document.getElementById('startCameraBtn');
            const stopBtn = document.getElementById('stopCameraBtn');
            const resetBtn = document.getElementById('resetButton');
            const scannerArea = document.getElementById('scannerArea');
            const beepEl = document.getElementById('beep-sound');

            let running = false;
            let lastResult = null;

            const formatCurrency = (v) => new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(v);

            const showItem = (data) => {
                document.getElementById('resultId').textContent = data.id_barang;
                document.getElementById('resultName').textContent = data.nama_barang;
                document.getElementById('resultPrice').textContent = formatCurrency(data.harga);
                document.getElementById('resultBarcode').textContent = data.id_barang;
                document.getElementById('emptyState').classList.add('d-none');
                document.getElementById('resultState').classList.remove('d-none');
                document.getElementById('scannerStatusText').textContent = 'Barcode berhasil dibaca';
            };

            const startScanner = () => {
                if (running) return;
                if (typeof Quagga === 'undefined') {
                    alert('Library Quagga belum tersedia. Coba refresh.');
                    return;
                }

                // initialize Quagga into scannerArea
                Quagga.init({
                    inputStream: {
                        name: 'Live',
                        type: 'LiveStream',
                        target: document.querySelector('#scanner-container')
                        inputStream: {
                            name: 'Live',
                            type: 'LiveStream',
                            target: document.querySelector('#scanner-container'),
                            constraints: {
                                facingMode: "environment",
                                width: {
                                    ideal: 1280
                                },
                                height: {
                                    ideal: 720
                                },
                                focusMode: "continuous"
                            }
                            locator: {
                                patchSize: "large",
                                halfSample: false
                            },
                        },
                    },
                    decoder: {
                        readers: ['code_128_reader', 'ean_reader', 'ean_8_reader', 'code_39_reader']
                    },
                    locate: true,
                    numOfWorkers: navigator.hardwareConcurrency ? Math.max(1, navigator
                        .hardwareConcurrency - 1) : 2
                }, function(err) {
                    if (err) {
                        console.error('Quagga init error', err);
                        alert('Gagal membuka kamera: ' + (err.message || err));
                        return;
                    }
                    Quagga.start();
                    running = true;
                    startBtn.classList.add('d-none');
                    stopBtn.classList.remove('d-none');
                    document.getElementById('html5qr-feedback').classList.remove('d-none');
                    console.info('Quagga started');
                });

                Quagga.onDetected(async function(result) {
                    if (!result || !result.codeResult) return;
                    const code = result.codeResult.code;
                    if (lastResult === code) return; // de-bounce
                    lastResult = code;

                    try {
                        beepEl.play().catch(() => {});
                    } catch (e) {}

                    // fetch server for item
                    try {
                        const res = await fetch('/scanner-barang/search/' + encodeURIComponent(code));
                        const json = await res.json();
                        if (json && json.status) {
                            showItem(json.data);
                            // stop scanner to prevent duplicate reads
                            stopScanner();
                        } else {
                            // not found: show message briefly
                            document.getElementById('scannerStatusText').textContent =
                                'Barang tidak ditemukan';
                            // allow next scan
                            setTimeout(() => {
                                document.getElementById('scannerStatusText').textContent =
                                    'Siap scan.';
                                lastResult = null;
                            }, 1500);
                        }
                    } catch (err) {
                        console.error('Lookup error', err);
                    }
                });
            };

            const stopScanner = async () => {
                if (!running) return;
                try {
                    Quagga.stop();
                } catch (e) {
                    console.warn('Quagga stop error', e);
                }
                try {
                    Quagga.offDetected();
                } catch (e) {}
                running = false;
                startBtn.classList.remove('d-none');
                stopBtn.classList.add('d-none');
                document.getElementById('html5qr-feedback').classList.add('d-none');
            };

            const resetScanner = () => {
                lastResult = null;
                document.getElementById('emptyState').classList.remove('d-none');
                document.getElementById('resultState').classList.add('d-none');
                document.getElementById('scannerStatusText').textContent =
                    'Siap scan. Fokuskan kursor ke area scan lalu pindai barcode.';
                // restart if it was running
                if (running) {
                    stopScanner();
                    setTimeout(startScanner, 300);
                }
            };

            // wire buttons
            startBtn.addEventListener('click', startScanner);
            stopBtn.addEventListener('click', stopScanner);
            resetBtn.addEventListener('click', resetScanner);

        })();
    </script>
@endsection
