<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Permohonan Sumpah Advokat</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="text-align: center; color: #2c3e50;">Permohonan Sumpah Advokat</h2>
        
        <p>Yth. Pengurus <strong>{{ $permohonan->organisasi->nama_organisasi ?? 'Organisasi Advokat' }}</strong>,</p>
        
        <p>Terima kasih telah mengajukan permohonan sumpah advokat. Permohonan Anda telah berhasil didaftarkan ke sistem Pengadilan Tinggi Tanjungkarang.</p>
        
        <div style="background-color: #f0f4ff; padding: 15px; border-left: 4px solid #2980b9; margin: 20px 0; text-align: center;">
            <p style="margin: 0; font-size: 13px; color: #555;">Nomor Registrasi Anda:</p>
            <h2 style="margin: 8px 0; color: #2980b9; font-family: monospace; letter-spacing: 3px;">{{ $permohonan->nomor_permohonan }}</h2>
        </div>

        <p>Silakan simpan Nomor Registrasi di atas dengan baik. Anda dapat menggunakan nomor ini untuk melacak status permohonan Anda melalui menu <strong>Cek Status (Tracking)</strong> pada website kami.</p>
        
        <p>Tahap selanjutnya adalah melengkapi dokumen persyaratan masing-masing anggota pemohon hingga siap diverifikasi oleh petugas kami.</p>
        
        <p style="font-size: 12px; color: #888; text-align: center; margin-top: 20px;">
            ⚠️ Jika email ini tidak masuk di Inbox, silakan periksa folder <strong>Spam</strong> atau <strong>Promosi</strong>.
        </p>
        
        <br>
        <p>Hormat kami,</p>
        <p><strong>Pengadilan Tinggi Tanjungkarang - EVOKAT</strong></p>
    </div>
</body>
</html>
