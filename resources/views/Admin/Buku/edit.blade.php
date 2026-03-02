@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book-edit"></i>
            </span> Edit Buku
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('buku.index') }}">Data Buku</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Buku</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Edit Buku</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('buku.update', $buku->idbuku) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group">
                            <label for="judul">Judul Buku <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('judul') is-invalid @enderror" 
                                   id="judul" 
                                   name="judul" 
                                   placeholder="Masukkan judul buku"
                                   value="{{ old('judul', $buku->judul) }}"
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
                                   value="{{ old('pengarang', $buku->pengarang) }}"
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
                                @foreach($kategori as $item)
                                    <option value="{{ $item->idkategori }}" 
                                        {{ old('idkategori', $buku->idkategori) == $item->idkategori ? 'selected' : '' }}>
                                        {{ $item->nama_kategori }}
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
