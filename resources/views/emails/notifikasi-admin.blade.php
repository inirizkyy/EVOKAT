<!DOCTYPE html>
<html>
<head>
    <title>Notifikasi Permohonan Sumpah Advokat Baru</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="color: #2c3e50; border-bottom: 2px solid #3498db; padding-bottom: 10px;">Permohonan Sumpah Advokat Baru</h2>
        
        <p>Halo Admin,</p>
        
        <p>Terdapat satu permohonan pengambilan sumpah advokat baru yang masuk ke sistem dan menunggu untuk diverifikasi.</p>
        
        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9; width: 35%;"><strong>Nomor Registrasi</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd; font-weight: bold; color: #2980b9;">{{ $permohonan->nomor_permohonan }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Nama Pemohon</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $permohonan->pemohon->nama_lengkap }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><strong>NIK</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $permohonan->pemohon->nik }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Organisasi Advokat</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ $permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd; background-color: #f9f9f9;"><strong>Waktu Pengajuan</strong></td>
                <td style="padding: 8px; border: 1px solid #ddd;">{{ \Carbon\Carbon::parse($permohonan->created_at)->format('d F Y H:i:s') }} WIB</td>
            </tr>
        </table>

        <p>Silakan login ke panel admin EVOKAT untuk memeriksa kelengkapan dan keabsahan dokumen persyaratan pemohon tersebut.</p>
        
        <div style="text-align: center; margin-top: 30px;">
            <a href="{{ route('admin.permohonan.show', $permohonan->id) }}" style="background-color: #3498db; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; font-weight: bold;">Verifikasi Sekarang</a>
        </div>
        
        <br><br>
        <p style="font-size: 12px; color: #7f8c8d; border-top: 1px solid #eee; padding-top: 10px;">
            Email ini dikirim secara otomatis oleh Sistem EVOKAT. Mohon tidak membalas email ini.
        </p>
    </div>
</body>
</html>
