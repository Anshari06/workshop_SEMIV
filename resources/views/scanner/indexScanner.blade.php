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

        ##scanner-container {

            position: relative;

            width: 100%;

            height: 300px;

            overflow: hidden;

            border-radius: 16px;

            background: black;

        }

        #scanner-container video {

            transform: scale(1.2);

            width: 100% !important;

            height: 100% !important;

            object-fit: cover;

        }

        #scanner-container canvas {

            position: absolute;

            top: 0;

            left: 0;

            width: 100% !important;

            height: 100% !important;

        }

        #scanner-container video,
        #scanner-container canvas {
            width: 100% !important;
            height: 300px !important;
            object-fit: cover;
        }


        #scanner-container canvas {

            position: absolute;

            top: 0;

            left: 0;

            width: 100% !important;

            height: 100% !important;

        }
    </style>
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-camera"></i>
            </span>
            Data Scanner
        </h3>
    </div>

    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body p-4 p-lg-5">

            <div class="row g-4 align-items-stretch">

                {{-- LEFT --}}
                <div class="col-lg-4">

                    <div class="card h-100 border bg-light rounded-4">

                        <div class="card-body text-center p-4 d-flex flex-column justify-content-center gap-3">

                            <div class="mx-auto rounded-circle d-flex align-items-center justify-content-center bg-primary bg-opacity-10 text-primary"
                                style="width: 76px; height: 76px;">
                                <i class="mdi mdi-barcode-scan" style="font-size: 36px;"></i>
                            </div>

                            <div>
                                <h4 class="fw-semibold mb-2">Scanner Siap</h4>

                                <p class="text-muted mb-0">
                                    Arahkan barcode ke kamera laptop.
                                </p>
                            </div>

                            <div class="alert alert-info mb-0 py-2 small">
                                Barcode yang cocok akan memunculkan data barang.
                            </div>

                            <div class="d-flex flex-wrap justify-content-center gap-2">

                                <button type="button" class="btn btn-outline-primary" id="startCameraBtn">
                                    Start Kamera
                                </button>

                                <button type="button" class="btn btn-outline-secondary" id="resetButton">
                                    Reset
                                </button>

                                <button type="button" class="btn btn-outline-danger d-none" id="stopCameraBtn">
                                    Stop Kamera
                                </button>

                            </div>

                            <div id="scanner-container" style="width:100%; height:300px;">
                            </div>

                            <div id="html5qr-feedback" class="small text-muted mt-2 d-none">
                                Kamera aktif, arahkan ke barcode.
                            </div>

                        </div>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="col-lg-8">

                    <div class="d-flex justify-content-between align-items-center mb-3">

                        <div>
                            <h4 class="mb-1">Hasil Pembacaan</h4>

                            <div class="text-muted">
                                Data diambil dari database barang.
                            </div>
                        </div>

                        <span class="badge text-bg-success px-3 py-2">
                            Ready
                        </span>

                    </div>

                    <div class="alert alert-success d-inline-flex align-items-center gap-2 mb-3" id="scannerStatus">

                        <span class="spinner-border spinner-border-sm"></span>

                        <span id="scannerStatusText">
                            Siap scan barcode.
                        </span>

                    </div>

                    <input type="text" id="barcodeInput" class="scanner-input" autocomplete="off">

                    <div class="card border rounded-4 mb-4">

                        <div class="card-body p-4">

                            <div id="emptyState" class="alert alert-light border mb-0">

                                Hasil scan akan muncul di sini.

                            </div>

                            <div id="resultState" class="d-none">

                                <div class="row g-3">

                                    <div class="col-md-4">
                                        <div class="text-muted small text-uppercase fw-semibold">
                                            ID Barang
                                        </div>

                                        <div class="fw-bold fs-5 text-dark" id="resultId">
                                            -
                                        </div>
                                    </div>

                                    <div class="col-md-5">
                                        <div class="text-muted small text-uppercase fw-semibold">
                                            Nama Barang
                                        </div>

                                        <div class="fw-bold fs-5 text-dark" id="resultName">
                                            -
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="text-muted small text-uppercase fw-semibold">
                                            Harga
                                        </div>

                                        <div class="fw-bold fs-5 text-dark" id="resultPrice">
                                            -
                                        </div>
                                    </div>

                                </div>

                                <hr class="my-4">

                                <div class="p-3 rounded-3 bg-light border">

                                    <div class="text-muted small text-uppercase fw-semibold mb-1">
                                        Barcode Terakhir
                                    </div>

                                    <div class="fs-5 fw-bold text-dark" id="resultBarcode">
                                        -
                                    </div>

                                </div>

                            </div>

                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>

    {{-- SOUND --}}
    <audio id="beep-sound" src="{{ asset('sounds/beep.mp3') }}" preload="auto">
    </audio>

    {{-- QUAGGA --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

    <script>
        (function() {

            const startBtn = document.getElementById('startCameraBtn');
            const stopBtn = document.getElementById('stopCameraBtn');
            const resetBtn = document.getElementById('resetButton');

            const scannerContainer = document.getElementById('scanner-container');

            const beepEl = document.getElementById('beep-sound');

            let scannerActive = false;

            let lastCode = null;

            function formatCurrency(value) {

                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0
                }).format(value);

            }

            function showItem(data) {

                document.getElementById('emptyState')
                    .classList.add('d-none');

                document.getElementById('resultState')
                    .classList.remove('d-none');

                document.getElementById('resultId').textContent =
                    data.id_barang;

                document.getElementById('resultName').textContent =
                    data.nama_barang;

                document.getElementById('resultPrice').textContent =
                    formatCurrency(data.harga);

                document.getElementById('resultBarcode').textContent =
                    data.id_barang;

                document.getElementById('scannerStatusText').textContent =
                    'Barcode berhasil dibaca';

            }

            async function processBarcode(code) {

                if (lastCode === code) return;

                lastCode = code;

                try {

                    beepEl.currentTime = 0;

                    await beepEl.play();

                } catch (e) {}

                try {

                    const response = await fetch(
                        '/scanner-barang/search/' +
                        encodeURIComponent(code)
                    );

                    const result = await response.json();

                    console.log(result);

                    if (result.status) {

                        showItem(result.data);

                        stopScanner();

                    } else {

                        document.getElementById('scannerStatusText')
                            .textContent =
                            'Barang tidak ditemukan';

                        setTimeout(() => {

                            document.getElementById('scannerStatusText')
                                .textContent =
                                'Siap scan barcode';

                            lastCode = null;

                        }, 2000);

                    }

                } catch (err) {

                    console.error(err);

                }

            }

            function startScanner() {

                if (scannerActive) return;

                scannerContainer.innerHTML = "";

                Quagga.init({

                    inputStream: {

                        name: "Live",

                        type: "LiveStream",

                        target: scannerContainer,

                        constraints: {

                            facingMode: "user",

                            width: {
                                ideal: 320
                            },

                            height: {
                                ideal: 240
                            }

                        }

                    },

                    locator: {

                        patchSize: "x-large",
                        halfSample: true

                    },

                    numOfWorkers: 1,

                    frequency: 15,

                    decoder: {

                        readers: [
                            "code_128_reader"
                        ]

                    },

                    locate: true

                }, function(err) {

                    if (err) {

                        console.error(err);

                        alert("Gagal membuka kamera");

                        return;

                    }

                    Quagga.start();

                    scannerActive = true;

                    startBtn.classList.add('d-none');

                    stopBtn.classList.remove('d-none');

                    document.getElementById('scannerStatusText')
                        .textContent =
                        'Scanner aktif';

                    console.log("Scanner aktif");

                    const video = scannerContainer.querySelector("video");

                    if (video) {

                        video.setAttribute("autoplay", true);

                        video.setAttribute("muted", true);

                        video.setAttribute("playsinline", true);

                        video.style.objectFit = "cover";

                    }

                });

                Quagga.onDetected(function(data) {

                    console.log("DETECTED", data);

                    if (!data.codeResult) return;

                    const code = data.codeResult.code;

                    console.log("HASIL:", code);

                    processBarcode(code);

                });

            }

            function stopScanner() {

                if (!scannerActive) return;

                Quagga.stop();

                scannerActive = false;

                const video = scannerContainer.querySelector('video');

                if (video && video.srcObject) {

                    video.srcObject.getTracks()
                        .forEach(track => track.stop());

                }

                startBtn.classList.remove('d-none');

                stopBtn.classList.add('d-none');

                document.getElementById('scannerStatusText')
                    .textContent =
                    'Scanner berhenti';

            }

            function resetScanner() {

                lastCode = null;

                document.getElementById('emptyState')
                    .classList.remove('d-none');

                document.getElementById('resultState')
                    .classList.add('d-none');

                document.getElementById('scannerStatusText')
                    .textContent =
                    'Siap scan barcode';

                stopScanner();

                setTimeout(() => {

                    startScanner();

                }, 500);

            }

            startBtn.addEventListener('click', startScanner);

            stopBtn.addEventListener('click', stopScanner);

            resetBtn.addEventListener('click', resetScanner);

        })();
    </script>
@endsection
