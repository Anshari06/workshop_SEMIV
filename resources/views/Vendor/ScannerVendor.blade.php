@extends('layouts.payment')

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
                                <button type="button" class="btn btn-primary">
                                    <i class="mdi mdi-camera me-1"></i> Start Kamera
                                </button>
                                <button type="button" class="btn btn-outline-secondary">
                                    Reset
                                </button>
                                <button type="button" class="btn btn-outline-danger">
                                    Stop Kamera
                                </button>
                            </div>

                            <div class="border rounded-3 bg-white p-3 mt-2" style="min-height: 280px;">
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    Area kamera / preview QR
                                </div>
                            </div>

                            <div class="small text-muted mt-1">
                                Status scanner: siap digunakan.
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
                        <span class="badge text-bg-success px-3 py-2">Ready</span>
                    </div>

                    <div class="alert alert-success d-inline-flex align-items-center gap-2 mb-3">
                        <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                        <span>Siap scan QR Code customer.</span>
                    </div>

                    <div class="card border rounded-4 mb-4">
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">ID Customer</div>
                                        <div class="fw-bold fs-5 text-dark">-</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">Nama Customer</div>
                                        <div class="fw-bold fs-5 text-dark">-</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border h-100">
                                        <div class="text-muted small text-uppercase fw-semibold mb-1">Status Bayar</div>
                                        <span class="badge text-bg-secondary px-3 py-2">Belum dibaca</span>
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
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="3" class="text-center text-muted py-4">
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
                                        <div class="fw-bold fs-4 text-dark">0</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <div class="text-muted small text-uppercase fw-semibold">Total Bayar</div>
                                        <div class="fw-bold fs-4 text-dark">Rp 0</div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="p-3 rounded-3 bg-light border">
                                        <div class="text-muted small text-uppercase fw-semibold">Metode Bayar</div>
                                        <div class="fw-bold fs-4 text-dark">-</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
