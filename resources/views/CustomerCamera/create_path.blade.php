@extends('layouts.apps')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-folder-image"></i>
            </span> Tambah Customer 2 (Simpan File Path)
        </h3>
    </div>

    <div class="row">
        <div class="col-md-10 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('customer-camera.store.path') }}" id="customerPathForm">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Nama Customer</label>
                            <input type="text" name="nama_customer" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Provinsi</label>
                                <select class="form-select" name="province_id" id="provinceSelect" required>
                                    <option value="">Pilih Provinsi</option>
                                    @foreach ($provinces as $province)
                                        <option value="{{ $province->id }}">{{ $province->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kota</label>
                                <select class="form-select" name="regency_id" id="regencySelect" required disabled>
                                    <option value="">Pilih Kota</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Kelurahan</label>
                                <select class="form-select" name="village_id" id="villageSelect" required disabled>
                                    <option value="">Pilih Kelurahan</option>
                                </select>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Preview Kamera</label>
                                <video id="cameraVideo" class="w-100 border rounded" autoplay playsinline style="max-height: 320px; transform: scaleX(-1);"></video>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Hasil Capture</label>
                                <canvas id="captureCanvas" class="w-100 border rounded" style="max-height: 320px;"></canvas>
                            </div>
                        </div>

                        <input type="hidden" name="photo_data" id="photoData">

                        <div class="mt-3 d-flex gap-2">
                            <button type="button" id="startCameraBtn" class="btn btn-secondary">Nyalakan Kamera</button>
                            <button type="button" id="captureBtn" class="btn btn-info">Ambil Foto</button>
                            <button type="submit" class="btn btn-success">Simpan Customer</button>
                            <a href="{{ route('customer-camera.customers.index') }}" class="btn btn-light">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        const startCameraBtn = document.getElementById('startCameraBtn');
        const captureBtn = document.getElementById('captureBtn');
        const video = document.getElementById('cameraVideo');
        const canvas = document.getElementById('captureCanvas');
        const photoDataInput = document.getElementById('photoData');
        const form = document.getElementById('customerPathForm');
        const provinceSelect = document.getElementById('provinceSelect');
        const regencySelect = document.getElementById('regencySelect');
        const villageSelect = document.getElementById('villageSelect');
        const regenciesUrl = '{{ route('customer-camera.api.regencies') }}';
        const villagesUrl = '{{ route('customer-camera.api.villages') }}';
        let streamInstance = null;

        function fillSelect(selectElement, items, placeholder) {
            selectElement.innerHTML = `<option value="">${placeholder}</option>`;
            items.forEach((item) => {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                selectElement.appendChild(option);
            });
            selectElement.disabled = items.length === 0;
        }

        provinceSelect.addEventListener('change', async function () {
            const provinceId = this.value;
            fillSelect(regencySelect, [], 'Pilih Kota');
            fillSelect(villageSelect, [], 'Pilih Kelurahan');

            if (!provinceId) {
                return;
            }

            const response = await fetch(`${regenciesUrl}?province_id=${encodeURIComponent(provinceId)}`);
            const data = await response.json();
            fillSelect(regencySelect, data, 'Pilih Kota');
        });

        regencySelect.addEventListener('change', async function () {
            const regencyId = this.value;
            fillSelect(villageSelect, [], 'Pilih Kelurahan');

            if (!regencyId) {
                return;
            }

            const response = await fetch(`${villagesUrl}?regency_id=${encodeURIComponent(regencyId)}`);
            const data = await response.json();
            fillSelect(villageSelect, data, 'Pilih Kelurahan');
        });

        startCameraBtn.addEventListener('click', async function () {
            try {
                streamInstance = await navigator.mediaDevices.getUserMedia({ video: true, audio: false });
                video.srcObject = streamInstance;
            } catch (error) {
                alert('Gagal mengakses kamera. Pastikan browser mengizinkan akses kamera.');
                console.error(error);
            }
        });

        captureBtn.addEventListener('click', function () {
            if (!video.srcObject) {
                alert('Nyalakan kamera terlebih dahulu.');
                return;
            }

            const context = canvas.getContext('2d');
            canvas.width = video.videoWidth;
            canvas.height = video.videoHeight;
            context.save();
            context.translate(canvas.width, 0);
            context.scale(-1, 1);
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            context.restore();

            photoDataInput.value = canvas.toDataURL('image/jpeg', 0.9);
        });

        form.addEventListener('submit', function (event) {
            if (!photoDataInput.value) {
                event.preventDefault();
                alert('Ambil foto customer terlebih dahulu.');
            }
        });
    </script>
@endsection
