Halo Admin,

Terdapat satu permohonan pengambilan sumpah advokat baru yang masuk ke sistem dan menunggu untuk diverifikasi.

Detail Permohonan:
- Nomor Registrasi: {{ $permohonan->nomor_permohonan }}
- Nama Pemohon: {{ $permohonan->pemohon->nama_lengkap }}
- NIK: {{ $permohonan->pemohon->nik }}
- Organisasi Advokat: {{ $permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}
- Waktu Pengajuan: {{ \Carbon\Carbon::parse($permohonan->created_at)->translatedFormat('d F Y H:i:s') }} WIB

Silakan login ke panel admin EVOKAT untuk memeriksa kelengkapan dan keabsahan dokumen persyaratan pemohon tersebut.

URL Verifikasi: {{ route('admin.permohonan.show', $permohonan->id) }}

Email ini dikirim secara otomatis oleh Sistem EVOKAT. Mohon tidak membalas email ini.
