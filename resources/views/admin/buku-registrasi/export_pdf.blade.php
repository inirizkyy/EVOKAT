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
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 8px;
            text-align: center;
        }
        .header h2 { margin: 0; font-size: 13pt; }
        .header h3 { margin: 3px 0 0; font-size: 11pt; }
        .header p { margin: 3px 0 0; font-size: 8pt; font-style: italic; color: #555; }
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
    <div class="header">
        <h2>PENGADILAN TINGGI TANJUNGKARANG</h2>
        <h3>LAPORAN BUKU REGISTRASI ADVOKAT</h3>
        <p>Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung</p>
    </div>

    <div class="title">
        DAFTAR REGISTER SUMPAH ANGGOTA ADVOKAT
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
                    No: {{ $item->pemohon->nomor_sk ?? '-' }}<br>
                    Tgl: {{ $item->pemohon->tanggal_sk ? \Carbon\Carbon::parse($item->pemohon->tanggal_sk)->translatedFormat('d M Y') : '-' }}
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
                <td>{{ $item->saksi ?? '-' }}</td>
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
