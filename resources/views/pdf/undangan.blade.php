<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Undangan</title>
    <style>
        @page {
            margin: 0;
            size: A4 portrait;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Georgia, "Times New Roman", serif;
            color: #1f2937;
            background: #f8fafc;
        }

        @if ($isPdf ?? false)
        body {
            background: #ffffff;
        }
        
        .sheet {
            padding: 0 !important;
            min-height: auto !important;
        }
        
        .letter {
            min-height: auto !important;
            padding: 36px 46px !important;
            page-break-inside: avoid;
        }
        
        .header h2 {
            font-size: 24px !important;
        }
        
        .title {
            font-size: 22px !important;
        }
        
        .content {
            font-size: 16px !important;
        }
        
        .topic strong {
            font-size: 18px !important;
        }
        @endif

        .actions {
            text-align: center;
            margin: 16px 0 14px;
        }

        .actions a {
            display: inline-block;
            padding: 10px 18px;
            margin: 0 6px;
            background: #111827;
            color: #fff;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        .sheet {
            width: 100%;
            min-height: calc(100vh - 30px);
            padding: 18px;
        }

        .letter {
            background: #ffffff;
            border: 8px solid #111827;
            outline: 2px solid #9ca3af;
            outline-offset: -14px;
            padding: 46px 56px;
            min-height: 640px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #111827;
            padding-bottom: 14px;
            margin-bottom: 28px;
        }

        .header {
            margin-top: 0;
        }

        .header h2 {
            margin: 0;
            font-size: 28px;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            color: #111827;
        }

        .header p {
            margin: 8px 0 0;
            color: #4b5563;
            font-size: 16px;
        }

        .number {
            text-align: right;
            margin-bottom: 28px;
            font-size: 14px;
            color: #6b7280;
        }

        .title {
            text-align: center;
            font-size: 26px;
            letter-spacing: 1px;
            margin: 8px 0 32px;
            color: #111827;
            text-transform: uppercase;
        }

        .content {
            font-size: 18px;
            line-height: 1.8;
            color: #374151;
        }

        .topic {
            margin: 20px 0;
            padding: 18px;
            border-left: 4px solid #1f2937;
            background: #f9fafb;
        }

        .topic strong {
            display: block;
            font-size: 20px;
            margin-bottom: 8px;
            color: #111827;
        }

        .closing {
            margin-top: 26px;
        }

        .signature {
            margin-top: 54px;
            text-align: right;
        }

        .signature .place-date {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 64px;
        }

        .signature .name {
            font-size: 16px;
            color: #111827;
            font-weight: 600;
        }

        .signature .role {
            font-size: 14px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    @if (!($isPdf ?? false))
        <div class="actions">
            <a href="{{ url()->previous() }}">Back</a>
            <a href="{{ route('cetak.undangan.download') }}">Download</a>
        </div>
    @endif

    <div class="sheet">
        <div class="letter">
            <div class="header">
                <h2>Fakultas Teknologi Informasi</h2>
                <p>Universitas Contoh</p>
            </div>

            <div class="number">
                No: 001/FTI/UND/{{ now()->format('Y') }}
            </div>

            <h3 class="title">Surat Undangan</h3>

            <div class="content">
                <p>Dengan hormat,</p>
                <p>
                    Sehubungan dengan penyelenggaraan kegiatan akademik dan pengembangan kompetensi,
                    kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara berikut:
                </p>

                <div class="topic">
                    <strong>{{ $judul }}</strong>
                    <span>Tanggal Pelaksanaan: {{ $tanggal }}</span>
                </div>

                <p>
                    Kehadiran Anda sangat kami harapkan sebagai bentuk dukungan terhadap keberhasilan kegiatan ini.
                </p>
                <p class="closing">Demikian undangan ini kami sampaikan. Atas perhatian dan kehadirannya, kami ucapkan terima kasih.</p>
            </div>

            <div class="signature">
                <div class="place-date">Bandung, {{ $tanggal }}</div>
                <div class="name">Panitia Penyelenggara</div>
                <div class="role">Fakultas Teknologi Informasi</div>
            </div>
        </div>
    </div>
</body>
</html>