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
        .header {
            text-align: center;
            border-bottom: 3px double #000;
            padding-bottom: 8px;
            margin-bottom: 25px;
        }
        .header h2 {
            margin: 0;
            font-size: 16pt;
            text-transform: uppercase;
        }
        .header h3 {
            margin: 5px 0 0 0;
            font-size: 14pt;
            font-weight: normal;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 10pt;
            font-style: italic;
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
        <div class="header">
            <h2>PENGADILAN TINGGI TANJUNGKARANG</h2>
            <h3>PANITIA PENDAFTARAN SUMPAH ADVOKAT</h3>
            <p>Jl. Cut Mutia No.42, Gulak Galik, Kec. Telukbetung Utara, Kota Bandar Lampung | Telp: (0721) 482431</p>
        </div>

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
