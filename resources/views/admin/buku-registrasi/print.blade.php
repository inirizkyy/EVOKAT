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
            height: 95px;
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
            font-size: 13pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            color: #000;
        }
        .kop-text .h2-title {
            margin: 2px 0 0 0;
            font-size: 13pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            color: #000;
        }
        .kop-text .h3-title {
            margin: 2px 0 3px 0;
            font-size: 15pt;
            font-weight: bold;
            font-family: "Times New Roman", Times, serif;
            letter-spacing: 0.5px;
            color: #000;
        }
        .kop-text p {
            margin: 1px 0;
            font-size: 9.5pt;
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
            margin-bottom: 20px;
        }

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
            height: 120px;
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

    <table class="kop-surat">
        <tr>
            <td class="kop-logo">
                <img src="{{ asset('img/logo-pt.png') }}" alt="Logo PT Tanjungkarang">
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
            <td>{{ $reg->permohonan->nomor_sk ?? $reg->pemohon->nomor_sk ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal SK Advokat</td>
            <td class="divider">:</td>
            <td>{{ ($reg->permohonan->tanggal_sk ?? $reg->pemohon->tanggal_sk) ? \Carbon\Carbon::parse($reg->permohonan->tanggal_sk ?? $reg->pemohon->tanggal_sk)->translatedFormat('d F Y') : '-' }}</td>
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
            <td>
                @if($reg->saksi)
                    @php $saksiArray = explode(';', $reg->saksi); @endphp
                    1. {{ trim($saksiArray[0] ?? '-') }}<br>
                    2. {{ trim($saksiArray[1] ?? '-') }}
                @else
                    Belum Lengkap
                @endif
            </td>
        </tr>
    </table>

    <div class="clear"></div>

    <div class="footer-sig">
        <div class="footer-sig-space">
            Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
            Petugas Registrasi,
        </div>
        <div>.........................................</div>
    </div>
    <div class="clear"></div>

    <script>
        window.onload = function() {
            // window.print();
        }
    </script>
</body>
</html>
