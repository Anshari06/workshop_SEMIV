@extends('layouts.apps')
@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-book-edit"></i>
            </span> Edit Kategori
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('kategori.index') }}">Data Kategori</a></li>
                <li class="breadcrumb-item active" aria-current="page">Edit Kategori</li>
            </ul>
        </nav>
    </div>

    <div class="row">
        <div class="col-md-6 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Form Edit Kategori</h4>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('kategori.update', $kategori->idkategori) }}" method="POST" id="formKategoriEdit">
                        @csrf
                        @method('PUT')

                        <div class="mb-3">
                            <label for="nama_kategori" class="form-label">Nama Kategori <span class="text-danger">*</span></label>
                            <input type="text" name="nama_kategori" id="nama_kategori" class="form-control @error('nama_kategori') is-invalid @enderror" 
                                value="{{ old('nama_kategori', $kategori->nama_kategori) }}" required>
                            @error('nama_kategori')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="button" id="btnSubmit" class="btn btn-gradient-primary">
                            <i class="mdi mdi-content-save"></i> <span id="btnText">Simpan</span>
                            <span id="btnSpinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                        </button>
                        <a href="{{ route('kategori.index') }}" class="btn btn-light">
                            <i class="mdi mdi-arrow-left"></i> Batal
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.getElementById('btnSubmit').addEventListener('click', function() {
            const form = document.getElementById('formKategoriEdit');
            const button = document.getElementById('btnSubmit');
            const btnText = document.getElementById('btnText');
            const btnSpinner = document.getElementById('btnSpinner');
            
            // Check HTML5 form validity
            if (!form.checkValidity()) {
                // Show validation messages
                form.reportValidity();
                return;
            }
            
            // Disable button and show spinner
            button.disabled = true;
            btnText.textContent = 'Menyimpan...';
            btnSpinner.classList.remove('d-none');
            
            // Submit form
            form.submit();
        });
    </script>
    @endpush

@endsection
