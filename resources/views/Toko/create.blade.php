@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store"></i>
            </span> Tambah Toko
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('toko.index') }}">Toko</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Tambah Toko</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Gagal!</strong> Terdapat validasi error.
                            <ul class="mb-0 mt-2">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <form action="{{ route('toko.store') }}" method="POST" id="formToko">
                        @csrf
                        <div class="mb-3">
                            <label for="nama_toko" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_toko') is-invalid @enderror" id="nama_toko" name="nama_toko" value="{{ old('nama_toko') }}" placeholder="Nama toko" required>
                            @error('nama_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="accuracy" class="form-label">Accuracy (Radius Toko) <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control @error('accuracy') is-invalid @enderror" id="accuracy" name="accuracy" value="{{ old('accuracy', 20) }}" min="1" placeholder="Radius toleransi lokasi toko (meter)" required>
                            @error('accuracy')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">Radius toleransi lokasi toko dalam meter.</small>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Lokasi Toko <span class="text-danger">*</span></label>
                            <div class="d-flex align-items-center gap-3">
                                <button type="button" id="btnLokasi" class="btn btn-outline-primary">
                                    <i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Saat Ini
                                </button>
                                <span id="statusLokasi" class="text-muted" style="font-size: 0.875rem;"></span>
                            </div>
                            <input type="hidden" id="latitude" name="latitude" value="{{ old('latitude') }}">
                            <input type="hidden" id="longitude" name="longitude" value="{{ old('longitude') }}">
                            <div id="lokasiInfo" class="mt-2" style="display: none;">
                                <small class="text-success">
                                    <i class="mdi mdi-check-circle"></i> Lokasi terdeteksi:
                                    <span id="lokasiTeks"></span>
                                </small>
                            </div>
                            @error('latitude')
                                <div class="text-danger" style="font-size: 0.875rem;">{{ $message }}</div>
                            @enderror
                            @error('longitude')
                                <div class="text-danger" style="font-size: 0.875rem;">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="barcode" name="barcode" value="{{ $nextBarcode }}" maxlength="8" readonly required>
                            <small class="text-muted">Kode toko dibuat otomatis.</small>
                            @error('barcode')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary" id="btnSimpan">
                                <i class="mdi mdi-content-save"></i> Simpan
                            </button>
                            <a href="{{ route('toko.index') }}" class="btn btn-secondary">
                                <i class="mdi mdi-close"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const btnLokasi = document.getElementById('btnLokasi');
    const statusLokasi = document.getElementById('statusLokasi');
    const lokasiInfo = document.getElementById('lokasiInfo');
    const lokasiTeks = document.getElementById('lokasiTeks');
    const latitudeInput = document.getElementById('latitude');
    const longitudeInput = document.getElementById('longitude');
    const form = document.getElementById('formToko');

    // Ambil lokasi saat ini
    btnLokasi.addEventListener('click', function () {
        if (!navigator.geolocation) {
            statusLokasi.textContent = 'Geolocation tidak didukung browser ini.';
            statusLokasi.className = 'text-danger';
            return;
        }

        btnLokasi.disabled = true;
        btnLokasi.innerHTML = '<i class="mdi mdi-loading mdi-spin"></i> Mendeteksi...';
        statusLokasi.textContent = 'Mendeteksi lokasi...';
        statusLokasi.className = 'text-muted';

        navigator.geolocation.getCurrentPosition(
            function (position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                const acc = position.coords.accuracy;

                latitudeInput.value = lat.toFixed(6);
                longitudeInput.value = lng.toFixed(6);

                lokasiTeks.textContent = lat.toFixed(6) + ', ' + lng.toFixed(6) + ' (akurasi: ±' + Math.round(acc) + 'm)';
                lokasiInfo.style.display = 'block';

                statusLokasi.textContent = 'Lokasi berhasil terdeteksi!';
                statusLokasi.className = 'text-success';

                btnLokasi.disabled = false;
                btnLokasi.innerHTML = '<i class="mdi mdi-refresh"></i> Ambil Ulang';
            },
            function (error) {
                btnLokasi.disabled = false;
                btnLokasi.innerHTML = '<i class="mdi mdi-crosshairs-gps"></i> Ambil Lokasi Saat Ini';
                switch (error.code) {
                    case error.PERMISSION_DENIED:
                        statusLokasi.textContent = 'Izin lokasi ditolak. Aktifkan di pengaturan browser.';
                        break;
                    case error.POSITION_UNAVAILABLE:
                        statusLokasi.textContent = 'Lokasi tidak tersedia.';
                        break;
                    case error.TIMEOUT:
                        statusLokasi.textContent = 'Waktu habis. Coba lagi.';
                        break;
                    default:
                        statusLokasi.textContent = 'Gagal mendapatkan lokasi.';
                }
                statusLokasi.className = 'text-danger';
            },
            { enableHighAccuracy: true, timeout: 15000, maximumAge: 0 }
        );
    });

    // Validasi sebelum submit
    form.addEventListener('submit', function (e) {
        if (!latitudeInput.value || !longitudeInput.value) {
            e.preventDefault();
            statusLokasi.textContent = 'Lokasi belum terdeteksi. Klik "Ambil Lokasi Saat Ini" terlebih dahulu.';
            statusLokasi.className = 'text-danger';
            lokasiInfo.style.display = 'none';
        }
    });
});
</script>
@endpush
