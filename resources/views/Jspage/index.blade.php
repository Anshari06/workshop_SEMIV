@extends('layouts.apps')
@section('content')
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

        #tableBarang {
            margin-top: 10px;
            border-radius: 8px;
            overflow: hidden;
            width: 100%;
            border-collapse: collapse;
        }

        #tableBarang thead th {
            background: #f2f4ff;
            color: #2e2d75;
            font-weight: 700;
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ddd;
            padding: 10px;
        }

        #tableBarang tbody td {
            text-align: center;
            vertical-align: middle;
            border: 1px solid #ddd;
            padding: 10px;
        }

        #tableBarang tbody tr {
            cursor: pointer;
        }

        #tableBarang tbody tr:hover {
            background: #f9f9f9;
        }

        #tableBarang tbody tr:nth-child(even) {
            background: #f5f5f5;
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
                <i class="mdi mdi-table"></i>
            </span> Page JavaScript - Halaman 1 (HTML Table)
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">Page JavaScript - HTML Table</li>
            </ul>
        </nav>
    </div>

    <div class="wrapper">
        <h1 class="title">Input Data Barang (Plain HTML Table)</h1>

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

        <table id="tableBarang">
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
            <a href="{{ route('jspage.datatables') }}">Halaman 2 (DataTables) →</a>
            <a href="{{ route('jspage.kota') }}">Halaman 3 (Select dan Select2) →</a>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            console.log('DOM Loaded - Inisialisasi halaman');
            
            const form = document.getElementById('barangForm');
            const namaBarang = document.getElementById('namaBarang');
            const hargaBarang = document.getElementById('hargaBarang');
            const btnSubmit = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            const barangTableBody = document.getElementById('barangTableBody');

            // Check semua element exist
            console.log('Form:', form);
            console.log('Button Submit:', btnSubmit);
            
            if (!form || !btnSubmit) {
                console.error('Element tidak ditemukan!');
                return;
            }

            // Modal elements
            const modalBarang = new bootstrap.Modal(document.getElementById('modalBarang'));
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

            let nextId = 1;
            let currentRow = null;

            // Submit form untuk tambah data
            btnSubmit.addEventListener('click', function(e) {
                e.preventDefault();
                console.log('Tombol simpan diklik');
                
                if (!form.checkValidity()) {
                    console.log('Form tidak valid');
                    form.reportValidity();
                    return;
                }

                console.log('Form valid - proses simpan');
                
                btnSubmit.disabled = true;
                btnText.textContent = 'Menyimpan...';
                btnSpinner.classList.remove('d-none');

                const idBarang = nextId++;
                const nama = namaBarang.value.trim();
                const harga = Number(hargaBarang.value).toLocaleString('id-ID');

                console.log('Data:', idBarang, nama, hargaBarang.value);

                setTimeout(function() {
                    const row = document.createElement('tr');
                    row.dataset.id = idBarang;
                    row.dataset.nama = namaBarang.value.trim();
                    row.dataset.harga = hargaBarang.value;
                    row.innerHTML = `
                        <td>${idBarang}</td>
                        <td>${nama}</td>
                        <td>${harga}</td>
                    `;
                    
                    // Event listener untuk klik row
                    row.addEventListener('click', function() {
                        showEditModal(this);
                    });
                    
                    barangTableBody.appendChild(row);
                    console.log('Data ditambahkan ke tabel');

                    form.reset();
                    namaBarang.focus();

                    btnSubmit.disabled = false;
                    btnText.textContent = 'Simpan';
                    btnSpinner.classList.add('d-none');
                }, 400);
            });

            // Fungsi untuk menampilkan modal edit
            function showEditModal(row) {
                console.log('Modal edit dibuka');
                currentRow = row;
                editId.value = row.dataset.id;
                editNama.value = row.dataset.nama;
                editHarga.value = row.dataset.harga;
                modalBarang.show();
            }

            // Fungsi untuk hapus data
            btnHapus.addEventListener('click', function() {
                btnHapus.disabled = true;
                btnHapusText.textContent = 'Menghapus...';
                btnHapusSpinner.classList.remove('d-none');

                setTimeout(function() {
                    if (currentRow) {
                        currentRow.remove();
                        currentRow = null;
                    }

                    btnHapus.disabled = false;
                    btnHapusText.textContent = 'Hapus';
                    btnHapusSpinner.classList.add('d-none');
                    modalBarang.hide();
                }, 400);
            });

            // Fungsi untuk ubah data
            btnUbah.addEventListener('click', function() {
                if (!formEdit.checkValidity()) {
                    formEdit.reportValidity();
                    return;
                }

                btnUbah.disabled = true;
                btnUbahText.textContent = 'Mengubah...';
                btnUbahSpinner.classList.remove('d-none');

                setTimeout(function() {
                    if (currentRow) {
                        const id = editId.value;
                        const nama = editNama.value.trim();
                        const harga = editHarga.value;
                        const hargaFormatted = Number(harga).toLocaleString('id-ID');

                        currentRow.dataset.nama = nama;
                        currentRow.dataset.harga = harga;
                        currentRow.innerHTML = `
                            <td>${id}</td>
                            <td>${nama}</td>
                            <td>${hargaFormatted}</td>
                        `;

                        // Re-attach event listener
                        currentRow.addEventListener('click', function() {
                            showEditModal(this);
                        });

                        currentRow = null;
                    }

                    btnUbah.disabled = false;
                    btnUbahText.textContent = 'Ubah';
                    btnUbahSpinner.classList.add('d-none');
                    modalBarang.hide();
                }, 400);
            });
        });
    </script>
@endsection
