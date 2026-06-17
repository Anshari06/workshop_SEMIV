@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-store"></i>
            </span> Edit Toko
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('toko.index') }}">Toko</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Edit Toko</h4>

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

                    <form action="{{ route('toko.update', $toko->barcode) }}" method="POST" id="formToko">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="barcode" class="form-label">Barcode</label>
                            <input type="text" class="form-control" id="barcode" value="{{ $toko->barcode }}" readonly>
                            <small class="text-muted">Barcode tidak dapat diubah.</small>
                        </div>

                        <div class="mb-3">
                            <label for="nama_toko" class="form-label">Nama Toko <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('nama_toko') is-invalid @enderror" id="nama_toko" name="nama_toko" value="{{ old('nama_toko', $toko->nama_toko) }}" required>
                            @error('nama_toko')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="accuracy" class="form-label">Accuracy (Radius Toko) <span class="text-danger">*</span></label>
                            <input type="number" step="any" class="form-control @error('accuracy') is-invalid @enderror" id="accuracy" name="accuracy" value="{{ old('accuracy', $toko->accuracy) }}" min="1" required>
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
                            <div class="row mt-2">
                                <div class="col-md-6">
                                    <label for="latitude" class="form-label">Latitude</label>
                                    <input type="number" step="any" class="form-control @error('latitude') is-invalid @enderror" id="latitude" name="latitude" value="{{ old('latitude', $toko->latitude) }}" required>
                                    @error('latitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="longitude" class="form-label">Longitude</label>
                                    <input type="number" step="any" class="form-control @error('longitude') is-invalid @enderror" id="longitude" name="longitude" value="{{ old('longitude', $toko->longitude) }}" required>
                                    @error('longitude')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div id="lokasiInfo" class="mt-2" style="display: none;">
                                <small class="text-success">
                                    <i class="mdi mdi-check-circle"></i> Lokasi terdeteksi:
                                    <span id="lokasiTeks"></span>
                                </small>
                            </div>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="mdi mdi-content-save"></i> Simpan Perubahan
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
});
</script>
@endpush
