@extends('layouts.apps')

@section('content')
    @push('styles')
        <style>
            .barcode-shell {
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 14px;
                padding: 1.25rem;
            }

            .barcode-card {
                border: 1px solid #e5e7eb;
                border-radius: 12px;
                box-shadow: none;
            }

            .barcode-table thead th {
                white-space: nowrap;
                font-size: .875rem;
                color: #374151;
            }

            .barcode-preview {
                display: inline-block;
                padding: .5rem .75rem;
                background: #fff;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
            }

            .barcode-code {
                font-size: .8rem;
                color: #6b7280;
                letter-spacing: .05em;
            }

            .barcode-svg-wrap {
                max-width: 260px;
                margin: 0 auto;
                overflow: hidden;
            }

            .barcode-svg-wrap svg {
                width: 100%;
                height: auto;
                display: block;
            }
        </style>
    @endpush

    <div class="page-header">
        <h3 class="page-title">
            <span class="page-title-icon bg-gradient-primary text-white me-2">
                <i class="mdi mdi-barcode"></i>
            </span> Barcode Barang
        </h3>
        <nav aria-label="breadcrumb">
            <ul class="breadcrumb">
                <li class="breadcrumb-item active" aria-current="page">
                    <span></span>Data Barang Barcode <i class="mdi mdi-alert-circle-outline icon-sm text-primary align-middle"></i>
                </li>
            </ul>
        </nav>
    </div>

    <div class="barcode-shell">
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Berhasil!</strong> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card barcode-card mb-3">
            <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-md-center gap-3">
                <div>
                    <h4 class="card-title mb-1">Daftar Barang</h4>
                    <p class="text-muted mb-0">Barcode diambil langsung dari <strong>id_barang</strong>.</p>
                </div>
            </div>
        </div>

        <div class="card barcode-card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered align-middle barcode-table mb-0">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 12%">ID Barang</th>
                                <th>Nama Barang</th>
                                <th class="text-end" style="width: 18%">Harga</th>
                                <th class="text-center" style="width: 35%">Barcode</th>
                                <th class="text-center" style="width: 14%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($products as $product)
                                <tr>
                                    <td><strong>{{ $product->id_barang }}</strong></td>
                                    <td>{{ $product->nama_barang }}</td>
                                    <td class="text-end">Rp {{ number_format($product->harga, 0, ',', '.') }}</td>
                                    <td class="text-center">
                                        <div class="barcode-preview">
                                            @if ($product->barcode_svg)
                                                <div class="barcode-svg-wrap">{!! $product->barcode_svg !!}</div>
                                            @else
                                                <div class="text-muted small">Library Picqer belum terpasang</div>
                                            @endif
                                            <div class="barcode-code mt-1">{{ $product->id_barang }}</div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <a href="{{ route('barcode.print', $product->id_barang) }}" class="btn btn-sm btn-primary" target="_blank" rel="noopener">
                                            Cetak PDF
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Belum ada data barang.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
