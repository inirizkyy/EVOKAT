<!DOCTYPE html>
<html>
<head>
    <title>Surat Pengantar Sumpah - {{ $permohonan->nomor_permohonan }}</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #000;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            padding: 20px 40px;
            box-sizing: border-box;
        }
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
            margin-bottom: 20px;
        }
        .title {
            text-align: center;
            margin-bottom: 25px;
        }
        .title h4 {
            margin: 0;
            font-size: 13pt;
            text-decoration: underline;
            text-transform: uppercase;
        }
        .title p {
            margin: 5px 0 0 0;
            font-size: 11pt;
        }
        .content {
            text-align: justify;
            margin-bottom: 30px;
        }
        .content p {
            margin: 0 0 15px 0;
            text-indent: 40px;
        }
        .table-data {
            width: 85%;
            margin: 15px auto;
            border-collapse: collapse;
        }
        .table-data td {
            padding: 6px 4px;
            vertical-align: top;
        }
        .signature {
            float: right;
            width: 250px;
            margin-top: 40px;
            text-align: center;
        }
        .signature-title {
            margin-bottom: 75px;
        }
        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }
        .clear {
            clear: both;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Kop Surat -->
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

        <!-- Judul Surat -->
        <div class="title">
            <h4>SURAT PENGANTAR PENETAPAN PENGAMBILAN SUMPAH</h4>
            <p>Nomor: SPP-{{ $permohonan->nomor_permohonan }}</p>
        </div>

        <!-- Isi Surat -->
        <div class="content">
            <p>
                Berdasarkan hasil verifikasi berkas administratif secara online dan pengecekan kesesuaian berkas fisik yang telah dilaksanakan oleh Tim Panitia Pendaftaran Sumpah Advokat Pengadilan Tinggi Tanjungkarang, dengan ini menerangkan bahwa:
            </p>

            @if($permohonan->pemohons->count() > 1)
                <!-- Multiple Members Table -->
                <p style="margin-top: 10px; margin-bottom: 10px; text-indent: 0;">
                    Organisasi Advokat <strong>{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</strong> mengajukan anggota-anggota di bawah ini yang telah dinyatakan memenuhi syarat:
                </p>
                <table style="width: 100%; border-collapse: collapse; border: 1px solid #000; font-size: 11pt; margin-bottom: 15px;">
                    <thead>
                        <tr style="background-color: #f2f2f2;">
                            <th style="border: 1px solid #000; padding: 6px; text-align: center; width: 8%;">No</th>
                            <th style="border: 1px solid #000; padding: 6px; text-align: left; width: 32%;">Nama Lengkap</th>
                            <th style="border: 1px solid #000; padding: 6px; text-align: center; width: 25%;">NIK</th>
                            <th style="border: 1px solid #000; padding: 6px; text-align: left; width: 35%;">Tempat, Tanggal Lahir</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($permohonan->pemohons as $index => $pemohon)
                            <tr>
                                <td style="border: 1px solid #000; padding: 6px; text-align: center;">{{ $index + 1 }}</td>
                                <td style="border: 1px solid #000; padding: 6px; font-weight: bold;">{{ $pemohon->nama_lengkap }}</td>
                                <td style="border: 1px solid #000; padding: 6px; text-align: center; font-family: monospace;">{{ $pemohon->nik }}</td>
                                <td style="border: 1px solid #000; padding: 6px;">{{ $pemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($pemohon->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <table class="table-data" style="margin-top: 10px;">
                    <tr>
                        <td style="width: 35%;">Organisasi Advokat</td>
                        <td style="width: 5%;">:</td>
                        <td style="font-weight: bold;">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td>Nomor SK Advokat</td>
                        <td>:</td>
                        <td>{{ $permohonan->nomor_sk }}</td>
                    </tr>
                    <tr>
                        <td>Tanggal SK Advokat</td>
                        <td>:</td>
                        <td>{{ \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') }}</td>
                    </tr>
                </table>
            @else
                <!-- Single Member Details -->
                @php
                    $firstPemohon = $permohonan->pemohons->first() ?? $permohonan->pemohon;
                @endphp
                @if($firstPemohon)
                    <table class="table-data">
                        <tr>
                            <td style="width: 35%;">Nama Lengkap</td>
                            <td style="width: 5%;">:</td>
                            <td style="font-weight: bold;">{{ $firstPemohon->nama_lengkap }}</td>
                        </tr>
                        <tr>
                            <td>NIK</td>
                            <td>:</td>
                            <td>{{ $firstPemohon->nik }}</td>
                        </tr>
                        <tr>
                            <td>Tempat, Tanggal Lahir</td>
                            <td>:</td>
                            <td>{{ $firstPemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($firstPemohon->tanggal_lahir)->translatedFormat('d F Y') }}</td>
                        </tr>
                        <tr>
                            <td>Alamat Lengkap</td>
                            <td>:</td>
                            <td>{{ $firstPemohon->alamat ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Organisasi Advokat</td>
                            <td>:</td>
                            <td style="font-weight: bold;">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td>Nomor SK Advokat</td>
                            <td>:</td>
                            <td>{{ $permohonan->nomor_sk }}</td>
                        </tr>
                        <tr>
                            <td>Tanggal SK Advokat</td>
                            <td>:</td>
                            <td>{{ \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') }}</td>
                        </tr>
                    </table>
                @endif
            @endif

            <p style="margin-top: 20px;">
                Telah dinyatakan <strong>MEMENUHI SYARAT (MS)</strong> untuk diajukan dalam sidang terbuka pengambilan sumpah advokat pada Pengadilan Tinggi Tanjungkarang. Surat pengantar ini diterbitkan sebagai draf administratif untuk proses cetak dan penandatanganan basah oleh Ketua Pengadilan Tinggi / Pejabat yang berwenang.
            </p>
            <p>
                Demikian surat pengantar ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya.
            </p>
        </div>

        <!-- Tanda Tangan -->
        <div class="signature">
            <div class="signature-title">
                Bandar Lampung, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}<br>
                Ketua Pengadilan Tinggi Tanjungkarang,
            </div>
            <div class="signature-name">
                ......................................................
            </div>
            <div>NIP. .............................................</div>
        </div>
        <div class="clear"></div>
    </div>
</body>
</html>
