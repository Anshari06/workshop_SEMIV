@extends('layouts.apps')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <style>
        .wrapper {
            max-width: 760px;
            margin: 0 auto;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 24px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
        }

        .title {
            margin: 0 0 18px;
            font-size: 22px;
        }

        .form-row {
            display: grid;
            grid-template-columns: 140px 1fr;
            gap: 10px;
            align-items: center;
            margin-bottom: 12px;
        }

        .form-row input {
            height: 36px;
            padding: 6px 10px;
            border: 1px solid #b7b7b7;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-row input:focus {
            border-color: #4b49ac;
            box-shadow: 0 0 0 0.2rem rgba(75, 73, 172, 0.15);
            outline: none;
        }

        #btnSubmit {
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .dataTables_wrapper .dataTables_filter input,
        .dataTables_wrapper .dataTables_length select {
            border: 1px solid #ced4da;
            border-radius: 6px;
            padding: 4px 8px;
        }

        #tableBarang {
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
        }

        #tableBarang thead th {
            background: #f2f4ff;
            color: #2e2d75;
            font-weight: 700;
        }

        #tableBarang th,
        #tableBarang td {
            text-align: center;
            vertical-align: middle;
        }

        #tableBarang tbody tr {
            cursor: pointer;
        }

        .navigation {
            margin-top: 20px;
            text-align: center;
        }

        .navigation a {
            display: inline-block;
            padding: 10px 20px;
            background: #4b49ac;
            color: white;
            border-radius: 6px;
            text-decoration: none;
            margin: 0 5px;
        }

        .navigation a:hover {
            background: #35338c;
        }
    </style>

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-table-large"></i>
            </span> Page JavaScript - Halaman 2 (DataTables)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Page JavaScript - DataTables</li>
            </ul>
        </nav>
    </div>

    <div class="wrapper">
        <h1 class="title">Input Data Barang (DataTables)</h1>

        <form id="barangForm" novalidate>
            <div class="form-row">
                <label for="namaBarang">Nama barang:</label>
                <input type="text" id="namaBarang" name="namaBarang" required>
            </div>

            <div class="form-row">
                <label for="hargaBarang">Harga barang:</label>
                <input type="number" id="hargaBarang" name="hargaBarang" min="0" required>
            </div>

            <button type="button" id="btnSubmit" class="btn btn-gradient-primary me-2 m-2">
                <i class="mdi mdi-content-save"></i> <span id="btnText">Simpan</span>
                <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
            </button>
        </form>

        <table id="tableBarang" class="table table-bordered table-hover table-striped">
            <thead>
                <tr>
                    <th>Nomor</th>
                    <th>Nama</th>
                    <th>Harga</th>
                </tr>
            </thead>
            <tbody id="barangTableBody"></tbody>
        </table>

        <!-- Modal Edit/Delete -->
        <div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalBarangLabel">Edit Data Barang</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="formEdit" novalidate>
                            <div class="mb-3">
                                <label for="editId" class="form-label">ID Barang</label>
                                <input type="text" class="form-control" id="editId" readonly>
                            </div>
                            <div class="mb-3">
                                <label for="editNama" class="form-label">Nama Barang <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNama" required>
                            </div>
                            <div class="mb-3">
                                <label for="editHarga" class="form-label">Harga Barang <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="editHarga" min="0" required>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" id="btnHapus" class="btn btn-danger">
                            <i class="mdi mdi-delete"></i> <span id="btnHapusText">Hapus</span>
                            <span id="btnHapusSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                        <button type="button" id="btnUbah" class="btn btn-primary">
                            <i class="mdi mdi-pencil"></i> <span id="btnUbahText">Ubah</span>
                            <span id="btnUbahSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div class="navigation">
            <a href="{{ route('jspage.index') }}">← Halaman 1 (HTML Table)</a>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('barangForm');
            const namaBarang = document.getElementById('namaBarang');
            const hargaBarang = document.getElementById('hargaBarang');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');

            const modalElement = document.getElementById('modalBarang');
            const formEdit = document.getElementById('formEdit');
            const editId = document.getElementById('editId');
            const editNama = document.getElementById('editNama');
            const editHarga = document.getElementById('editHarga');
            const btnHapus = document.getElementById('btnHapus');
            const btnHapusText = document.getElementById('btnHapusText');
            const btnHapusSpinner = document.getElementById('btnHapusSpinner');
            const btnUbah = document.getElementById('btnUbah');
            const btnUbahText = document.getElementById('btnUbahText');
            const btnUbahSpinner = document.getElementById('btnUbahSpinner');

            if (!form || !btnSubmit || !namaBarang || !hargaBarang) {
                console.error('Form element tidak lengkap.');
                return;
            }

            let nextId = 1;
            let currentRowIndex = null;
            let dataTable = null;
            let modalBarang = null;

            if (window.bootstrap && window.bootstrap.Modal && modalElement) {
                modalBarang = new window.bootstrap.Modal(modalElement);
            }

            try {
                if (window.jQuery && window.jQuery.fn && typeof window.jQuery.fn.DataTable === 'function') {
                    dataTable = window.jQuery('#tableBarang').DataTable({
                        paging: true,
                        lengthChange: false,
                        searching: true,
                        ordering: true,
                        info: true,
                        language: {
                            search: 'Cari:',
                            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                            infoEmpty: 'Belum ada data',
                            emptyTable: 'Belum ada data barang',
                            paginate: {
                                first: 'Awal',
                                last: 'Akhir',
                                next: 'Berikutnya',
                                previous: 'Sebelumnya'
                            }
                        }
                    });
                }
            } catch (error) {
                console.error('Gagal inisialisasi DataTables:', error);
            }

            if (!dataTable) {
                console.error('DataTables tidak aktif. Cek urutan script jQuery/DataTables.');
            }

            if (window.jQuery) {
                window.jQuery('#tableBarang tbody').on('click', 'tr', function() {
                    if (!dataTable || !modalBarang) {
                        return;
                    }

                    currentRowIndex = dataTable.row(this).index();
                    const data = dataTable.row(this).data();

                    if (data) {
                        editId.value = data[0];
                        editNama.value = data[1];
                        editHarga.value = String(data[2]).replace(/\./g, '');
                        modalBarang.show();
                    }
                });
            }

            function handleCreate() {
                if (!form.checkValidity()) {
                    form.reportValidity();
                    return;
                }

                if (!dataTable) {
                    alert('DataTables belum siap. Muat ulang halaman lalu coba lagi.');
                    return;
                }

                btnSubmit.disabled = true;
                btnText.textContent = 'Menyimpan...';
                btnSpinner.classList.remove('d-none');

                const idBarang = nextId++;
                const nama = namaBarang.value.trim();
                const harga = Number(hargaBarang.value).toLocaleString('id-ID');

                setTimeout(function() {
                    dataTable.row.add([idBarang, nama, harga]).draw(false);
                    form.reset();
                    namaBarang.focus();

                    btnSubmit.disabled = false;
                    btnText.textContent = 'Simpan';
                    btnSpinner.classList.add('d-none');
                }, 300);
            }

            btnSubmit.addEventListener('click', function(e) {
                e.preventDefault();
                handleCreate();
            });

            form.addEventListener('submit', function(e) {
                e.preventDefault();
                handleCreate();
            });

            btnHapus.addEventListener('click', function() {
                if (!dataTable || currentRowIndex === null) {
                    return;
                }

                btnHapus.disabled = true;
                btnHapusText.textContent = 'Menghapus...';
                btnHapusSpinner.classList.remove('d-none');

                setTimeout(function() {
                    dataTable.row(currentRowIndex).remove().draw(false);
                    currentRowIndex = null;
                    btnHapus.disabled = false;
                    btnHapusText.textContent = 'Hapus';
                    btnHapusSpinner.classList.add('d-none');

                    if (modalBarang) {
                        modalBarang.hide();
                    }
                }, 300);
            });

            btnUbah.addEventListener('click', function() {
                if (!formEdit.checkValidity()) {
                    formEdit.reportValidity();
                    return;
                }

                if (!dataTable || currentRowIndex === null) {
                    return;
                }

                btnUbah.disabled = true;
                btnUbahText.textContent = 'Mengubah...';
                btnUbahSpinner.classList.remove('d-none');

                setTimeout(function() {
                    const id = editId.value;
                    const nama = editNama.value.trim();
                    const harga = Number(editHarga.value).toLocaleString('id-ID');
                    dataTable.row(currentRowIndex).data([id, nama, harga]).draw(false);
                    currentRowIndex = null;

                    btnUbah.disabled = false;
                    btnUbahText.textContent = 'Ubah';
                    btnUbahSpinner.classList.add('d-none');

                    if (modalBarang) {
                        modalBarang.hide();
                    }
                }, 300);
            });
        });
    </script>
@endpush
