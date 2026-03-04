@extends('layouts.apps')
@section('content')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <script src="https://code.jquery.com/jquery-3.7.0.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-tag-check"></i>
            </span> Tag Harga
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Data Tag Harga <i
                        class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <strong>Berhasil!</strong> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <strong>Error!</strong>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="card-title mb-0">Daftar Barang Tag Harga</h4>
                        @if (Route::has('tag-harga.create'))
                            <a href="{{ route('tag-harga.create') }}" class="btn btn-sm btn-primary">
                                <i class="mdi mdi-plus"></i> Tambah Barang
                            </a>
                        @endif
                    </div>

                    <form action="{{ route('tag-harga.print') }}" method="POST">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-md-2">
                                <label>X (Kolom)</label>
                                <input type="number" name="x" min="1" max="5" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-2">
                                <label>Y (Baris)</label>
                                <input type="number" name="y" min="1" max="8" class="form-control"
                                    required>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-success">Cetak PDF</button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <button type="button" id="btnSelectAll" class="btn btn-sm btn-info">
                                <i class="mdi mdi-checkbox-marked"></i> Pilih Semua
                            </button>
                            <button type="button" id="btnDeselectAll" class="btn btn-sm btn-secondary">
                                <i class="mdi mdi-checkbox-blank-outline"></i> Hapus Pilihan
                            </button>
                        </div>

                        <table id="tableBarang" class="table table-bordered">
                            <thead>
                                <tr>
                                    <th width="50"></th>
                                    <th>ID</th>
                                    <th>Nama</th>
                                    <th>Harga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($products as $p)
                                    <tr>
                                        <td>
                                            <input type="checkbox" name="selected_barang[]" class="barang-checkbox"
                                                value="{{ $p->id_barang }}">
                                        </td>
                                        <td>{{ $p->id_barang }}</td>
                                        <td>{{ $p->nama_barang }}</td>
                                        <td>Rp {{ number_format($p->harga, 0, ',', '.') }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                    </form>

                </div>
            </div>
        </div>
    </div>
    <script>
        // Initialize DataTable dan buttons
        $(document).ready(function() {
            // Initialize DataTable
            var table = $('#tableBarang').DataTable({
                "paging": true,
                "lengthChange": true,
                "searching": true,
                "ordering": true,
            });

            // Tombol Pilih Semua
            $('#btnSelectAll').click(function(e) {
                e.preventDefault();
                $('#tableBarang').find('input.barang-checkbox').prop('checked', true);
                console.log('Semua checkbox dipilih');
            });

            // Tombol Hapus Pilihan
            $('#btnDeselectAll').click(function(e) {
                e.preventDefault();
                $('#tableBarang').find('input.barang-checkbox').prop('checked', false);
                console.log('Semua checkbox dihapus');
            });
        });
    </script>
@endsection
