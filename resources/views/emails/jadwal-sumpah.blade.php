<!DOCTYPE html>
<html>
<head>
    <title>Jadwal Pelaksanaan Sumpah Advokat</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <div style="max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px;">
        <h2 style="text-align: center; color: #27ae60;">Jadwal Sumpah Advokat</h2>
        
        <p>Yth. Bapak/Ibu <strong>{{ $jadwal->permohonan->pemohon->nama_lengkap }}</strong>,</p>
        
        <p>Selamat! Berkas permohonan sumpah advokat Anda (Nomor Registrasi: <strong>{{ $jadwal->permohonan->nomor_permohonan }}</strong>) telah disetujui. Berikut adalah jadwal pelaksanaan pengambilan sumpah advokat Anda:</p>
        
        <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #27ae60; margin: 20px 0;">
            <p style="margin: 5px 0;"><strong>Tanggal:</strong> {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}</p>
            <p style="margin: 5px 0;"><strong>Pukul:</strong> {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} WIB</p>
            <p style="margin: 5px 0;"><strong>Lokasi:</strong> {{ $jadwal->lokasi }}</p>
            @if($jadwal->keterangan)
            <p style="margin: 5px 0;"><strong>Keterangan Khusus:</strong> {{ $jadwal->keterangan }}</p>
            @endif
        </div>

        <p>Mohon hadir tepat waktu dengan membawa berkas-berkas asli yang disyaratkan serta mematuhi protokol dan tata tertib persidangan (seperti memakai atribut Toga, dsb).</p>
        
        <br>
        <p>Hormat kami,</p>
        <p><strong>Panitia Pengambilan Sumpah Advokat</strong></p>
    </div>
</body>
</html>
