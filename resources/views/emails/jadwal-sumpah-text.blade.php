SURAT PEMBERITAHUAN JADWAL PELAKSANAAN SUMPAH ADVOKAT
Nomor: {{ $jadwal->permohonan->nomor_permohonan }}
Tanggal: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}

Yth.
Pengurus {{ $jadwal->permohonan->organisasi->nama_organisasi ?? $jadwal->permohonan->pemohon->nama_lengkap ?? 'Organisasi Advokat' }}

Dengan hormat,

Sehubungan dengan permohonan pengambilan Sumpah Advokat yang telah diajukan dengan nomor registrasi {{ $jadwal->permohonan->nomor_permohonan }}, dengan ini kami sampaikan bahwa berkas permohonan telah dinyatakan lengkap dan memenuhi syarat.

Anggota pemohon dijadwalkan untuk mengikuti Pengambilan Sumpah Advokat pada:

  Hari / Tanggal : {{ \Carbon\Carbon::parse($jadwal->tanggal)->locale('id')->translatedFormat('l, d F Y') }}
  Pukul          : {{ \Carbon\Carbon::parse($jadwal->jam)->format('H:i') }} WIB
  Tempat / Lokasi: {{ $jadwal->lokasi }}
@if($jadwal->keterangan)
  Catatan        : {{ $jadwal->keterangan }}
@endif

DATA PERMOHONAN:
  No. Permohonan    : {{ $jadwal->permohonan->nomor_permohonan }}
  Organisasi Advokat: {{ $jadwal->permohonan->organisasi->nama_organisasi ?? $jadwal->permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}
  Jumlah Anggota    : {{ $jadwal->permohonan->pemohons->count() ?? 1 }} Orang

Mohon hadir tepat waktu dengan membawa berkas-berkas asli yang disyaratkan serta mematuhi protokol dan tata tertib persidangan (antara lain: mengenakan pakaian Toga beserta atribut sesuai ketentuan).

Demikian surat pemberitahuan ini kami sampaikan. Atas perhatian dan kehadiran Saudara/i, kami ucapkan terima kasih.

⚠️ Jika email ini tidak masuk di Inbox, silakan periksa folder Spam atau Promosi.

Hormat kami,

Panitia Pengambilan Sumpah Advokat
EVOKAT — Sistem Pendaftaran Sumpah Advokat

---
Email ini dikirim secara otomatis oleh sistem EVOKAT. Mohon tidak membalas email ini.
