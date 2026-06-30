Yth. Bapak/Ibu {{ $permohonan->pemohon->nama_lengkap }},

Terima kasih, permohonan pengambilan sumpah advokat Anda telah berhasil kami terima dan saat ini sedang dalam proses antrean verifikasi oleh tim admin.

Detail Pendaftaran:
- Nomor Registrasi: {{ $permohonan->nomor_permohonan }}
- NIK: {{ $permohonan->pemohon->nik }}
- Tanggal Pengajuan: {{ \Carbon\Carbon::parse($permohonan->created_at)->format('d F Y H:i:s') }} WIB
- Status Saat Ini: Menunggu Verifikasi

Gunakan Nomor Registrasi di atas untuk mengecek status permohonan Anda secara berkala melalui menu Tracking di website kami.

URL Tracking: {{ url('/tracking') }}

Hormat kami,
Panitia Pengambilan Sumpah Advokat
EVOKAT
