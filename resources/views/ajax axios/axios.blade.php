@extends('layouts.apps')

@section('content')
    <style>
        .region-wrapper {
            max-width: 1180px;
            margin: 0 auto;
        }

        .region-card {
            background: #fff;
            border: 1px solid #dfe6ef;
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
            margin-bottom: 20px;
        }

        .region-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #e8edf4;
            font-weight: 700;
            color: #2e2d75;
            font-size: 1.05rem;
        }

        .region-card-body {
            padding: 20px;
        }

        .select-grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 12px;
            margin-bottom: 16px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-weight: 700;
            color: #334155;
            margin-bottom: 6px;
            font-size: 0.9rem;
        }

        .form-group select {
            min-height: 44px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            padding: 8px 12px;
            font-size: 14px;
        }

        .form-group select:focus {
            border-color: #2e2d75;
            box-shadow: 0 0 0 0.2rem rgba(46, 45, 117, 0.25);
            outline: none;
        }

        .form-group select:disabled {
            background-color: #f0f4f8;
            color: #94a3b8;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        .region-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        .region-table thead th {
            background: #eef2ff;
            color: #312e81;
            font-weight: 700;
            padding: 12px 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        .region-table tbody td {
            padding: 11px 10px;
            border: 1px solid #ddd;
            color: #1f2937;
        }

        .region-table tbody tr:hover {
            background: #f9f9f9;
        }

        .region-table tbody tr:nth-child(even) {
            background: #f5f5f5;
        }

        .empty-state {
            text-align: center;
            padding: 24px;
            color: #64748b;
            font-style: italic;
        }

        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 16px;
        }

        .button-group button {
            min-height: 44px;
            font-weight: 700;
        }

        @media (max-width: 1024px) {
            .select-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 640px) {
            .select-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-map"></i>
            </span> Modul 5 - Wilayah Administrasi Indonesia (Axios)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Cascading Select dari database</li>
            </ul>
        </nav>
    </div>

    <div class="region-wrapper">
        <div class="region-card">
            <div class="region-card-header">
                <i class="mdi mdi-folder-multiple"></i> Pilih Wilayah Administrasi
            </div>
            <div class="region-card-body">
                <form id="regionFormAxios" novalidate>
                    <div class="select-grid">
                        <div class="form-group">
                            <label for="selectProvinsiAxios">Provinsi (Level 1)</label>
                            <select id="selectProvinsiAxios" name="provinsi" required>
                                <option value="0">Pilih Provinsi</option>
                                @foreach ($provinces as $province)
                                    <option value="{{ $province->id }}">{{ $province->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selectKotaAxios">Kota (Level 2)</label>
                            <select id="selectKotaAxios" name="kota" required disabled>
                                <option value="0">Pilih Kota</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selectKecamatanAxios">Kecamatan (Level 3)</label>
                            <select id="selectKecamatanAxios" name="kecamatan" required disabled>
                                <option value="0">Pilih Kecamatan</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="selectKelurahanAxios">Kelurahan (Level 4)</label>
                            <select id="selectKelurahanAxios" name="kelurahan" required disabled>
                                <option value="0">Pilih Kelurahan</option>
                            </select>
                        </div>
                    </div>

                    <div class="button-group">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-check-circle"></i> Tambahkan ke Tabel
                        </button>
                        <button type="reset"
                                class="btn btn-outline-secondary">
                                <i class="mdi mdi-refresh"></i> Reset
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="region-card">
            <div class="region-card-header">
                <i class="mdi mdi-table"></i> Daftar Wilayah yang Dipilih
            </div>
            <div class="region-card-body">
                <div class="table-wrapper">
                    <table class="region-table table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th style="width: 25%;">Provinsi</th>
                                <th style="width: 25%;">Kota</th>
                                <th style="width: 25%;">Kecamatan</th>
                                <th style="width: 25%;">Kelurahan</th>
                            </tr>
                        </thead>
                        <tbody id="regionTableBodyAxios">
                            <tr>
                                <td colspan="5" class="empty-state">Belum ada data. Silakan pilih wilayah dan tambahkan
                                    ke tabel.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const endpointRegencies = "{{ route('ajax-axios.regencies') }}";
            const endpointDistricts = "{{ route('ajax-axios.districts') }}";
            const endpointVillages = "{{ route('ajax-axios.villages') }}";

            const selectProvinsi = document.getElementById('selectProvinsiAxios');
            const selectKota = document.getElementById('selectKotaAxios');
            const selectKecamatan = document.getElementById('selectKecamatanAxios');
            const selectKelurahan = document.getElementById('selectKelurahanAxios');
            const regionForm = document.getElementById('regionFormAxios');
            const regionTableBody = document.getElementById('regionTableBodyAxios');

            let rowCount = 0;

            function resetSelect(selectElement, placeholderText, isDisabled) {
                selectElement.innerHTML = `<option value="0">${placeholderText}</option>`;
                selectElement.disabled = isDisabled;
            }

            function clearDependentSelects() {
                resetSelect(selectKota, 'Pilih Kota', true);
                resetSelect(selectKecamatan, 'Pilih Kecamatan', true);
                resetSelect(selectKelurahan, 'Pilih Kelurahan', true);
            }

            function setOptions(selectElement, items, placeholderText) {
                selectElement.innerHTML = `<option value="0">${placeholderText}</option>`;
                items.forEach(function(item) {
                    const option = document.createElement('option');
                    option.value = item.id;
                    option.textContent = item.name;
                    selectElement.appendChild(option);
                });
                selectElement.disabled = false;
            }

            selectProvinsi.addEventListener('change', function() {
                clearDependentSelects();

                const provinceId = this.value;
                if (provinceId === '0') {
                    return;
                }

                window.axios.get(endpointRegencies, {
                    params: {
                        province_id: provinceId
                    }
                }).then(function(response) {
                    setOptions(selectKota, response.data, 'Pilih Kota');
                }).catch(function() {
                    alert('Gagal memuat data kota dari database.');
                });
            });

            selectKota.addEventListener('change', function() {
                resetSelect(selectKecamatan, 'Pilih Kecamatan', true);
                resetSelect(selectKelurahan, 'Pilih Kelurahan', true);

                const regencyId = this.value;
                if (regencyId === '0') {
                    return;
                }

                window.axios.get(endpointDistricts, {
                    params: {
                        regency_id: regencyId
                    }
                }).then(function(response) {
                    setOptions(selectKecamatan, response.data, 'Pilih Kecamatan');
                }).catch(function() {
                    alert('Gagal memuat data kecamatan dari database.');
                });
            });

            selectKecamatan.addEventListener('change', function() {
                resetSelect(selectKelurahan, 'Pilih Kelurahan', true);

                const districtId = this.value;
                if (districtId === '0') {
                    return;
                }

                window.axios.get(endpointVillages, {
                    params: {
                        district_id: districtId
                    }
                }).then(function(response) {
                    setOptions(selectKelurahan, response.data, 'Pilih Kelurahan');
                }).catch(function() {
                    alert('Gagal memuat data kelurahan dari database.');
                });
            });

            regionForm.addEventListener('submit', function(event) {
                event.preventDefault();

                if (!regionForm.checkValidity()) {
                    regionForm.reportValidity();
                    return;
                }

                if (selectProvinsi.value === '0' || selectKota.value === '0' || selectKecamatan.value ===
                    '0' || selectKelurahan.value === '0') {
                    alert('Silakan pilih semua level wilayah');
                    return;
                }

                const provinsiName = selectProvinsi.options[selectProvinsi.selectedIndex].text;
                const kotaName = selectKota.options[selectKota.selectedIndex].text;
                const kecamatanName = selectKecamatan.options[selectKecamatan.selectedIndex].text;
                const kelurahanName = selectKelurahan.options[selectKelurahan.selectedIndex].text;

                rowCount++;

                if (regionTableBody.childElementCount === 1 && regionTableBody.querySelector(
                    '.empty-state')) {
                    regionTableBody.innerHTML = '';
                }

                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${rowCount}</td>
                    <td>${provinsiName}</td>
                    <td>${kotaName}</td>
                    <td>${kecamatanName}</td>
                    <td>${kelurahanName}</td>
                `;
                regionTableBody.appendChild(tr);

                regionForm.reset();
                clearDependentSelects();
            });

            regionForm.addEventListener('reset', function() {
                setTimeout(function() {
                    clearDependentSelects();
                }, 0);
            });
        });
    </script>
@endpush
