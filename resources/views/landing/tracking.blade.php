@extends('layouts.frontend')
@section('title', 'Cek Status Permohonan')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Cek Status Permohonan</h1>
            <p class="text-body-subtle text-lg">Pantau status permohonan sumpah advokat Anda secara real-time.</p>
        </div>
        
        <div class="bg-neutral-primary-soft rounded-[2.5rem] shadow-xl border border-border-default p-8 sm:p-12 lg:p-16 max-w-3xl mx-auto mb-12 relative overflow-hidden">
            <!-- Decorative Background Element -->
            <div class="absolute -right-10 -top-10 text-brand opacity-5 text-[15rem] pointer-events-none select-none rotate-12 z-0">
                <i class="fa-solid fa-magnifying-glass-chart"></i>
            </div>

            @php
                $displayNomor = session('nomor_permohonan') ?? request()->query('nomor_permohonan') ?? (isset($permohonan) ? $permohonan->nomor_permohonan : null);
            @endphp

            @if($displayNomor)
            {{-- Banner khusus setelah submit form / tracking permohonan --}}
            <div class="mb-8 rounded-2xl border border-border-success-subtle bg-success-soft relative z-10 overflow-hidden shadow-sm">
                <div class="px-6 pt-6 pb-4 flex items-center gap-3 border-b border-border-success-subtle">
                    <div class="w-9 h-9 rounded-full bg-white border border-border-success-subtle flex items-center justify-center text-fg-success-strong flex-shrink-0">
                        <i class="fa-solid fa-circle-check text-lg"></i>
                    </div>
                    <div>
                        <p class="font-bold text-fg-success-strong text-[15px]">Permohonan Berhasil Ditemukan / Diajukan!</p>
                        <p class="text-[13px] text-fg-success">
                            {{ session('success') ?? 'Permohonan berhasil terdaftar di Pengadilan Tinggi.' }}<br>
                            Simpan nomor registrasi Anda di bawah ini untuk keperluan pelacakan.
                        </p>
                    </div>
                </div>
                <div class="px-6 py-5 flex flex-col items-center gap-3">
                    <p class="text-[13px] font-medium text-fg-success uppercase tracking-widest">Nomor Registrasi Anda</p>
                    <div class="flex items-center gap-3">
                        <span id="nomorRegistrasi" class="font-mono text-2xl sm:text-3xl font-bold text-fg-success-strong tracking-widest">{{ $displayNomor }}</span>
                        <button type="button" onclick="copyNomor()" title="Salin nomor" class="w-9 h-9 rounded-full border border-border-success-subtle bg-white text-fg-success hover:text-fg-success-strong hover:shadow-md transition-all flex items-center justify-center flex-shrink-0">
                            <i id="copyIcon" class="fa-regular fa-copy text-base"></i>
                        </button>
                    </div>
                    <p class="text-[14px] sm:text-[15px] font-bold text-fg-success-strong text-center">Nomor Registrasi ini juga telah dikirimkan ke email.</p>
                    <p class="text-[12px] sm:text-[13px] text-fg-danger-strong text-center font-semibold mt-1">
                        <i class="fa-solid fa-circle-exclamation mr-1 text-fg-danger-strong"></i> Apabila email tidak masuk di Inbox utama, silakan periksa folder <strong>Spam</strong> atau <strong>Promosi</strong>.
                    </p>
                </div>
            </div>
            @elseif(session('success'))
            <div class="mb-8 p-4 rounded-2xl bg-success-soft border border-border-success-subtle text-fg-success-strong flex items-start gap-3 relative z-10">
                <i class="fa-solid fa-circle-check mt-1"></i>
                <p class="text-[14px]">{{ session('success') }}</p>
            </div>
            @endif

            @if(session('error'))
            <div class="mb-8 p-4 rounded-2xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong flex items-start gap-3 relative z-10">
                <i class="fa-solid fa-circle-xmark mt-1"></i>
                <p class="text-[14px]">{{ session('error') }}</p>
            </div>
            @endif

            <form action="{{ url('/tracking') }}" method="POST"
                  data-loading-title="Mencari Data Registrasi..."
                  data-loading-sub="Harap tunggu, sistem sedang mencari data permohonan Anda."
                  class="relative z-10">
                @csrf
                <div class="mb-8">
                    <label class="block text-[18px] font-['Playfair_Display'] font-bold text-heading mb-4 text-center">Masukkan Nomor Registrasi</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-6 flex items-center pointer-events-none transition-colors group-focus-within:text-brand text-body-subtle">
                            <i class="fa-solid fa-magnifying-glass text-xl"></i>
                        </div>
                        <input type="text" class="block w-full rounded-full border-2 border-border-default-medium bg-white shadow-lg text-[18px] sm:text-[20px] text-center text-heading py-5 px-16 focus:outline-none focus:border-brand focus:ring-4 focus:ring-brand/20 transition-all font-mono tracking-widest placeholder:tracking-normal placeholder:text-[16px]" name="nomor_permohonan" required placeholder="Contoh: ADV-20260701-0001" value="{{ $displayNomor ?? '' }}">
                    </div>
                </div>
                <div class="text-center mb-8">
                    <button type="submit" class="inline-flex justify-center items-center px-12 py-4 rounded-full text-[18px] font-bold bg-brand text-white shadow-md hover:shadow-xl hover:-translate-y-1 active:shadow-inset active:translate-y-0 transition-all duration-300 border border-brand-softer w-full sm:w-auto">
                        Lacak Status <i class="fa-solid fa-arrow-right-long ml-3"></i>
                    </button>
                </div>
            </form>

            <!-- Informasi Layanan Box -->
            <div class="p-6 sm:p-8 rounded-2xl bg-[#faf9f6] border border-border-default shadow-sm relative z-10 w-full" style="background-image: radial-gradient(#e5e7eb 1px, transparent 1px); background-size: 20px 20px;">
                <div class="flex items-start gap-4">
                    <div class="w-10 h-10 rounded-xl bg-white border border-border-default-medium shadow-sm flex items-center justify-center text-heading flex-shrink-0">
                        <i class="fa-solid fa-info"></i>
                    </div>
                    <div>
                        <h5 class="text-[16px] font-bold text-heading mb-3">Informasi Layanan</h5>
                        <ul class="space-y-3 text-[14.5px] font-medium text-heading leading-relaxed list-disc list-outside ml-4">
                            <li>Nomor registrasi dikirimkan melalui halaman konfirmasi dan email organisasi saat Anda selesai mengisi formulir.</li>
                            <li>Format registrasi: <code class="px-2 py-1 bg-white border border-border-default rounded-md text-[13px] font-mono font-bold text-brand shadow-sm">ADV-YYYYMMDD-XXXX</code></li>
                            <li>Proses verifikasi dan penerbitan izin memakan waktu estimasi <strong class="text-brand">3-5 hari kerja</strong>.</li>
                            <li>Jika berkas anggota <strong class="text-fg-danger">Ditolak</strong>, klik "Koreksi Dokumen" di sebelah namanya untuk memperbaiki berkas dan mengirim ulang.</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        @if(isset($permohonan))
        <div class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default p-6 sm:p-10 lg:p-12">
            <h3 class="font-['Playfair_Display'] text-2xl font-bold text-brand mb-8 pb-4 border-b border-border-default flex items-center gap-3">
                <span class="w-10 h-10 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-lg"><i class="fa-regular fa-file-lines"></i></span>
                Detail Permohonan
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Nomor Registrasi</span>
                        <span class="text-heading font-bold sm:w-3/5 font-mono">{{ $permohonan->nomor_permohonan }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Tanggal Pengajuan</span>
                        <span class="text-heading sm:w-3/5">{{ \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5 mt-1">Status Saat Ini</span>
                        <span class="sm:w-3/5">
                            @if($permohonan->status == 'Draft')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-neutral-secondary-medium border border-border-default text-heading shadow-sm">Draft</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Admin')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning shadow-sm"><i class="fa-regular fa-clock mr-1.5"></i>Verifikasi Admin</span>
                            @elseif(str_starts_with($permohonan->status, 'Menunggu Perbaikan Dokumen'))
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong shadow-sm"><i class="fa-solid fa-cloud-arrow-up mr-1.5"></i>Perbaikan Dokumen</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 1')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning shadow-sm"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 1</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 2')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning shadow-sm"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 2</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 3')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning shadow-sm"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 3</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 4')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning shadow-sm"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 4</span>
                            @elseif($permohonan->status == 'Menentukan Jadwal Berkas Fisik')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning shadow-sm"><i class="fa-solid fa-calendar-check mr-1.5"></i>Proses Pengecekan Berkas Fisik</span>
                            @elseif($permohonan->status == 'Menentukan Jadwal Sumpah')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info shadow-sm"><i class="fa-regular fa-calendar mr-1.5"></i>Jadwal Sumpah</span>
                            @elseif($permohonan->status == 'Proses Pembuatan Surat')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info shadow-sm"><i class="fa-solid fa-spinner fa-spin mr-1.5"></i>Pembuatan Surat</span>
                            @elseif($permohonan->status == 'Surat Selesai')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong shadow-sm"><i class="fa-solid fa-circle-check mr-1.5"></i>Surat Selesai</span>
                            @elseif($permohonan->status == 'Selesai')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-brand-softer border border-border-brand-subtle text-fg-brand-strong shadow-sm"><i class="fa-solid fa-flag-checkered mr-1.5"></i>Selesai</span>
                            @elseif($permohonan->status == 'Ditolak')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong shadow-sm"><i class="fa-solid fa-circle-xmark mr-1.5"></i>Ditolak</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info shadow-sm">{{ $permohonan->status }}</span>
                            @endif
                        </span>
                    </div>
                </div>
                <div class="space-y-4">
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Organisasi</span>
                        <span class="text-heading sm:w-3/5 font-semibold">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</span>
                    </div>
                    <div class="flex flex-col sm:flex-row border-b border-border-default border-dashed pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Nomor SK Pendirian</span>
                        <span class="text-heading sm:w-3/5">{{ $permohonan->nomor_sk }}</span>
                    </div>

                    <div class="flex flex-col sm:flex-row pb-3">
                        <span class="text-body-subtle font-medium sm:w-2/5">Email Organisasi</span>
                        <span class="text-heading sm:w-3/5">{{ $permohonan->email_organisasi }}</span>
                    </div>
                </div>
            </div>

            @if($permohonan->catatan && !in_array($permohonan->status, ['Proses Pembuatan Surat', 'Surat Selesai', 'Selesai']))
            <div class="mb-8 p-5 rounded-xl bg-warning-soft border border-border-warning-subtle text-heading flex flex-col gap-2">
                <h5 class="font-bold flex items-center gap-2 text-fg-warning"><i class="fa-solid fa-bell"></i> Catatan Verifikasi Permohonan:</h5>
                <p class="text-[14px] leading-relaxed ml-6 text-body">{{ $permohonan->catatan }}</p>
            </div>
            @endif

            @if(str_starts_with($permohonan->status, 'Menunggu Perbaikan Dokumen'))
            <div class="mb-8 p-6 rounded-2xl bg-danger-soft border border-border-danger-subtle text-fg-danger-strong relative overflow-hidden">
                <i class="fa-solid fa-circle-exclamation absolute -right-4 -bottom-4 text-8xl text-fg-danger opacity-10 pointer-events-none"></i>
                <h5 class="font-bold text-lg mb-2 flex items-center gap-2 text-fg-danger-strong">
                    <i class="fa-solid fa-triangle-exclamation mr-1"></i> Perbaikan Dokumen Diperlukan
                </h5>
                <p class="text-[14px] leading-relaxed mb-4">
                    Verifikator menemukan dokumen yang tidak valid atau butuh perbaikan. Silakan unggah ulang dokumen yang ditolak agar proses verifikasi dapat dilanjutkan.
                </p>
                <a href="{{ route('permohonan.dokumen-list', $permohonan->nomor_permohonan) }}" class="inline-flex items-center px-5 py-2.5 rounded-full text-xs font-bold text-white shadow-md hover:shadow-lg transition-all" style="background-color: #dc2626 !important; color: #ffffff !important; border: 1px solid #b91c1c !important;">
                    <i class="fa-solid fa-cloud-arrow-up mr-2"></i> Perbaiki &amp; Upload Dokumen
                </a>
            </div>
            @endif

            @if($permohonan->status == 'Verifikasi Berkas Fisik')
            <div class="mb-8 p-6 rounded-2xl bg-[#fff3cd] border border-[#ffeeba] text-[#856404] relative overflow-hidden">
                <i class="fa-solid fa-file-shield absolute -right-4 -bottom-4 text-8xl text-[#856404] opacity-10 pointer-events-none"></i>
                <h5 class="font-bold text-lg mb-2 flex items-center gap-2 text-[#856404]">
                    <i class="fa-solid fa-spinner fa-spin mr-1"></i> Pemeriksaan Berkas Sedang Diproses
                </h5>
                <p class="text-[14px] leading-relaxed">
                    Pegawai Pengadilan Tinggi sedang melakukan verifikasi dan pencocokan berkas (seperti Ijazah SH, Sertifikat UPA, KTP, Surat Magang, dll.) milik para pemohon. Pastikan berkas yang diserahkan telah sesuai dan lengkap.
                </p>
            </div>
            @endif

            @if(in_array($permohonan->status, ['Surat Selesai', 'Selesai']) && $permohonan->file_surat)
            <!-- Panel Surat Pengantar Final -->
            <div class="mb-8 p-6 rounded-2xl bg-success-soft border border-border-success-subtle text-fg-success-strong relative overflow-hidden">
                <i class="fa-solid fa-file-circle-check absolute -right-4 -bottom-4 text-8xl text-success opacity-10 pointer-events-none"></i>
                <h5 class="font-bold text-lg mb-4 flex items-center gap-2 text-fg-success-strong">
                    <i class="fa-solid fa-file-arrow-down"></i> Surat Balasan
                </h5>
                <div class="h-px w-full bg-border-success-subtle mb-4"></div>
                <p class="text-[14px] mb-4">Surat pengantar sumpah organisasi Anda telah ditandatangani dan disetujui. Silakan unduh surat pengantar resmi menggunakan tombol di bawah ini.</p>
                <a href="{{ route('permohonan.download-final', $permohonan->nomor_permohonan) }}" class="inline-flex items-center px-6 py-3 rounded-full text-[14px] font-bold bg-success text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-success-subtle">
                    <i class="fa-solid fa-download mr-2"></i> Unduh Surat Balasan
                </a>
            </div>
            @endif

            @if($permohonan->tanggal_verifikasi_fisik && !in_array($permohonan->status, ['Proses Pembuatan Surat', 'Surat Selesai', 'Selesai', 'Ditolak']))
            <div class="mb-8 p-6 rounded-2xl bg-warning-soft border border-border-warning-subtle text-heading relative overflow-hidden">
                <i class="fa-regular fa-folder-open absolute -right-4 -bottom-4 text-8xl text-fg-warning opacity-10 pointer-events-none"></i>
                <h5 class="font-bold text-lg mb-4 flex items-center gap-2 text-heading">
                    <i class="fa-solid fa-calendar-days text-fg-warning"></i> Jadwal Pengecekan Berkas Fisik
                </h5>
                <div class="h-px w-full bg-border-warning-subtle mb-4"></div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 relative z-10">
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Hari</span>
                        <span class="block text-xl font-bold text-heading">{{ $permohonan->hari_verifikasi_fisik ?? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->isoFormat('dddd') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Tanggal</span>
                        <span class="block text-xl font-bold text-heading">{{ \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->translatedFormat('d F Y') }}</span>
                    </div>
                </div>
                <p class="text-[13px] mt-4 opacity-80 relative z-10 text-body">Perwakilan Organisasi / Anggota yang bersangkutan wajib datang membawa seluruh berkas fisik asli sesuai jadwal di atas.</p>
            </div>
            @endif

            @if(in_array($permohonan->status, ['Surat Selesai', 'Selesai']) && $permohonan->file_surat && $permohonan->jadwalSumpah)
            <div class="mb-10 p-6 rounded-2xl bg-success-soft border border-border-success-subtle text-fg-success relative overflow-hidden">
                <i class="fa-regular fa-calendar-check absolute -right-4 -bottom-4 text-8xl text-success opacity-10 pointer-events-none"></i>
                
                <h5 class="font-bold text-lg mb-4 flex items-center gap-2"><i class="fa-regular fa-calendar-check"></i> Jadwal Pelaksanaan Sumpah Advokat:</h5>
                <div class="h-px w-full bg-border-success-subtle mb-4"></div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative z-10">
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Tanggal</span>
                        <span class="block text-lg font-bold text-fg-success-strong">{{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->translatedFormat('d F Y') }}</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Pukul</span>
                        <span class="block text-lg font-bold text-fg-success-strong">{{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->jam)->format('H:i') }} WIB</span>
                    </div>
                    <div>
                        <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Lokasi</span>
                        <span class="block text-lg font-bold text-fg-success-strong">{{ $permohonan->jadwalSumpah->lokasi }}</span>
                    </div>
                </div>
                
                @if($permohonan->jadwalSumpah->keterangan)
                <div class="mt-5 pt-4 border-t border-border-success-subtle relative z-10">
                    <span class="block text-xs font-semibold uppercase tracking-wider opacity-70 mb-1">Keterangan:</span>
                    <p class="text-[14px]">{{ $permohonan->jadwalSumpah->keterangan }}</p>
                </div>
                @endif
            </div>
            @endif

            <!-- MEMBERS STATUS TABLE -->
            <h4 class="font-bold text-heading text-lg mt-10 mb-4 pb-2 border-b border-border-default">Daftar Anggota &amp; Status Kelengkapan</h4>
            <div class="overflow-x-auto w-full mb-8">
                <table class="w-full text-left whitespace-nowrap text-[14px] text-body border border-border-default rounded-xl overflow-hidden">
                    <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-bold">
                        <tr>
                            <th class="px-5 py-3 text-center">No</th>
                            <th class="px-5 py-3">Nama Anggota / NIK</th>
                            <th class="px-5 py-3">Email</th>
                            <th class="px-5 py-3 text-center">Status Verifikasi</th>
                            <th class="px-5 py-3">Catatan / Alasan</th>
                            <th class="px-5 py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-border-default font-medium">
                        @foreach($permohonan->pemohons as $index => $pemohon)
                            <tr>
                                <td class="px-5 py-4 text-center">{{ $index + 1 }}</td>
                                <td class="px-5 py-4">
                                    <div class="font-bold text-heading">{{ $pemohon->nama_lengkap }}</div>
                                    <div class="text-[12px] text-body-subtle font-mono">{{ $pemohon->nik }}</div>
                                </td>
                                <td class="px-5 py-4">{{ $pemohon->email }}</td>
                                <td class="px-5 py-4 text-center">
                                    @if($pemohon->status_verifikasi == 'Disetujui' || in_array($permohonan->status, ['Menentukan Jadwal Sumpah', 'Proses Pembuatan Surat', 'Surat Selesai', 'Selesai']))
                                        @if(in_array($permohonan->status, ['Menentukan Jadwal Berkas Fisik', 'Menunggu Verifikasi Admin', 'Menunggu Verifikasi Verifikator 1', 'Menunggu Verifikasi Verifikator 2', 'Menunggu Verifikasi Verifikator 3', 'Menunggu Verifikasi Verifikator 4', 'Menunggu Verifikasi']))
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-solid fa-spinner fa-spin mr-1"></i> Sedang Diproses</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong">✔ Disetujui</span>
                                        @endif
                                    @elseif($pemohon->status_verifikasi == 'Ditolak')
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">✖ Ditolak</span>
                                    @else
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-neutral-secondary-medium border border-border-default text-heading">Pending</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 whitespace-normal text-xs text-fg-danger-strong">
                                    {{ $pemohon->catatan_penolakan ?? '-' }}
                                </td>
                                <td class="px-5 py-4 text-center">
                                    @if($pemohon->status_verifikasi == 'Ditolak' || $permohonan->status == 'Draft')
                                        <a href="{{ route('permohonan.dokumen-upload', [$permohonan->nomor_permohonan, $pemohon->id]) }}" class="inline-flex items-center px-3 py-1.5 rounded-full text-xs font-bold bg-neutral-primary-soft text-brand border border-brand hover:bg-neutral-secondary-soft transition-all">
                                            <i class="fa-solid fa-edit mr-1"></i> Koreksi Berkas
                                        </a>
                                    @else
                                        <span class="text-body-subtle text-xs">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <h4 class="font-bold text-heading text-lg mt-10 mb-6 pb-2 border-b border-border-default">Riwayat Status</h4>
            
            <!-- Timeline Layout (Scrollable) -->
            <div class="max-h-[400px] overflow-y-auto pr-3 pl-1 py-2 custom-scrollbar">
                <div class="relative border-l border-brand ml-4 space-y-8 pb-4 pt-2">
                    @forelse($permohonan->riwayatStatus()->orderBy('changed_at', 'desc')->get() as $index => $riwayat)
                    <div class="relative pl-8">
                        <!-- Timeline Dot -->
                        <div class="absolute left-0 top-1 w-4 h-4 rounded-full -translate-x-1/2 {{ $index === 0 ? 'bg-brand ring-4 ring-brand-softer' : 'bg-neutral-primary border-2 border-brand' }}"></div>
                        
                        <div class="bg-neutral-primary rounded-xl p-4 shadow-sm border border-border-default hover:shadow-md transition-all">
                            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2 gap-2">
                                <span class="text-sm font-semibold text-heading">
                                    Perubahan ke <span class="text-brand">{{ $riwayat->status_baru }}</span>
                                </span>
                                <span class="text-xs text-body-subtle bg-neutral-primary-soft shadow-inset px-2 py-1 rounded-md">
                                    <i class="fa-regular fa-clock mr-1"></i> {{ \Carbon\Carbon::parse($riwayat->changed_at)->translatedFormat('d M Y - H:i') }}
                                </span>
                            </div>
                            <div class="text-xs text-body-subtle mb-2">
                                Dari status: <span class="px-1.5 py-0.5 bg-neutral-secondary-medium rounded text-heading">{{ $riwayat->status_lama }}</span>
                            </div>
                            @php
                                $isPhysicalNote = $riwayat->keterangan && (
                                    str_contains(strtolower($riwayat->keterangan), 'mohon bawa') || 
                                    str_contains(strtolower($riwayat->keterangan), 'berkas fisik')
                                );
                            @endphp
                            @if($riwayat->keterangan && !in_array($riwayat->status_baru, ['Proses Pembuatan Surat', 'Surat Selesai', 'Selesai']) && !str_starts_with($riwayat->status_baru, 'Menunggu Verifikasi Verifikator') && !($isPhysicalNote && !in_array($riwayat->status_baru, ['Menentukan Jadwal Berkas Fisik', 'Verifikasi Berkas Fisik'])))
                            <p class="text-[13px] text-body bg-neutral-primary-soft p-2.5 rounded-lg border border-border-default shadow-inset mt-3 whitespace-normal break-words">
                                {{ $riwayat->keterangan }}
                            </p>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="pl-8 text-body-subtle italic text-sm">
                        Belum ada riwayat perubahan status.
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
        @endif

    </div>
</section>

@endsection

@push('scripts')
<script>
function copyNomor() {
    const nomor = document.getElementById('nomorRegistrasi').textContent.trim();
    navigator.clipboard.writeText(nomor).then(() => {
        const icon = document.getElementById('copyIcon');
        icon.classList.remove('fa-regular', 'fa-copy');
        icon.classList.add('fa-solid', 'fa-check');
        setTimeout(() => {
            icon.classList.remove('fa-solid', 'fa-check');
            icon.classList.add('fa-regular', 'fa-copy');
        }, 2000);
    });
}
</script>
@endpush
