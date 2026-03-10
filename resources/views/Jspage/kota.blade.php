@extends('layouts.apps')
@section('content')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

    <style>
        .kota-wrapper {
            max-width: 860px;
            margin: 0 auto;
            display: grid;
            gap: 18px;
        }

        .kota-card {
            background: #fff;
            border: 1px solid #dfe6ef;
            border-radius: 14px;
            box-shadow: 0 8px 18px rgba(15, 23, 42, 0.05);
        }

        .kota-card-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e8edf4;
            font-weight: 700;
            color: #2e2d75;
            font-size: 1.03rem;
        }

        .kota-card-body {
            padding: 20px;
            display: grid;
            gap: 12px;
        }

        .kota-block {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            background: #ffffff;
            padding: 12px;
        }

        .kota-form-grid {
            display: grid;
            grid-template-columns: 130px 1fr 170px;
            gap: 12px;
            align-items: center;
        }

        .kota-row {
            display: grid;
            grid-template-columns: 130px 1fr;
            gap: 12px;
            align-items: center;
        }

        .kota-form-grid label,
        .kota-row label {
            margin: 0;
            font-weight: 600;
            color: #334155;
            font-size: 0.92rem;
        }

        .kota-form-grid input,
        .kota-row select {
            min-height: 42px;
        }

        .kota-form-grid .btn {
            min-height: 42px;
            font-weight: 600;
        }

        .kota-selected {
            padding: 12px 14px;
            border-radius: 10px;
            background: #f8fafc;
            color: #1e293b;
            font-weight: 600;
            border: 1px dashed #d7e0ec;
        }

        .select2-container {
            width: 100% !important;
        }

        .select2-container--default .select2-selection--single {
            height: 42px;
            border: 1px solid #ced4da;
            border-radius: 6px;
            display: flex;
            align-items: center;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 40px;
            color: #495057;
            padding-left: 12px;
            padding-right: 34px;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 40px;
            right: 8px;
        }

        .select2-dropdown {
            border-color: #ced4da;
            border-radius: 6px;
        }

        @media (max-width: 768px) {
            .kota-form-grid,
            .kota-row {
                grid-template-columns: 1fr;
            }

            .kota-form-grid .btn {
                width: 100%;
            }
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-city"></i>
            </span> Page JavaScript - Select dan Select2
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Page JavaScript - Select</li>
            </ul>
        </nav>
    </div>

    <div class="kota-wrapper">
        <div class="kota-card">
            <div class="kota-card-header">
                Select
            </div>
            <div class="kota-card-body">
                <form id="kotaFormBasic" novalidate>
                    <div class="kota-form-grid kota-block">
                        <label for="namaKotaBasic">Input Kota</label>
                        <input type="text" id="namaKotaBasic" name="namaKotaBasic" class="form-control" placeholder="Masukkan nama kota" required>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambahkan
                        </button>
                    </div>
                </form>

                <div class="kota-row kota-block">
                    <label for="selectKotaBasic">Select Kota</label>
                    <select id="selectKotaBasic" class="form-select">
                        <option value="">Pilih kota</option>
                    </select>
                </div>

                <div class="kota-selected kota-block">
                    Kota Terpilih: <span id="kotaTerpilihBasic">-</span>
                </div>
            </div>
        </div>

        <div class="kota-card">
            <div class="kota-card-header">
                Select 2
            </div>
            <div class="kota-card-body">
                <form id="kotaFormSelect2" novalidate>
                    <div class="kota-form-grid kota-block">
                        <label for="namaKotaSelect2">Input Kota</label>
                        <input type="text" id="namaKotaSelect2" name="namaKotaSelect2" class="form-control" placeholder="Masukkan nama kota" required>
                        <button type="submit" class="btn btn-success">
                            <i class="mdi mdi-plus"></i> Tambahkan
                        </button>
                    </div>
                </form>

                <div class="kota-row kota-block">
                    <label for="selectKotaSelect2">Select Kota (Select2)</label>
                    <select id="selectKotaSelect2" class="form-select">
                        <option value="">Pilih kota</option>
                    </select>
                </div>

                <div class="kota-selected kota-block">
                    Kota Terpilih: <span id="kotaTerpilihSelect2">-</span>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const kotaFormBasic = document.getElementById('kotaFormBasic');
            const namaKotaBasic = document.getElementById('namaKotaBasic');
            const selectKotaBasic = document.getElementById('selectKotaBasic');
            const kotaTerpilihBasic = document.getElementById('kotaTerpilihBasic');

            const kotaFormSelect2 = document.getElementById('kotaFormSelect2');
            const namaKotaSelect2 = document.getElementById('namaKotaSelect2');
            const selectKotaSelect2 = document.getElementById('selectKotaSelect2');
            const kotaTerpilihSelect2 = document.getElementById('kotaTerpilihSelect2');

            let daftarKotaBasic = [];
            let daftarKotaSelect2 = [];

            function updateSelectOptions(selectElement, dataKota) {
                selectElement.innerHTML = '<option value="">Pilih kota</option>';

                dataKota.forEach(function(kota) {
                    const option = document.createElement('option');
                    option.value = kota.nama;
                    option.textContent = kota.nama;
                    selectElement.appendChild(option);
                });
            }

            kotaFormBasic.addEventListener('submit', function(event) {
                event.preventDefault();

                if (!kotaFormBasic.checkValidity()) {
                    kotaFormBasic.reportValidity();
                    return;
                }

                const nama = namaKotaBasic.value.trim();

                if (!nama) {
                    return;
                }

                daftarKotaBasic.push({
                    nama: nama
                });

                updateSelectOptions(selectKotaBasic, daftarKotaBasic);
                selectKotaBasic.value = nama;
                kotaTerpilihBasic.textContent = nama;
                kotaFormBasic.reset();
                namaKotaBasic.focus();
            });

            selectKotaBasic.addEventListener('change', function() {
                kotaTerpilihBasic.textContent = selectKotaBasic.value || '-';
            });

            kotaFormSelect2.addEventListener('submit', function(event) {
                event.preventDefault();

                if (!kotaFormSelect2.checkValidity()) {
                    kotaFormSelect2.reportValidity();
                    return;
                }

                const nama = namaKotaSelect2.value.trim();

                if (!nama) {
                    return;
                }

                daftarKotaSelect2.push({
                    nama: nama
                });

                updateSelectOptions(selectKotaSelect2, daftarKotaSelect2);

                if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.select2 === 'function') {
                    window.jQuery(selectKotaSelect2).trigger('change.select2');
                }

                selectKotaSelect2.value = nama;

                if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.select2 === 'function') {
                    window.jQuery(selectKotaSelect2).trigger('change');
                }

                kotaTerpilihSelect2.textContent = nama;
                kotaFormSelect2.reset();
                namaKotaSelect2.focus();
            });

            if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.select2 === 'function') {
                window.jQuery(selectKotaSelect2).select2({
                    width: '100%',
                    placeholder: 'Pilih kota'
                });

                window.jQuery(selectKotaSelect2).on('change', function() {
                    const selectedValue = window.jQuery(this).val();
                    kotaTerpilihSelect2.textContent = selectedValue || '-';
                });
            } else {
                selectKotaSelect2.addEventListener('change', function() {
                    kotaTerpilihSelect2.textContent = selectKotaSelect2.value || '-';
                });
            }
        });
    </script>
@endpush

