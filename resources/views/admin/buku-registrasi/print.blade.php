<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Buku Registrasi - {{ $reg->pemohon->nama_lengkap }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #000;
            margin: 30px;
        }
        .text-center { text-align: center; }
        .font-bold { font-weight: bold; }
        .header {
            margin-bottom: 30px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }
        .header h2 { margin: 0; font-size: 14pt; }
        .header h3 { margin: 5px 0 0; font-size: 12pt; }
        .header p { margin: 5px 0 0; font-size: 9pt; font-style: italic; }
        .title { margin-bottom: 25px; font-size: 12pt; font-weight: bold; text-decoration: underline; text-transform: uppercase; }
        
        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .main-table td {
            padding: 8px 4px;
            vertical-align: top;
        }
        .main-table .label {
            width: 30%;
            color: #555;
            font-weight: bold;
        }
        .main-table .divider {
            width: 3%;
        }
        .photo-box {
            border: 1px solid #ccc;
            padding: 5px;
            width: 120px;
            height: 160px;
            object-fit: cover;
        }
        
        .footer-sig {
            margin-top: 40px;
            float: right;
            width: 250px;
            text-align: center;
        }
        .footer-sig-space {
            margin-bottom: 60px;
        }
        .clear { clear: both; }
        
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body>
    <div class="no-print" style="margin-bottom: 20px; text-align: right;">
        <button onclick="window.print()" style="padding: 8px 15px; font-weight: bold; background: #1a3c5e; color: #fff; border: 0; border-radius: 4px; cursor: pointer;">
            Cetak Dokumen
        </button>
    </div>

    <div class="header text-center">
        <h2>PENGADILAN TINGGI TANJUNGKARANG</h2>
        <h3>BUKU REGISTRASI ADVOKAT</h3>
        <p>Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung</p>
    </div>

    <div class="title text-center">
        LEMBAR REGISTRASI ANGGOTA ADVOKAT
    </div>

    <div style="float: right; margin-left: 20px;">
        @if($reg->pemohon && $reg->pemohon->foto)
            <img src="{{ asset('storage/' . $reg->pemohon->foto) }}" class="photo-box" alt="Foto Pemohon">
        @else
            <div class="photo-box" style="display: flex; align-items: center; justify-content: center; font-size: 8pt; color: #aaa; text-align: center;">
                FOTO 3X4
            </div>
        @endif
    </div>

    <table class="main-table">
        <tr>
            <td class="label">Nama Lengkap &amp; Gelar</td>
            <td class="divider">:</td>
            <td class="font-bold">{{ $reg->pemohon->nama_lengkap }}</td>
        </tr>
        <tr>
            <td class="label">NIK (Nomor Induk Kependudukan)</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->nik }}</td>
        </tr>
        <tr>
            <td class="label">Tempat, Tanggal Lahir</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($reg->pemohon->tanggal_lahir)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Kelamin</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</td>
        </tr>
        <tr>
            <td class="label">Alamat Lengkap</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->alamat }}</td>
        </tr>
        <tr>
            <td class="label">Email</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->email }}</td>
        </tr>
        <tr>
            <td class="label">No. HP / WhatsApp</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->no_hp }}</td>
        </tr>
        <tr>
            <td class="label">Organisasi Advokat</td>
            <td class="divider">:</td>
            <td class="font-bold">{{ $reg->pemohon->organisasi->nama_organisasi ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nomor SK Advokat</td>
            <td class="divider">:</td>
            <td>{{ $reg->pemohon->nomor_sk }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal SK Advokat</td>
            <td class="divider">:</td>
            <td>{{ \Carbon\Carbon::parse($reg->pemohon->tanggal_sk)->translatedFormat('d F Y') }}</td>
        </tr>
        <tr>
            <td colspan="3" style="border-top: 1px solid #ccc; padding-top: 15px; margin-top: 10px; font-weight: bold; color: #1a3c5e;">DATA SIDANG SUMPAH &amp; BAS</td>
        </tr>
        <tr>
            <td class="label">Nomor Berita Acara Sumpah (BAS)</td>
            <td class="divider">:</td>
            <td class="font-bold">{{ $reg->nomor_bas ?? 'Belum Lengkap' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Pelaksanaan Sumpah</td>
            <td class="divider">:</td>
            <td>{{ $reg->tanggal_disumpah ? \Carbon\Carbon::parse($reg->tanggal_disumpah)->translatedFormat('d F Y') : 'Belum Lengkap' }}</td>
        </tr>
        <tr>
            <td class="label">Ketua Pengadilan Tinggi Menyumpah</td>
            <td class="divider">:</td>
            <td>{{ $reg->ketua_pengadilan_tinggi ?? 'Belum Lengkap' }}</td>
        </tr>
        <tr>
            <td class="label">Saksi-Saksi</td>
            <td class="divider">:</td>
            <td>{{ $reg->saksi ?? 'Belum Lengkap' }}</td>
        </tr>
    </table>

    <div class="clear"></div>

    <div class="footer-sig">
        <div class="footer-sig-space">
            Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Petugas Registrasi,
        </div>
        <div class="font-bold" style="text-decoration: underline;">
            {{ Auth::user()->name ?? 'Administrator' }}
        </div>
        <div>NIP. .........................................</div>
    </div>
    <div class="clear"></div>

    <script>
        window.onload = function() {
            // window.print();
        }
    </script>
</body>
</html>
