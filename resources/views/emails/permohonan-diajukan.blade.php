<!DOCTYPE html>
<html>
<head>
    <title>Pendaftaran Permohonan Sumpah Advokat</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="text-align: center; color: #2c3e50;">Permohonan Sumpah Advokat</h2>
        
        <p>Yth. Bapak/Ibu <strong>{{ $permohonan->pemohon->nama_lengkap }}</strong>,</p>
        
        <p>Terima kasih telah mengajukan permohonan sumpah advokat. Permohonan Anda telah kami terima dan saat ini sedang dalam status <strong>Menunggu Verifikasi</strong> oleh petugas kami.</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;">
            <p style="margin: 0;"><strong>Nomor Registrasi Anda:</strong></p>
            <h3 style="margin: 10px 0; color: #2980b9;">{{ $permohonan->nomor_permohonan }}</h3>
        </div>

        <p>Silakan simpan Nomor Registrasi di atas dengan baik. Anda dapat menggunakan nomor ini untuk melacak status permohonan Anda melalui website kami di menu <strong>Cek Status (Tracking)</strong>.</p>
        
        <p>Kami akan menghubungi Anda lebih lanjut setelah dokumen Anda selesai diverifikasi.</p>
        
        <br>
        <p>Hormat kami,</p>
        <p><strong>Panitia Pengambilan Sumpah Advokat</strong></p>
    </div>
</body>
</html>
