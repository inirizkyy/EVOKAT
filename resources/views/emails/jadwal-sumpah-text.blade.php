Yth. Bapak/Ibu {{ $jadwal->permohonan->pemohon->nama_lengkap }},

Selamat! Berkas permohonan sumpah advokat Anda (Nomor Registrasi: {{ $jadwal->permohonan->nomor_permohonan }}) telah disetujui. Berikut adalah jadwal pelaksanaan pengambilan sumpah advokat Anda:

Tanggal: {{ \Carbon\Carbon::parse($jadwal->tanggal)->format('d F Y') }}
Pukul: {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} WIB
Lokasi: {{ $jadwal->lokasi }}
@if($jadwal->keterangan)
Keterangan Khusus: {{ $jadwal->keterangan }}
@endif

Mohon hadir tepat waktu dengan membawa berkas-berkas asli yang disyaratkan serta mematuhi protokol dan tata tertib persidangan.

Hormat kami,
Panitia Pengambilan Sumpah Advokat
EVOKAT
