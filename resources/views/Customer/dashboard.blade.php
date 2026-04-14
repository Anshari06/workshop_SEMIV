@extends('layouts.payment')

@section('title', 'Dashboard Customer')
@section('content')
    <div class="container">
        <div class="table">
            <h1>Daftar Vendor</h1>
            <div class="row">
                @foreach ($vendors as $vendor)
                    @php
                        $rawPath = trim((string) (optional($vendor->menus->first())->path_gambar ?? ''), '/');
                        $imagePath = 'https://via.placeholder.com/640x360?text=No+Image';

                        if ($rawPath !== '') {
                            $candidates = [
                                $rawPath,
                                'assets/images/' . $rawPath,
                                'assets/images/MENU/' . basename($rawPath),
                            ];

                            foreach ($candidates as $candidate) {
                                if (file_exists(public_path($candidate))) {
                                    $imagePath = asset($candidate);
                                    break;
                                }
                            }
                        }
                    @endphp
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <img src="{{ $imagePath }}" class="card-img-top" alt="{{ $vendor->nama_vendor }}"
                                style="height: 220px; object-fit: cover;">
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title">{{ $vendor->nama_vendor }}</h5>
                                <p class="card-text">{{ $vendor->deskripsi ?? 'Vendor tersedia untuk pemesanan.' }}</p>
                                <a href="{{ route('customer.menu', $vendor->id) }}" class="btn btn-primary mt-auto">Lihat Menu</a>
                            </div>          
                         </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection