@extends('layouts.admin')
@section('title', 'Detail Permohonan')

@section('actions')
<a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
@php
    $totalMembers = $permohonan->pemohons->count();
    $approvedMembers = $permohonan->pemohons->where('status_verifikasi', 'Disetujui')->count();
    $rejectedMembers = $permohonan->pemohons->where('status_verifikasi', 'Ditolak')->count();
    $pendingMembers = $permohonan->pemohons->where('status_verifikasi', 'Pending')->count();
    
    // Progress verifikasi: percentage of members whose status is not Pending
    $verifiedMembers = $approvedMembers + $rejectedMembers;
    $progressPercent = $totalMembers > 0 ? round(($verifiedMembers / $totalMembers) * 100) : 0;

    $allDocsComplete = true;
    $hasInvalidDocs = false;
    $requiredCount = \App\Models\MasterPersyaratan::where('is_required', true)->count();
    foreach ($permohonan->pemohons as $pemohon) {
        $uploadedCount = $pemohon->dokumenPersyaratan
            ->whereIn('persyaratan_id', \App\Models\MasterPersyaratan::where('is_required', true)->pluck('id'))
            ->where('status_dokumen', 'Valid')
            ->count();
            
        $invalidCount = $pemohon->dokumenPersyaratan
            ->where('status_dokumen', 'Tidak Valid')
            ->count();
            
        if ($invalidCount > 0) {
            $hasInvalidDocs = true;
        }
        
        if ($uploadedCount < $requiredCount) {
            $allDocsComplete = false;
        }
    }
@endphp


