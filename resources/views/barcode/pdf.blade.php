<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Barcode {{ $barang->id_barang }}</title>
    <style>
        @page {
            margin: 4mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            margin: 0;
            color: #111827;
        }

        .card {
            border: 1px solid #d1d5db;
            border-radius: 10px;
            padding: 8px;
        }

        h2 {
            margin: 0 0 6px;
            font-size: 12px;
        }

        .row {
            margin: 0 0 4px;
            font-size: 10px;
        }

        .label {
            font-weight: 700;
        }

        .barcode {
            margin-top: 8px;
            text-align: center;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px;
        }

        .barcode img {
            width: 220px;
            height: 48px;
        }

        .code {
            margin-top: 4px;
            font-size: 9px;
            letter-spacing: 0.08em;
            color: #374151;
        }
    </style>
</head>

<body>
    <div class="card">
        <h2>Label Barcode Barang</h2>

        <p class="row"><span class="label">ID Barang:</span> {{ $barang->id_barang }}</p>
        <p class="row"><span class="label">Nama Barang:</span> {{ $barang->nama_barang }}</p>
        <p class="row"><span class="label">Harga:</span> Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>

        <div class="barcode">
            @if ($barcodePngBase64)
                <img src="data:image/png;base64,{{ $barcodePngBase64 }}" alt="Barcode {{ $barang->id_barang }}">
            @else
                <p>Library Picqer belum terpasang.</p>
            @endif
            <div class="code">{{ $barang->id_barang }}</div>
        </div>
    </div>
</body>

</html>
