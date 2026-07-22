<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Pengecekan Berkas Fisik</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="text-align: center; color: #2c3e50;">Jadwal Pengecekan Berkas Fisik</h2>
        
        <p>Yth. Pengurus <strong>{{ $permohonan->organisasi->nama_organisasi ?? 'Organisasi Advokat' }}</strong>,</p>
        
        <p>Pemberitahuan bahwa jadwal penyerahan dan pengecekan berkas fisik asli untuk permohonan sumpah advokat Anda telah ditentukan.</p>
        
        <div style="background-color: #f0f4ff; padding: 15px; border-left: 4px solid #2980b9; margin: 20px 0;">
            <p style="margin: 0 0 5px 0;"><strong>Nomor Registrasi:</strong> <span style="font-family: monospace; font-size: 16px; color: #2980b9;">{{ $permohonan->nomor_permohonan }}</span></p>
            <p style="margin: 5px 0;"><strong>Hari:</strong> {{ $permohonan->hari_verifikasi_fisik ?? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->isoFormat('dddd') }}</p>
            <p style="margin: 5px 0 0 0;"><strong>Tanggal Pengecekan:</strong> {{ \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->translatedFormat('d F Y') }}</p>
        </div>

        <p>Mohon perwakilan organisasi hadir di Pengadilan Tinggi Tanjungkarang sesuai jadwal tersebut dengan membawa seluruh <strong>berkas fisik asli</strong> milik anggota pemohon yang didaftarkan.</p>
        
        <p style="font-size: 12px; color: #888; text-align: center; margin-top: 20px;">
            ⚠️ Jika email ini tidak masuk di Inbox, silakan periksa folder <strong>Spam</strong> atau <strong>Promosi</strong>.
        </p>
        
        <br>
        <p>Hormat kami,</p>
        <p><strong>Pengadilan Tinggi Tanjungkarang - EVOKAT</strong></p>
    </div>
</body>
</html>
