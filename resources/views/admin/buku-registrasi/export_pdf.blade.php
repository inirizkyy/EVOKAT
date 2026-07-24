<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Buku Registrasi Advokat</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 9pt;
            color: #333;
            line-height: 1.3;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .kop-surat {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 5px;
        }
        .kop-surat td {
            vertical-align: middle;
        }
        .kop-logo {
            width: 15%;
            text-align: center;
        }
        .kop-logo img {
            height: 105px;
            width: auto;
        }
        .kop-text {
            width: 70%;
            text-align: center;
        }
        .kop-space {
            width: 15%;
        }
        .kop-text .h1-title {
            margin: 0;
            font-size: 14pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            color: #000;
        }
        .kop-text .h2-title {
            margin: 2px 0 0 0;
            font-size: 14pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            color: #000;
        }
        .kop-text .h3-title {
            margin: 2px 0 3px 0;
            font-size: 16pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            color: #000;
        }
        .kop-text p {
            margin: 1px 0;
            font-size: 10pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            color: #000;
        }
        .kop-text a {
            color: #0000ff;
            text-decoration: underline;
        }
        
        .line-thick {
            border-bottom: 3px solid #000;
            margin-bottom: 2px;
        }
        .line-thin {
            border-bottom: 1px solid #000;
            margin-bottom: 15px;
        }

        .title { margin-bottom: 20px; font-size: 11pt; font-weight: bold; text-align: center; text-transform: uppercase; }
        
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data-table th, table.data-table td {
            border: 1px solid #666;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        table.data-table th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
        }
        
        .footer-date {
            margin-top: 30px;
            text-align: right;
            font-size: 9pt;
        }
        .signature-block {
            margin-top: 50px;
            float: right;
            width: 200px;
            text-align: center;
        }
        .clear { clear: both; }
    </style>
</head>
<body>
    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                @if(file_exists(public_path('img/logo-pt.png')))
                    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('img/logo-pt.png'))) }}" alt="Logo PT Tanjungkarang">
                @endif
            </td>
            <td class="kop-text">
                <div class="h1-title">MAHKAMAH AGUNG REPUBLIK INDONESIA</div>
                <div class="h2-title">DIREKTORAT JENDERAL BADAN PERADILAN UMUM</div>
                <div class="h3-title">PENGADILAN TINGGI TANJUNGKARANG</div>
                <p>Jalan Cut Mutia No. 42, Teluk Betung Utara Kota Bandar Lampung, (0721) 481286</p>
                <p>Provinsi Lampung Kode Pos – 35214</p>
                <p>www.pt-tanjungkarang.go.id, <a href="mailto:admin@pt-tanjungkarang.go.id">admin@pt-tanjungkarang.go.id</a></p>
            </td>
            <td class="kop-space"></td>
        </tr>
    </table>
    <div class="line-thick"></div>
    <div class="line-thin"></div>

    <div class="title">
        LAPORAN BUKU REGISTRASI ADVOKAT
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 15%;">Nama &amp; NIK</th>
                <th style="width: 20%;">Alamat</th>
                <th style="width: 12%;">Organisasi</th>
                <th style="width: 18%;">SK Advokat</th>
                <th style="width: 18%;">BAS &amp; Sumpah</th>
                <th style="width: 14%;">Saksi-Saksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($registrasi as $index => $item)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>
                    <span class="font-bold">{{ $item->pemohon->nama_lengkap ?? '-' }}</span><br>
                    <span style="font-size: 8pt; color: #555;">NIK: {{ $item->pemohon->nik ?? '-' }}</span>
                </td>
                <td>{{ $item->pemohon->alamat ?? '-' }}</td>
                <td class="text-center">{{ $item->pemohon->organisasi->nama_organisasi ?? '-' }}</td>
                <td>
                    No: {{ $item->permohonan->nomor_sk ?? $item->pemohon->nomor_sk ?? '-' }}<br>
                    Tgl: {{ ($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk) ? \Carbon\Carbon::parse($item->permohonan->tanggal_sk ?? $item->pemohon->tanggal_sk)->translatedFormat('d M Y') : '-' }}
                </td>
                <td>
                    @if($item->nomor_bas)
                        No BAS: {{ $item->nomor_bas }}<br>
                        Tgl Sumpah: {{ \Carbon\Carbon::parse($item->tanggal_disumpah)->translatedFormat('d M Y') }}<br>
                        Penyumpah: {{ $item->ketua_pengadilan_tinggi }}
                    @else
                        <span style="color: #d35400; font-style: italic;">Belum Lengkap</span>
                    @endif
                </td>
                <td>
                    @if($item->saksi)
                        @php $saksiArray = explode(';', $item->saksi); @endphp
                        1. {{ trim($saksiArray[0] ?? '-') }}<br>
                        2. {{ trim($saksiArray[1] ?? '-') }}
                    @else
                        -
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center" style="padding: 15px; font-style: italic;">Tidak ada data registrasi advokat.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer-date">
        Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
        Mengetahui,<br>
        Panitia Pendaftaran Sumpah Advokat PT Tanjungkarang
    </div>
</body>
</html>
