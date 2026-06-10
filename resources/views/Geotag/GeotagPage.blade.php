@extends('layouts.apps')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-crosshairs-gps"></i>
            </span>
            Kunjungan Toko
        </h3>
    </div>

    <div class="row g-4">
        <div class="col-lg-5">
            <div class="card stretch-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="section-title mb-1">Scan Barcode</p>
                            <p class="text-muted mb-0">Validasi toko sebelum kunjungan dicatat.</p>
                        </div>
                        <button type="button" class="btn btn-primary btn-sm">
                            <i class="mdi mdi-barcode-scan me-1"></i>
                            Mulai Scan
                        </button>
                    </div>

                    <div class="border rounded-4 p-4 text-center mb-3" style="min-height: 240px; background: #f7f9fc;">
                        <div class="d-flex flex-column justify-content-center align-items-center h-100 text-muted">
                            <i class="mdi mdi-qrcode-scan" style="font-size: 3rem;"></i>
                            <div class="fw-semibold mt-2">Area Scanner Barcode</div>
                            <small>Barcode akan tampil di sini setelah scan berhasil.</small>
                        </div>
                    </div>

                    <div class="alert alert-light border mb-0">
                        <small class="text-muted d-block mb-1">Barcode Terdeteksi</small>
                        <strong>TOKO001</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7">
            <div class="card stretch-card">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-4">
                        <div>
                            <p class="section-title mb-1">Informasi Toko</p>
                            <p class="text-muted mb-0">Data referensi lokasi toko dan posisi sales.</p>
                        </div>
                        <span class="badge text-bg-primary">Geotag</span>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Nama Toko</small>
                                <div class="fw-semibold fs-5">Toko Sumber Rejeki</div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Barcode</small>
                                <div class="fw-semibold fs-5">TOKO001</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Latitude Toko</small>
                                <div class="fw-semibold fs-5">-7.257472</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Longitude Toko</small>
                                <div class="fw-semibold fs-5">112.752088</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Accuracy</small>
                                <div class="fw-semibold fs-5">20 Meter</div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <p class="section-title mb-1">Lokasi Sales</p>
                            <p class="text-muted mb-0">Ambil posisi perangkat untuk validasi radius kunjungan.</p>
                        </div>
                        <button type="button" class="btn btn-success btn-sm">
                            <i class="mdi mdi-crosshairs-gps me-1"></i>
                            Ambil Lokasi
                        </button>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label class="form-label">Latitude</label>
                            <input type="text" class="form-control" value="-7.257500">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Longitude</label>
                            <input type="text" class="form-control" value="112.752100">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Accuracy</label>
                            <input type="text" class="form-control" value="15 Meter">
                        </div>
                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Jarak Aktual</small>
                                <div class="fw-semibold fs-5">120 Meter</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Threshold</small>
                                <div class="fw-semibold fs-5">300 Meter</div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="border rounded-4 p-3 h-100">
                                <small class="text-muted d-block">Threshold Efektif</small>
                                <div class="fw-semibold fs-5">335 Meter</div>
                            </div>
                        </div>
                    </div>

                    <div class="alert alert-success border-0 rounded-4 d-flex align-items-start gap-3 mb-4">
                        <i class="mdi mdi-check-circle fs-3"></i>
                        <div>
                            <h6 class="mb-1">Kunjungan Diterima</h6>
                            <div>Sales berada dalam radius lokasi toko.</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2 flex-wrap">
                        <button type="button" class="btn btn-primary">
                            <i class="mdi mdi-send me-1"></i>
                            Submit Kunjungan
                        </button>
                        <button type="button" class="btn btn-outline-secondary">
                            Reset
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection