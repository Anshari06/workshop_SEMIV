<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sertifikat</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
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
        
        .certificate {
            min-height: auto !important;
            padding: 40px 50px 36px !important;
            page-break-inside: avoid;
        }
        
        h1 {
            font-size: 48px !important;
        }
        
        h2 {
            font-size: 38px !important;
        }
        
        h3 {
            font-size: 28px !important;
            margin: 10px 0 32px !important;
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

        .certificate {
            text-align: center;
            background: #ffffff;
            border: 10px solid #111827;
            outline: 2px solid #9ca3af;
            outline-offset: -16px;
            padding: 54px 60px 48px;
            min-height: 540px;
            position: relative;
        }

        .subtitle {
            font-size: 16px;
            letter-spacing: 5px;
            text-transform: uppercase;
            color: #6b7280;
            margin: 0 0 10px;
        }

        h1 {
            margin: 0;
            font-size: 52px;
            letter-spacing: 4px;
            color: #111827;
        }

        .intro {
            margin-top: 36px;
            margin-bottom: 8px;
            font-size: 20px;
            color: #374151;
        }

        h2 {
            margin: 8px 0 28px;
            font-size: 42px;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .desc {
            margin: 0;
            font-size: 18px;
            color: #4b5563;
        }

        h3 {
            margin: 12px 0 44px;
            font-size: 30px;
            color: #1f2937;
        }

        .meta {
            margin-top: 42px;
            width: 100%;
        }

        .date {
            font-size: 15px;
            color: #6b7280;
            margin-bottom: 26px;
        }

        .sign-wrap {
            width: 260px;
            margin-left: auto;
            margin-right: 14px;
            text-align: center;
        }

        .sign-line {
            border-top: 1px solid #111827;
            margin-bottom: 8px;
        }

        .sign-name {
            font-size: 15px;
            color: #111827;
        }

        .sign-role {
            font-size: 13px;
            color: #6b7280;
        }
    </style>
</head>
<body>
    @if (!($isPdf ?? false))
        <div class="actions">
            <a href="{{ url()->previous() }}">Back</a>
            <a href="{{ route('cetak.sertifikat.download') }}">Download</a>
        </div>
    @endif
    <div class="sheet">
        <div class="certificate">
            <p class="subtitle">Certificate of Appreciation</p>
            <h1>SERTIFIKAT</h1>

            <p class="intro">Diberikan kepada:</p>
            <h2>{{ $nama }}</h2>

            <p class="desc">Atas partisipasi dan kontribusinya dalam kegiatan</p>
            <h3>{{ $judul }}</h3>

            <div class="meta">
                <p class="date">Tanggal: {{ now()->translatedFormat('d F Y') }}</p>
                <div class="sign-wrap">
                    <div class="sign-line"></div>
                    <div class="sign-name">Panitia Workshop</div>
                    <div class="sign-role">Penyelenggara</div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>