<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <!-- Kolom Kiri: Data Organisasi & Status Permohonan -->
    <div class="lg:col-span-2 space-y-8">
        
        <!-- Informasi Organisasi -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base flex justify-between items-center">
                <h6 class="m-0 font-bold text-heading">Informasi Organisasi Advokat</h6>
                @if($allDocsComplete)
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong" title="Seluruh dokumen semua anggota valid">
                        <i class="fa-solid fa-circle-check mr-1.5"></i> Berkas Lengkap
                    </span>
                @elseif($hasInvalidDocs)
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong" title="Terdapat dokumen yang ditolak atau tidak valid">
                        <i class="fa-solid fa-circle-xmark mr-1.5"></i> Berkas Kurang/Ditolak
                    </span>
                @else
                    <span class="inline-flex items-center px-2.5 py-1 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning" title="Dokumen menunggu verifikasi admin">
                        <i class="fa-regular fa-clock mr-1.5"></i> Berkas Menunggu
                    </span>
                @endif
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div class="border-b border-border-default border-dashed pb-3 md:col-span-2 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nama Organisasi Advokat</span>
                        <span class="text-heading font-bold text-lg">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor SK Advokat</span>
                        <span class="text-heading font-semibold">{{ $permohonan->nomor_sk ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tanggal SK Advokat</span>
                        <span class="text-heading font-semibold">
                            {{ $permohonan->tanggal_sk ? \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor HP/WA Organisasi</span>
                        <span class="text-heading font-semibold">{{ $permohonan->no_hp_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Email Aktif Organisasi</span>
                        <span class="text-heading font-semibold">{{ $permohonan->email_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tanggal Pengajuan</span>
                        <span class="text-heading font-semibold">
                            {{ $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Status Permohonan</span>
                        <span>
                            @if($permohonan->status == 'Draft')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-neutral-secondary-medium text-heading">Draft</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1.5"></i>Menunggu Verifikasi</span>
                            @elseif($permohonan->status == 'Verifikasi Berkas Fisik')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-solid fa-folder-open mr-1.5"></i>Verifikasi Fisik</span>
                            @elseif($permohonan->status == 'Siap Penjadwalan Pengecekan Berkas Fisik')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong"><i class="fa-solid fa-calendar mr-1"></i>Siap Penjadwalan Fisik</span>
                            @elseif($permohonan->status == 'Menentukan Jadwal Verifikasi')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-regular fa-clock mr-1"></i>Jadwal Verifikasi</span>
                            @elseif($permohonan->status == 'Menentukan Jadwal Sumpah')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-regular fa-calendar mr-1"></i>Jadwal Sumpah</span>
                            @elseif($permohonan->status == 'Proses Pembuatan Surat')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Pembuatan Surat</span>
                            @elseif($permohonan->status == 'Surat Selesai')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong"><i class="fa-regular fa-file-pdf mr-1"></i>Surat Selesai</span>
                            @elseif($permohonan->status == 'Selesai')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-brand-softer border border-border-brand-subtle text-fg-brand-strong"><i class="fa-solid fa-flag-checkered mr-1"></i>Selesai</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning">{{ $permohonan->status }}</span>
                            @endif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Daftar Anggota -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Daftar Anggota Pemohon</h6>
            </div>
            <div class="p-0">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                        <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                            <tr>
                                <th class="px-6 py-3">Nama Anggota</th>
                                <th class="px-6 py-3">NIK</th>
                                <th class="px-6 py-3">Email</th>
                                <th class="px-6 py-3 text-center">Status Berkas</th>
                                <th class="px-6 py-3 text-center">Verifikasi</th>
                                <th class="px-6 py-3 text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-neutral-primary divide-y divide-border-default font-medium">
                            @forelse($permohonan->pemohons as $pemohon)
                                @php
                                    $pemohonValidCount = $pemohon->dokumenPersyaratan
                                        ->whereIn('persyaratan_id', \App\Models\MasterPersyaratan::where('is_required', true)->pluck('id'))
                                        ->where('status_dokumen', 'Valid')
                                        ->count();
                                    $pemohonComplete = ($pemohonValidCount >= $requiredCount);
                                    
                                    $pemohonInvalidCount = $pemohon->dokumenPersyaratan
                                        ->where('status_dokumen', 'Tidak Valid')
                                        ->count();
                                        
                                    $pemohonUploadedCount = $pemohon->dokumenPersyaratan
                                        ->whereIn('persyaratan_id', \App\Models\MasterPersyaratan::where('is_required', true)->pluck('id'))
                                        ->where('status_dokumen', '!=', 'Tidak Valid')
                                        ->count();
                                    $allUploaded = ($pemohonUploadedCount >= $requiredCount);
                                    
                                    $invalidDocsList = $pemohon->dokumenPersyaratan
                                        ->where('status_dokumen', 'Tidak Valid')
                                        ->map(fn($d) => $d->masterPersyaratan->nama_persyaratan)
                                        ->implode(', ');
                                @endphp
                                <tr class="hover:bg-neutral-secondary-soft transition-colors">
                                    <td class="px-6 py-4 font-bold text-heading">{{ $pemohon->nama_lengkap }}</td>
                                    <td class="px-6 py-4 font-mono">{{ $pemohon->nik }}</td>
                                    <td class="px-6 py-4">{{ $pemohon->email }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @if($pemohonComplete)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-success-soft text-fg-success-strong border border-border-success-subtle">Lengkap</span>
                                        @elseif($pemohonInvalidCount > 0)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-danger-soft text-fg-danger-strong border border-border-danger-subtle">Kurang/Ditolak</span>
                                        @elseif($allUploaded)
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-warning-soft text-fg-warning border border-border-warning-subtle">Menunggu</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-danger-soft text-fg-danger-strong border border-border-danger-subtle">Belum Lengkap</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if($pemohon->status_verifikasi == 'Disetujui')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-success-soft text-fg-success-strong border border-border-success-subtle">✔ Disetujui</span>
                                        @elseif($pemohon->status_verifikasi == 'Ditolak')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-danger-soft text-fg-danger-strong border border-border-danger-subtle cursor-help" title="Berkas tidak valid: {{ $invalidDocsList }}">✖ Ditolak</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-neutral-secondary-medium border border-border-default text-heading">Pending</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ route('admin.permohonan.member-show', $pemohon->id) }}" class="inline-flex items-center px-3 py-1.5 rounded-base text-xs font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong border border-brand transition-all">
                                            <i class="fa-solid fa-file-shield mr-1"></i> Periksa / Verifikasi
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-body-subtle italic">Belum ada anggota terdaftar.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- Kolom Kanan: Alur Status Sekuensial & Log Riwayat -->
    <div class="flex flex-col space-y-8">
        
        <!-- Aksi Transisi Status Sekuensial -->
        @php
            $currentStatus = $permohonan->status;
            $nextStatusMap = [
                'Siap Penjadwalan Pengecekan Berkas Fisik' => 'Menentukan Jadwal Verifikasi',
                'Menentukan Jadwal Verifikasi' => 'Menentukan Jadwal Sumpah',
                'Menentukan Jadwal Sumpah' => 'Proses Pembuatan Surat',
                'Proses Pembuatan Surat' => 'Surat Selesai',
                'Surat Selesai' => 'Selesai',
            ];
            $nextStatus = $nextStatusMap[$currentStatus] ?? null;
            $allApproved = $permohonan->pemohons->every(fn($m) => $m->status_verifikasi === 'Disetujui');
        @endphp

        @if($currentStatus === 'Proses Pembuatan Surat')
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Cetak / Download Draf Surat</h6>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-[13px] text-body leading-relaxed">Silakan unduh draf surat di bawah ini untuk dicetak dan ditandatangani basah oleh Ketua Pengadilan Tinggi / Pejabat berwenang.</p>
                
                @php
                    $hasActiveTemplate = \App\Models\SuratTemplate::where('is_active', true)->exists();
                    $isDocx = $permohonan->file_surat ? Str::endsWith($permohonan->file_surat, ['.docx', '.doc']) : $hasActiveTemplate;
                @endphp
                
                <form action="{{ route('admin.permohonan.download-surat', $permohonan->id) }}" method="GET" class="space-y-4">
                    <div>
                        <label class="block text-[14px] font-bold text-heading mb-2">Jabatan Penandatangan <span class="text-fg-danger">*</span></label>
                        <select name="jabatan" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">Pilih Jabatan Penandatangan</option>
                            <option value="PANITERA">PANITERA</option>
                            <option value="PLH. PANITERA">PLH. PANITERA</option>
                            <option value="PLT. PANITERA">PLT. PANITERA</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-sm font-bold bg-brand text-white border border-brand shadow-sm hover:shadow-md active:shadow-inset transition-all">
                        @if($isDocx)
                            <i class="fa-solid fa-file-word mr-2"></i> Download Draf Word
                        @else
                            <i class="fa-solid fa-file-pdf mr-2"></i> Download Draf PDF
                        @endif
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($currentStatus === 'Verifikasi Berkas Fisik' || $currentStatus === 'Menunggu Verifikasi')
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 space-y-4">
                <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3">Penjadwalan Berkas Fisik</h6>
                <p class="text-xs text-body font-medium leading-relaxed">
                    Tentukan jadwal penyerahan berkas fisik asli melalui halaman penjadwalan setelah seluruh dokumen anggota disetujui.
                </p>
                <button type="button" disabled class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-sm font-bold bg-neutral-secondary-medium text-body-subtle border border-border-default cursor-not-allowed shadow-none">
                    <i class="fa-solid fa-calendar-plus mr-2"></i> Tentukan Jadwal
                </button>
            </div>
        @elseif($currentStatus === 'Siap Penjadwalan Pengecekan Berkas Fisik')
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 space-y-4">
                <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3">Penjadwalan Berkas Fisik</h6>
                <p class="text-xs text-body font-medium leading-relaxed">
                    Seluruh dokumen anggota telah disetujui. Silakan tentukan jadwal penyerahan berkas fisik asli melalui halaman penjadwalan.
                </p>
                <a href="{{ route('admin.permohonan.penjadwalan', $permohonan->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-sm font-bold bg-brand text-white border border-brand shadow-sm hover:shadow-md transition-all">
                    <i class="fa-solid fa-calendar-plus mr-2"></i> Tentukan Jadwal
                </a>
            </div>
        @elseif($nextStatus)
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col"
             x-data="{
                selectedStatus: '{{ $nextStatus }}',
                hariVerifikasi: '',
                setHariVerifikasi(val) {
                    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                    this.hariVerifikasi = val ? days[new Date(val).getDay()] : '';
                }
             }">
            <div class="p-6">
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf
                    
                    <div>
                        <label class="block text-[14px] font-bold text-heading mb-1.5">Status Baru yang Diajukan</label>
                        <div class="px-4 py-3 bg-brand/5 border border-brand/20 rounded-base text-brand font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-arrow-right-long"></i> {{ $nextStatus }}
                        </div>
                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                    </div>

                    <!-- 1. Menentukan Jadwal Verifikasi -->
                    @if($nextStatus === 'Menentukan Jadwal Verifikasi')
                        <div class="p-4 rounded-xl bg-warning-soft border border-border-warning-subtle space-y-4">
                            <p class="text-[13px] font-semibold text-fg-warning flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days"></i>
                                Tentukan jadwal penyerahan berkas fisik
                            </p>
                            <div>
                                <label class="block text-[13px] font-medium text-heading mb-1">Tanggal <span class="text-fg-danger">*</span></label>
                                <input type="date" name="tanggal_verifikasi_fisik" min="{{ now()->toDateString() }}"
                                       @change="setHariVerifikasi($event.target.value)" required
                                       class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                <div x-show="hariVerifikasi" class="mt-2 flex items-center gap-2">
                                    <i class="fa-solid fa-circle-check text-fg-warning text-sm"></i>
                                    <span class="text-[13px] font-semibold text-fg-warning">Hari: <span x-text="hariVerifikasi"></span></span>
                                </div>
                                <input type="hidden" name="hari_verifikasi_fisik" :value="hariVerifikasi">
                            </div>
                        </div>
                    @endif

                    <!-- 2. Menentukan Jadwal Sumpah -->
                    @if($nextStatus === 'Menentukan Jadwal Sumpah')
                        <div class="p-4 rounded-xl bg-brand/5 border border-border-brand-subtle space-y-4">
                            <p class="text-[13px] font-semibold text-brand flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days"></i>
                                Tentukan Jadwal Pelaksanaan Sumpah Advokat
                            </p>
                            <div class="grid grid-cols-1 gap-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-heading mb-1">Tanggal Sumpah <span class="text-fg-danger">*</span></label>
                                    <input type="date" name="tanggal_sumpah" required
                                           class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-heading mb-1">Jam Sumpah <span class="text-fg-danger">*</span></label>
                                    <input type="time" name="jam_sumpah" required
                                           class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                </div>
                            </div>
                            <div>
                                <label class="block text-[13px] font-medium text-heading mb-1">Lokasi Sumpah <span class="text-fg-danger">*</span></label>
                                <input type="text" name="lokasi_sumpah" value="Ruang Sidang Utama Pengadilan Tinggi Tanjungkarang" required
                                       class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                </div>
                        </div>
                    @endif

                    <!-- 3. Proses Pembuatan Surat -->
                    @if($nextStatus === 'Proses Pembuatan Surat')
                        <div class="p-4 rounded-xl bg-info-soft border border-border-info-subtle text-fg-info text-[13px]">
                            <i class="fa-solid fa-gears mr-1"></i> Sistem akan secara otomatis meng-generate draf surat pengantar (Word/PDF) saat status ini disimpan.
                        </div>
                    @endif

                    <!-- 4. Surat Selesai (Upload signed PDF) -->
                    @if($nextStatus === 'Surat Selesai')
                        <div class="p-4 rounded-xl bg-success-soft border border-border-success-subtle space-y-3">
                            <label class="block text-[13px] font-bold text-fg-success-strong">Unggah Surat Pengantar Final Bertanda Tangan (PDF) <span class="text-fg-danger">*</span></label>
                            <input type="file" name="surat_bertanda_tangan" accept=".pdf" required
                                   class="block w-full text-sm text-heading file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-brand/10 file:text-brand hover:file:bg-brand/20 transition-all">
                            <p class="text-[12px] text-fg-success font-medium">Hanya menerima file PDF dengan ukuran maksimal 2MB.</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan Keterangan</label>
                        <textarea name="catatan" rows="3" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle" placeholder="Catatan hasil verifikasi atau keterangan jadwal..."></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-[15px] font-bold bg-brand text-white shadow-sm hover:shadow-md hover:opacity-95 active:shadow-inset transition-all border border-brand-softer">
                        <i class="fas fa-save mr-2"></i> Konfirmasi Status Baru
                    </button>
                </form>
            </div>
        </div>
        @endif

        <!-- Panel Surat Pengantar Final -->
        @if(in_array($permohonan->status, ['Surat Selesai', 'Selesai']) && $permohonan->file_surat)
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-success-subtle flex flex-col">
            <div class="py-4 px-6 border-b border-border-success-subtle bg-success-soft rounded-t-base">
                <h6 class="m-0 font-bold text-fg-success-strong flex items-center gap-2"><i class="fa-solid fa-file-circle-check"></i> Surat Pengantar Final</h6>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-[13px] text-body leading-relaxed">Surat pengantar final bertanda tangan basah telah diunggah dan siap diunduh.</p>
                <a href="{{ route('admin.permohonan.download-surat', $permohonan->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-sm font-bold bg-neutral-primary-soft text-success shadow-sm hover:shadow-md hover:text-fg-success-strong border border-success transition-all">
                    <i class="fa-solid fa-download mr-2"></i> Download Surat Final (PDF)
                </a>
            </div>
        </div>
        @endif

        <!-- Panel detail jadwal sumpah jika terdaftar -->
        @if(in_array($permohonan->status, ['Menentukan Jadwal Sumpah', 'Proses Pembuatan Surat', 'Surat Selesai', 'Selesai']) && $permohonan->jadwalSumpah)
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-fg-success-strong"><i class="fa-solid fa-calendar-check mr-2"></i>Jadwal Sumpah</h6>
            </div>
            <div class="p-6 space-y-3 text-[14px]">
                <div><span class="text-body-subtle">Hari:</span> {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->locale('id')->translatedFormat('l') }}</div>
                <div><span class="text-body-subtle">Tanggal:</span> {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->tanggal)->locale('id')->translatedFormat('d F Y') }}</div>
                <div><span class="text-body-subtle">Pukul:</span> {{ \Carbon\Carbon::parse($permohonan->jadwalSumpah->jam)->format('H:i') }} WIB</div>
                <div><span class="text-body-subtle">Lokasi:</span> {{ $permohonan->jadwalSumpah->lokasi }}</div>
            </div>
        </div>
        @endif

        <!-- Riwayat Status Timeline -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6">
            <h6 class="font-bold text-heading mb-4">Riwayat Status Permohonan</h6>
            <div class="max-h-[400px] overflow-y-auto pr-3 pl-1 py-1 custom-scrollbar">
                <div class="relative border-l border-brand ml-2 space-y-6 pb-2" style="border-left: 2px solid var(--color-brand, #8b1e1e); margin-left: 8px; position: relative;">
                    @forelse($permohonan->riwayatStatus()->orderBy('changed_at', 'desc')->get() as $riwayat)
                    <div class="relative pl-6" style="position: relative; padding-left: 24px;">
                        <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-brand -translate-x-1/2" style="background-color: var(--color-brand, #8b1e1e);"></div>
                        <div class="text-[13px] text-heading font-bold">{{ $riwayat->status_baru }}</div>
                        <div class="text-[11px] text-body-subtle">{{ \Carbon\Carbon::parse($riwayat->changed_at)->translatedFormat('d M Y - H:i') }}</div>
                        @if($riwayat->keterangan)
                            <div class="text-[12px] text-body mt-1.5 bg-white p-2 rounded border border-border-default shadow-sm">{{ $riwayat->keterangan }}</div>
                        @endif
                    </div>
                    @empty
                    <div class="pl-4 text-body-subtle text-xs italic">Belum ada riwayat.</div>
                    @endforelse
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
