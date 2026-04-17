@extends('layouts.apps')

@section('content')
    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-account-multiple"></i>
            </span> Data Customer
        </h3>
    </div>

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <div class="d-flex justify-content-end gap-2 mb-3">
                        <a href="{{ route('customer-camera.create.blob') }}" class="btn btn-sm btn-primary">Tambah Customer 1</a>
                        <a href="{{ route('customer-camera.create.path') }}" class="btn btn-sm btn-success">Tambah Customer 2</a>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nama Customer</th>
                                    <th>Provinsi</th>
                                    <th>Kota</th>
                                    <th>Kelurahan</th>
                                    <th>Metode Simpan</th>
                                    <th>Foto</th>
                                    <th>Dibuat</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->id }}</td>
                                        <td>{{ $customer->nama_customer }}</td>
                                        <td>{{ $customer->province_name ?? '-' }}</td>
                                        <td>{{ $customer->regency_name ?? '-' }}</td>
                                        <td>{{ $customer->village_name ?? '-' }}</td>
                                        <td>
                                            @if ($customer->foto_blob)
                                                BLOB Database
                                            @elseif ($customer->foto_path)
                                                File Path
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>
                                            @if ($customer->foto_blob)
                                                <img src="data:{{ $customer->foto_mime ?? 'image/jpeg' }};base64,{{ base64_encode($customer->foto_blob) }}" alt="Foto {{ $customer->nama_customer }}" style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px;">
                                            @elseif ($customer->foto_path)
                                                <img src="{{ asset('storage/' . $customer->foto_path) }}" alt="Foto {{ $customer->nama_customer }}" style="width: 90px; height: 90px; object-fit: cover; border-radius: 8px;">
                                            @else
                                                <span class="text-muted">Tidak ada foto</span>
                                            @endif
                                        </td>
                                        <td>{{ $customer->created_at?->format('d-m-Y H:i') }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Belum ada data customer.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
