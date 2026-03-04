<!DOCTYPE html>
<html>

<head>
    <style>
        @page {
            margin: 10mm;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            width: 20%;
            height: 30mm;
            border: 1px solid black;
            text-align: center;
            vertical-align: middle;
            font-size: 12px;
        }
    </style>
</head>

<body>

    <table>
        @php
            $totalSlot = 40;
            $totalData = count($dataBarang);
        @endphp

        @for ($i = 1; $i <= $totalSlot; $i++)
            @if (($i - 1) % 5 == 0)
                <tr>
            @endif

            <td>
                @php $index = $i - $startPosition; @endphp

                @if ($i >= $startPosition && $index >= 0 && $index < $totalData)
                    <strong>{{ $dataBarang[$index]->nama_barang }}</strong><br>
                    Rp {{ number_format($dataBarang[$index]->harga, 0, ',', '.') }}<br>
                    Created_at{{ $dataBarang[$index]->created_at->format('d-m-Y') }}
                @endif
            </td>

            @if ($i % 5 == 0)
                </tr>
            @endif
        @endfor

    </table>

</body>

</html>
