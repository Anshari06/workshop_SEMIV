<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print Barcode {{ $barang->id_barang }}</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            margin: 24px;
            color: #111827;
        }

        .print-card {
            max-width: 420px;
            border: 1px solid #d1d5db;
            border-radius: 12px;
            padding: 16px;
        }

        .title {
            margin: 0 0 12px;
            font-size: 20px;
        }

        .muted {
            color: #6b7280;
            margin: 0 0 16px;
            font-size: 14px;
        }

        .row {
            margin: 0 0 8px;
            font-size: 14px;
        }

        .label {
            font-weight: 700;
        }

        .barcode-wrap {
            margin-top: 16px;
            padding: 12px;
            border: 1px solid #e5e7eb;
            border-radius: 10px;
            text-align: center;
        }

        .barcode-wrap svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .barcode-text {
            margin-top: 8px;
            letter-spacing: .06em;
            color: #374151;
            font-size: 13px;
        }

        .actions {
            margin-top: 16px;
            display: flex;
            gap: 8px;
        }

        .btn {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 8px 12px;
            text-decoration: none;
            color: #111827;
            font-size: 14px;
            cursor: pointer;
            background: #fff;
        }

        .btn-primary {
            background: #2563eb;
            color: #fff;
            border-color: #2563eb;
        }

        @media print {
            .actions {
                display: none;
            }

            body {
                margin: 0;
            }

            .print-card {
                border: none;
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>

<body>
    <div class="print-card">
        <h1 class="title">Label Barcode Barang</h1>
        <p class="muted">Data diambil dari barang yang dipilih.</p>

        <p class="row"><span class="label">ID Barang:</span> {{ $barang->id_barang }}</p>
        <p class="row"><span class="label">Nama Barang:</span> {{ $barang->nama_barang }}</p>
        <p class="row"><span class="label">Harga:</span> Rp {{ number_format($barang->harga, 0, ',', '.') }}</p>

        <div class="barcode-wrap">
            @if ($barcodeSvg)
                {!! $barcodeSvg !!}
            @else
                <p class="muted">Library Picqer belum terpasang.</p>
            @endif
            <div class="barcode-text">{{ $barang->id_barang }}</div>
        </div>

        <div class="actions">
            <button class="btn btn-primary" onclick="window.print()">Print</button>
            <a class="btn" href="{{ route('barcode.index') }}">Kembali</a>
        </div>
    </div>
</body>

<script>
    (function() {
        const shouldAutoPrint = {{ request()->boolean('autoprint') ? 'true' : 'false' }};

        if (shouldAutoPrint) {
            window.addEventListener('load', function() {
                setTimeout(function() {
                    window.print();
                }, 150);
            });
        }
    })();
</script>

</html>
