Yth. Pengurus {{ $permohonan->organisasi->nama_organisasi ?? 'Organisasi Advokat' }},

Terima kasih, permohonan pengambilan sumpah advokat Anda telah berhasil kami terima dan didaftarkan ke sistem Pengadilan Tinggi Tanjungkarang.

Detail Pendaftaran:
- Nomor Registrasi: {{ $permohonan->nomor_permohonan }}
- Tanggal Pengajuan: {{ \Carbon\Carbon::parse($permohonan->created_at)->translatedFormat('d F Y H:i:s') }} WIB
- Status Saat Ini: {{ $permohonan->status }}

Gunakan Nomor Registrasi di atas untuk mengecek status permohonan Anda secara berkala melalui menu Tracking di website kami.

URL Tracking: {{ url('/tracking') }}

⚠️ Jika email ini tidak masuk di Inbox, silakan periksa folder Spam atau Promosi.

Hormat kami,
Pengadilan Tinggi Tanjungkarang - EVOKAT
