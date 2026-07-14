<!DOCTYPE html>
<html>
<head>
    <title>Pemberitahuan Berkas Anggota Ditolak</title>
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
            <h3 style="text-align: center; font-size: 15px; text-transform: uppercase; letter-spacing: 0.5px; color: #721c24; margin-bottom: 4px;">
                PEMBERITAHUAN BERKAS ANGGOTA DITOLAK
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
            <p style="margin: 0 0 16px; color: #555; font-size: 13px;">dan Rekan <strong>{{ $pemohon->nama_lengkap }}</strong></p>

            <p style="margin: 0 0 16px; text-align: justify;">
                Dengan hormat,
            </p>
            <p style="margin: 0 0 16px; text-align: justify;">
                Permohonan sumpah advokat atas nama <strong>{{ $pemohon->nama_lengkap }}</strong> belum dapat diproses karena masih terdapat dokumen yang tidak valid.
            </p>

            {{-- Anggota Info --}}
            <div style="background: #fdf2f2; border-left: 5px solid #ec7063; border-radius: 4px; padding: 16px 20px; margin: 20px 0;">
                <p style="margin: 0 0 10px; font-weight: bold; color: #721c24;">Dokumen yang perlu diperbaiki:</p>
                <ul style="margin: 0 0 10px 20px; padding: 0; color: #721c24; font-weight: bold;">
                    @foreach($rejectedList as $item)
                        <li style="margin-bottom: 5px;">{{ $item }}</li>
                    @endforeach
                </ul>
            </div>

            <p style="margin: 0 0 16px; text-align: justify;">
                **Instruksi Perbaikan Berkas:**
                Silakan melakukan perbaikan dokumen dengan mengklik tautan di bawah ini atau mengakses menu pelacakan status permohonan EVOKAT dengan memasukkan nomor registrasi Anda, lalu pilih tombol **Koreksi Berkas** di sebelah nama anggota tersebut.
            </p>

            <div style="text-align: center; margin: 25px 0;">
                <a href="{{ url('/tracking') }}?nomor_permohonan={{ $permohonan->nomor_permohonan }}" style="background-color: #721c24; color: #ffffff; padding: 12px 28px; text-decoration: none; border-radius: 4px; font-weight: bold; display: inline-block; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">
                    Perbaiki Dokumen Anggota
                </a>
            </div>

            <p style="margin: 16px 0 12px; text-align: justify;">
                Setelah dokumen baru diunggah, tim petugas Pengadilan Tinggi akan melakukan verifikasi ulang terhadap kelengkapan berkas tersebut.
            </p>
            
            <p style="margin: 24px 0 24px; text-align: justify;">
                Demikian pemberitahuan ini kami sampaikan. Atas perhatian dan kerjasamanya, kami ucapkan terima kasih.
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
