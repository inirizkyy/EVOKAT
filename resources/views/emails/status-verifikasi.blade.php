<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Status Verifikasi Permohonan Sumpah Advokat</title>
    <meta charset="UTF-8">
</head>
<body style="font-family: 'Times New Roman', serif; line-height: 1.8; color: #222; background: #f5f5f5; margin:0; padding:0;">
    <div style="max-width: 680px; margin: 30px auto; background: #fff; border: 1px solid #ccc; border-radius: 6px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.07);">

        {{-- Header --}}
        <div style="background: #1a3c5e; padding: 24px 32px; text-align: center;">
            <h2 style="margin: 0; color: #fff; font-size: 18px; letter-spacing: 1px; text-transform: uppercase;">EVOKAT</h2>
            <p style="margin: 4px 0 0; color: #b3d4f0; font-size: 13px;">Sistem Pendaftaran Sumpah Advokat</p>
        </div>

        {{-- Judul Surat --}}
        <div style="padding: 20px 32px 0 32px; border-bottom: 2px solid #e0e0e0;">
            <h3 style="text-align: center; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; color: #1a3c5e; margin-bottom: 4px;">
                PEMBERITAHUAN STATUS PERMOHONAN
            </h3>
            <p style="text-align: center; font-size: 13px; color: #555; margin: 0 0 16px;">
                Nomor Registrasi: <strong>{{ $permohonan->nomor_permohonan }}</strong> &nbsp;|&nbsp;
                Tanggal: <strong>{{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}</strong>
            </p>
        </div>

        {{-- Isi Surat --}}
        <div style="padding: 24px 32px;">
            <p style="margin: 0 0 16px;">Yth.</p>
            <p style="margin: 0 0 4px; font-weight: bold;">Pimpinan {{ $permohonan->organisasi->nama_organisasi ?? 'Organisasi Advokat' }}</p>
            <p style="margin: 0 0 16px; color: #555; font-size: 13px;">Nomor SK: {{ $permohonan->nomor_sk }}</p>

            <p style="margin: 0 0 16px; text-align: justify;">
                Dengan hormat,
            </p>
            <p style="margin: 0 0 16px; text-align: justify;">
                Sehubungan dengan permohonan pengambilan Sumpah Advokat kolektif yang telah Anda ajukan melalui sistem EVOKAT dengan nomor registrasi <strong>{{ $permohonan->nomor_permohonan }}</strong>, bersama ini kami sampaikan bahwa status permohonan saat ini telah diperbarui menjadi:
            </p>

            {{-- Status Badge Section --}}
            <div style="text-align: center; margin: 20px 0;">
                @if($permohonan->status === 'Verifikasi Berkas Fisik')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #fcf3cf; border: 1px solid #f9e79f; color: #b7950b;">
                        Verifikasi Berkas Fisik
                    </span>
                @elseif($permohonan->status === 'Menentukan Jadwal Verifikasi')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460;">
                        Jadwal Verifikasi Fisik Ditentukan
                    </span>
                @elseif($permohonan->status === 'Menentukan Jadwal Sumpah')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460;">
                        Jadwal Sumpah Ditentukan
                    </span>
                @elseif($permohonan->status === 'Proses Pembuatan Surat')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #d6d8db; border: 1px solid #e2e3e5; color: #383d41;">
                        Proses Pembuatan Surat
                    </span>
                @elseif($permohonan->status === 'Surat Selesai')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724;">
                        Surat Selesai (Siap Diunduh)
                    </span>
                @elseif($permohonan->status === 'Selesai')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #1a3c5e; border: 1px solid #1a3c5e; color: #ffffff;">
                        Selesai
                    </span>
                @else
                    <span style="display: inline-block; padding: 10px 24px; font-size: 15px; font-weight: bold; border-radius: 30px; background-color: #e2e3e5; border: 1px solid #d6d8db; color: #383d41;">
                        {{ $permohonan->status }}
                    </span>
                @endif
            </div>

            {{-- Detail Jadwal Verifikasi Fisik --}}
            @if($permohonan->tanggal_verifikasi_fisik)
                <p style="margin: 0 0 12px; text-align: justify;">
                    Rincian jadwal penyerahan dan pencocokan berkas fisik asli pemohon adalah sebagai berikut:
                </p>
                <div style="background: #fdfaf2; border-left: 5px solid #d4ac0d; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tr>
                            <td style="padding: 5px 0; width: 38%; color: #555;">Hari / Tanggal Penyerahan</td>
                            <td style="padding: 5px 0; width: 4%; color: #555;">:</td>
                            <td style="padding: 5px 0; font-weight: bold;">
                                {{ $permohonan->hari_verifikasi_fisik ?? '-' }}, 
                                {{ \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->translatedFormat('d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; color: #555; vertical-align: top;">Lokasi Penyerahan</td>
                            <td style="padding: 5px 0; color: #555; vertical-align: top;">:</td>
                            <td style="padding: 5px 0; font-weight: bold;">PTSP Pengadilan Tinggi Tanjungkarang</td>
                        </tr>
                    </table>
                </div>
            @endif

            {{-- Detail Jadwal Sumpah --}}
            @if(in_array($permohonan->status, ['Menentukan Jadwal Sumpah', 'Proses Pembuatan Surat', 'Surat Selesai', 'Selesai']) && $permohonan->jadwalSumpah)
                <p style="margin: 0 0 12px; text-align: justify;">
                    Rincian jadwal pelaksanaan Pengambilan Sumpah Advokat adalah sebagai berikut:
                </p>
                <div style="background: #f4fbf4; border-left: 5px solid #2ecc71; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tr>
                            <td style="padding: 5px 0; width: 30%; color: #555;">Hari / Tanggal</td>
                            <td style="padding: 5px 0; width: 4%; color: #555;">:</td>
                            <td style="padding: 5px 0; font-weight: bold;">
                                {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; color: #555;">Waktu / Pukul</td>
                            <td style="padding: 5px 0; color: #555;">:</td>
                            <td style="padding: 5px 0; font-weight: bold;">
                                {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->jam)->format('H:i') }} WIB
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 5px 0; color: #555; vertical-align: top;">Lokasi Pelaksanaan</td>
                            <td style="padding: 5px 0; color: #555; vertical-align: top;">:</td>
                            <td style="padding: 5px 0; font-weight: bold;">{{ $permohonan->jadwalSumpah->lokasi }}</td>
                        </tr>
                    </table>
                </div>
            @endif

            {{-- Catatan Admin --}}
            @if($permohonan->catatan)
                <div style="background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                    <strong style="color: #1a3c5e; font-size: 14px;">Catatan/Keterangan dari Petugas:</strong>
                    <p style="margin: 8px 0 0; color: #333; text-align: justify; font-size: 14px; white-space: pre-line;">{!! nl2br(e($permohonan->catatan)) !!}</p>
                </div>
            @endif

            {{-- Daftar Anggota Terdaftar --}}
            <h4 style="margin: 24px 0 12px; font-size: 15px; color: #1a3c5e; border-b: 1px solid #eee; padding-bottom: 6px;">Daftar Anggota Pemohon:</h4>
            <table style="width: 100%; border-collapse: collapse; font-size: 13px; margin-bottom: 24px;">
                <thead>
                    <tr style="background-color: #f2f2f2;">
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">No</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Nama Lengkap</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">NIK</th>
                        <th style="border: 1px solid #ddd; padding: 8px; text-align: center;">Verifikasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($permohonan->pemohons as $index => $pemohon)
                        <tr>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">{{ $index + 1 }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px; font-weight: bold;">{{ $pemohon->nama_lengkap }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center; font-family: monospace;">{{ $pemohon->nik }}</td>
                            <td style="border: 1px solid #ddd; padding: 8px; text-align: center;">
                                @if($pemohon->status_verifikasi == 'Disetujui')
                                    <span style="color: #27ae60; font-weight: bold;">Disetujui</span>
                                @elseif($pemohon->status_verifikasi == 'Ditolak')
                                    <span style="color: #c0392b; font-weight: bold;">Ditolak</span>
                                @else
                                    <span style="color: #7f8c8d;">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            {{-- Tombol Unduh Surat Final (Jika Status Surat Selesai / Selesai) --}}
            @if(in_array($permohonan->status, ['Surat Selesai', 'Selesai']))
                <p style="margin: 0 0 16px; text-align: justify;">
                    Surat pengantar sumpah organisasi Anda telah ditandatangani dan disetujui. Silakan unduh surat resmi bertanda tangan menggunakan tombol di bawah ini:
                </p>
                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ url('/tracking') }}?nomor_permohonan={{ $permohonan->nomor_permohonan }}" style="background-color: #1a3c5e; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        Unduh Surat Pengantar Resmi
                    </a>
                </div>
            @else
                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ url('/tracking') }}?nomor_permohonan={{ $permohonan->nomor_permohonan }}" style="background-color: #7f8c8d; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block;">
                        Pantau Status Pelacakan
                    </a>
                </div>
            @endif

            <p style="margin: 16px 0 12px; text-align: justify;">
                Anda dapat terus memantau riwayat dan perkembangan status permohonan melalui menu <strong>Cek Status (Tracking)</strong> pada website resmi EVOKAT menggunakan Nomor Registrasi Anda.
            </p>
            
            <p style="margin: 24px 0 24px; text-align: justify;">
                Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
            </p>

            <p style="margin: 24px 0 24px; text-align: justify; font-size: 12px; color: #666; font-style: italic; border-top: 1px dashed #ddd; padding-top: 15px;">
                *Catatan: Jika Anda tidak menemukan email pemberitahuan ini di folder Kotak Masuk (Inbox), silakan periksa folder Spam Anda.*
            </p>
        </div>

        {{-- Footer --}}
        <div style="background: #f0f4f8; padding: 12px 32px; border-top: 1px solid #e0e0e0; text-align: center;">
            <p style="margin: 0; font-size: 11px; color: #888;">
                Email ini dikirim secara otomatis oleh sistem EVOKAT. Mohon tidak membalas email ini.
            </p>
        </div>
    </div>
</body>
</html>
