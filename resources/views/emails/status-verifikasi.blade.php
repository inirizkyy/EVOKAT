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
            <p style="margin: 0 0 4px; font-weight: bold;">{{ $permohonan->pemohon->nama_lengkap }}</p>
            <p style="margin: 0 0 16px; color: #555; font-size: 13px;">{{ $permohonan->pemohon->alamat ?? '' }}</p>

            <p style="margin: 0 0 16px; text-align: justify;">
                Dengan hormat,
            </p>
            <p style="margin: 0 0 16px; text-align: justify;">
                Sehubungan dengan permohonan pengambilan Sumpah Advokat yang telah Saudara/i ajukan melalui sistem e-Advokat (EVOKAT) PT Tanjungkarang dengan nomor registrasi <strong>{{ $permohonan->nomor_permohonan }}</strong>, bersama ini kami sampaikan bahwa status permohonan Saudara/i saat ini telah diperbarui menjadi:
            </p>

            {{-- Status Badge Section --}}
            <div style="text-align: center; margin: 20px 0;">
                @if($permohonan->status === 'Verifikasi Berkas Fisik')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 16px; font-weight: bold; border-radius: 30px; background-color: #fcf3cf; border: 1px solid #f9e79f; color: #b7950b;">
                        <i class="fa-solid fa-folder-open" style="margin-right: 8px;"></i>Verifikasi Berkas Fisik
                    </span>
                @elseif($permohonan->status === 'Disetujui')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 16px; font-weight: bold; border-radius: 30px; background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724;">
                        Disetujui
                    </span>
                @elseif($permohonan->status === 'Ditolak')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 16px; font-weight: bold; border-radius: 30px; background-color: #f8d7da; border: 1px solid #f5c6cb; color: #721c24;">
                        Ditolak
                    </span>
                @elseif($permohonan->status === 'Selesai')
                    <span style="display: inline-block; padding: 10px 24px; font-size: 16px; font-weight: bold; border-radius: 30px; background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460;">
                        Selesai
                    </span>
                @else
                    <span style="display: inline-block; padding: 10px 24px; font-size: 16px; font-weight: bold; border-radius: 30px; background-color: #e2e3e5; border: 1px solid #d6d8db; color: #383d41;">
                        {{ $permohonan->status }}
                    </span>
                @endif
            </div>

            {{-- Detail Berkas Fisik (Jika Status Verifikasi Berkas Fisik) --}}
            @if($permohonan->status === 'Verifikasi Berkas Fisik')
                <p style="margin: 0 0 12px; text-align: justify;">
                    Berkas administrasi digital Anda telah diverifikasi. Selanjutnya, Anda **diwajibkan** untuk menyerahkan berkas fisik persyaratan asli guna pencocokan data pada jadwal berikut:
                </p>
                <div style="background: #fdfaf2; border-left: 5px solid #d4ac0d; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
                        <tr>
                            <td style="padding: 5px 0; width: 38%; color: #555;">Hari / Tanggal Penyerahan</td>
                            <td style="padding: 5px 0; width: 4%; color: #555;">:</td>
                            <td style="padding: 5px 0; font-weight: bold;">
                                {{ $permohonan->hari_verifikasi_fisik ?? '-' }}, 
                                {{ $permohonan->tanggal_verifikasi_fisik ? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->translatedFormat('d F Y') : '-' }}
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

            {{-- Catatan Admin --}}
            @if($permohonan->catatan)
                <div style="background: #f9f9f9; border: 1px solid #e0e0e0; border-radius: 4px; padding: 16px 20px; margin: 0 0 20px;">
                    <strong style="color: #1a3c5e; font-size: 14px;">Catatan/Keterangan dari Petugas:</strong>
                    <p style="margin: 8px 0 0; color: #333; text-align: justify; font-size: 14px; white-space: pre-line;">{!! nl2br(e($permohonan->catatan)) !!}</p>
                </div>
            @endif

            {{-- Tombol Unduh Surat Final (Jika Status Disetujui) --}}
            @if($permohonan->status === 'Disetujui')
                <p style="margin: 0 0 16px; text-align: justify;">
                    Selamat, permohonan sumpah advokat Anda telah disetujui. Surat keterangan/dokumen rekomendasi final bertanda tangan saat ini sudah siap untuk diunduh. Silakan klik tombol di bawah ini:
                </p>
                <div style="text-align: center; margin: 25px 0;">
                    <a href="{{ url('/tracking') }}?nomor_permohonan={{ $permohonan->nomor_permohonan }}" style="background-color: #1a3c5e; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                        Unduh Surat Final
                    </a>
                </div>
            @endif

            <p style="margin: 16px 0 12px; text-align: justify;">
                Saudara/i dapat terus memantau riwayat dan perkembangan status permohonan melalui menu <strong>Cek Status (Tracking)</strong> pada website resmi EVOKAT menggunakan Nomor Registrasi Anda.
            </p>
            
            <p style="margin: 24px 0 24px; text-align: justify;">
                Demikian pemberitahuan ini kami sampaikan. Atas perhatian Saudara/i, kami ucapkan terima kasih.
            </p>

            <p style="margin: 0 0 4px;">Hormat kami,</p>
            <br>
            <p style="margin: 0; font-weight: bold;">Panitia Pengambilan Sumpah Advokat</p>
            <p style="margin: 0; font-size: 13px; color: #555;">EVOKAT — Sistem Pendaftaran Sumpah Advokat</p>
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
