@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book-plus"></i>
            </span> Tambah Buku
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Data Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">Tambah Buku</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Tambah Buku</h4>
                    <p class="card-description">
                        Isi form di bawah ini untuk menambahkan buku baru
                    </p>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form class="forms-sample" action="{{ route('buku.store') }}" method="POST">
                        @csrf
                        
                        {{-- <div class="form-group">
                            <label for="kode">Kode Buku</label>
                            <input type="text" 
                                   class="form-control" 
                                   value="Kode akan dibuat otomatis (Contoh: NL-2 untuk Novel ke-2)" 
                                   disabled>
                            <small class="form-text text-muted">
                                Kode dibuat otomatis berdasarkan kategori dan urutan buku
                            </small>
                        </div> --}}

                        <div class="form-group">
                            <label for="judul">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" 
                                   name="judul" 
                                   placeholder="Masukkan judul buku"
                                   value="{{ old('judul') }}"
                                   required>
                            @error('judul')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="pengarang">Pengarang <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('pengarang') is-invalid @enderror" 
                                   id="pengarang" 
                                   name="pengarang" 
                                   placeholder="Masukkan nama pengarang"
                                   value="{{ old('pengarang') }}"
                                   required>
                            @error('pengarang')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="idkategori">Kategori Buku <span class="text-danger">*</span></label>
                            <select class="form-control @error('idkategori') is-invalid @enderror" 
                                    id="idkategori" 
                                    name="idkategori"
                                    required>
                                <option value="">-- Pilih Kategori --</option>
                                @foreach($kategori as $kat)
                                    <option value="{{ $kat->idkategori }}" {{ old('idkategori') == $kat->idkategori ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idkategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-group mb-0">
                            <button type="submit" class="btn btn-gradient-primary me-2">
                                <i class="mdi mdi-content-save"></i> Simpan
                            </button>
                            <a href="{{ route('buku.index') }}" class="btn btn-light">
                                <i class="mdi mdi-arrow-left"></i> Batal
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
