Pemberitahuan Status Permohonan Sumpah Advokat
Nomor Registrasi: {{ $permohonan->nomor_permohonan }}
Tanggal: {{ \Carbon\Carbon::now()->locale('id')->translatedFormat('d F Y') }}

Yth. {{ $permohonan->pemohon->nama_lengkap }}
Di tempat

Dengan hormat,
Sehubungan dengan permohonan pengambilan Sumpah Advokat yang telah Saudara/i ajukan dengan nomor registrasi {{ $permohonan->nomor_permohonan }}, bersama ini kami sampaikan bahwa status permohonan Saudara/i saat ini telah diperbarui menjadi:

[{{ $permohonan->status }}]

@if($permohonan->status === 'Verifikasi Berkas Fisik')
Berkas administrasi digital Anda telah diverifikasi. Selanjutnya, Anda diwajibkan untuk menyerahkan berkas fisik persyaratan asli guna pencocokan data pada jadwal berikut:
- Hari/Tanggal Penyerahan: {{ $permohonan->hari_verifikasi_fisik ?? '-' }}, {{ $permohonan->tanggal_verifikasi_fisik ? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->translatedFormat('d F Y') : '-' }}
- Lokasi Penyerahan: PTSP Pengadilan Tinggi Tanjungkarang
@endif

@if($permohonan->catatan)
Catatan/Keterangan dari Petugas:
{{ $permohonan->catatan }}
@endif

@if($permohonan->status === 'Disetujui')
Selamat, permohonan sumpah advokat Anda telah disetujui. Surat keterangan/dokumen rekomendasi final bertanda tangan saat ini sudah siap untuk diunduh.
Silakan akses tautan berikut untuk men-download berkas final Anda:
{{ url('/tracking') }}?nomor_permohonan={{ $permohonan->nomor_permohonan }}
@endif

Saudara/i dapat terus memantau riwayat dan perkembangan status permohonan melalui menu Cek Status (Tracking) pada website resmi EVOKAT menggunakan Nomor Registrasi Anda.

Demikian pemberitahuan ini kami sampaikan. Atas perhatian Saudara/i, kami ucapkan terima kasih.

Hormat kami,
Panitia Pengambilan Sumpah Advokat
EVOKAT — Sistem Pendaftaran Sumpah Advokat
