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

    $isCompletedWithBas = ($permohonan->status === 'Selesai') && $permohonan->pemohons()->whereHas('bukuRegistrasi', function ($q) {
        $q->whereNotNull('nomor_bas');
    })->exists();

    $allApproved = $totalMembers > 0 && $approvedMembers === $totalMembers;

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
                        <span class="text-heading font-bold text-lg">{{ $permohonan->organisasi?->nama_organisasi ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor SK Pendirian Advokat</span>
                        <span class="text-heading font-semibold">{{ $permohonan->nomor_sk ?? '-' }}</span>
                    </div>

                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tanggal SK Advokat</span>
                        <span class="text-heading font-semibold">
                            {{ $permohonan->tanggal_sk ? \Carbon\Carbon::parse($permohonan->tanggal_sk)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor HP Organisasi</span>
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
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nomor Surat Pengantar</span>
                        <span class="text-heading font-semibold">{{ $permohonan->nomor_surat_pengantar ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tanggal Surat Pengantar</span>
                        <span class="text-heading font-semibold">
                            {{ $permohonan->tanggal_surat_pengantar ? \Carbon\Carbon::parse($permohonan->tanggal_surat_pengantar)->translatedFormat('d F Y') : '-' }}
                        </span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col md:col-span-2">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Perihal Surat Pengantar</span>
                        <span class="text-heading font-semibold">{{ $permohonan->perihal_surat_pengantar ?? '-' }}</span>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col md:col-span-2">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-2">Dokumen Berkas Organisasi</span>
                        <div class="flex flex-wrap gap-2">
                            @if($permohonan->file_surat_pengantar)
                                <a href="{{ asset('storage/'.$permohonan->file_surat_pengantar) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded text-xs font-bold bg-neutral-primary-soft text-brand border border-brand hover:shadow-sm">
                                    <i class="fa-solid fa-file-pdf mr-1.5"></i> Lihat Surat Pengantar
                                </a>
                            @endif
                            @if($permohonan->file_sk_pendirian)
                                <a href="{{ asset('storage/'.$permohonan->file_sk_pendirian) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded text-xs font-bold bg-neutral-primary-soft text-brand border border-brand hover:shadow-sm">
                                    <i class="fa-solid fa-file-pdf mr-1.5"></i> Lihat SK Pendirian
                                </a>
                            @endif
                            @if($permohonan->file_sk_kepengurusan_pdf)
                                <a href="{{ asset('storage/'.$permohonan->file_sk_kepengurusan_pdf) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded text-xs font-bold bg-neutral-primary-soft text-brand border border-brand hover:shadow-sm">
                                    <i class="fa-solid fa-file-pdf mr-1.5"></i> Lihat SK Kepengurusan
                                </a>
                            @endif
                            @if(!$permohonan->file_surat_pengantar && !$permohonan->file_sk_pendirian && !$permohonan->file_sk_kepengurusan_pdf)
                                <span class="text-xs text-body-subtle font-normal italic">Belum ada file dokumen organisasi unggahan</span>
                            @endif
                        </div>
                    </div>
                    <div class="border-b border-border-default border-dashed pb-3 flex flex-col">
                        <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Status Permohonan</span>
                        <span>
                            @if($permohonan->status == 'Draft')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-neutral-secondary-medium text-heading">Draft</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Admin')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1.5"></i>Verifikasi Admin</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 1')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 1</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 2')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 2</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 3')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 3</span>
                            @elseif($permohonan->status == 'Menunggu Verifikasi Verifikator 4')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-regular fa-clock mr-1.5"></i>Verifikator 4</span>
                            @elseif($permohonan->status == 'Menentukan Jadwal Berkas Fisik')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-warning-soft border border-border-warning-subtle text-fg-warning"><i class="fa-solid fa-calendar-check mr-1"></i>Proses Pengecekan Berkas Fisik</span>
                            @elseif($permohonan->status == 'Menentukan Jadwal Sumpah')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-regular fa-calendar mr-1"></i>Jadwal Sumpah</span>
                            @elseif($permohonan->status == 'Proses Pembuatan Surat')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-info-soft border border-border-info-subtle text-fg-info"><i class="fa-solid fa-spinner fa-spin mr-1"></i>Pembuatan Surat</span>
                            @elseif($permohonan->status == 'Surat Selesai')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-success-soft border border-border-success-subtle text-fg-success-strong"><i class="fa-regular fa-file-pdf mr-1"></i>Surat Selesai</span>
                            @elseif($permohonan->status == 'Selesai')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-brand-softer border border-border-brand-subtle text-fg-brand-strong"><i class="fa-solid fa-flag-checkered mr-1"></i>Selesai</span>
                            @elseif($permohonan->status == 'Ditolak')
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-danger-soft border border-border-danger-subtle text-fg-danger-strong"><i class="fa-solid fa-circle-xmark mr-1.5"></i>Ditolak</span>
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
                                        ->map(fn($d) => $d->masterPersyaratan?->nama_persyaratan)
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
                'Menunggu Verifikasi Admin' => 'Menunggu Verifikasi Verifikator 1',
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
                    <div x-data="{ 
                        selectedSigner: '',
                        customSigner: '',
                        init() {
                            this.updateHidden();
                        },
                        updateHidden() {
                            document.getElementById('nama_penandatangan').value = this.selectedSigner === 'custom_signer' ? this.customSigner : this.selectedSigner;
                        }
                    }">
                        <label class="block text-[14px] font-bold text-heading mb-2">Nama Penandatangan <span class="text-fg-danger">*</span></label>
                        <select @change="selectedSigner = $event.target.value; updateHidden();" class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] text-heading py-2.5 px-3 focus:outline-none focus:border-brand transition-all" required>
                            <option value="">Pilih Nama Penandatangan</option>
                            <option value="[KOSONG]">Kosongkan (Tanpa Nama)</option>
                            @foreach(\App\Models\Signatory::all() as $s)
                                <option value="{{ $s?->name }}">{{ $s?->name }}</option>
                            @endforeach
                            <option value="custom_signer">+ Tulis Nama Penandatangan</option>
                        </select>
                        <div x-show="selectedSigner === 'custom_signer'" class="mt-3">
                            <label class="block text-[12px] font-medium text-heading mb-1">Nama Penandatangan <span class="text-fg-danger">*</span></label>
                            <input type="text" x-model="customSigner" @input="updateHidden()" placeholder="Masukkan nama pejabat..."
                                   class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[13px] text-heading py-2 px-3 focus:outline-none focus:border-brand transition-all">
                        </div>
                        <input type="hidden" name="nama_penandatangan" id="nama_penandatangan" value="">
                    </div>
                    <div>
                        <label class="block text-[14px] font-bold text-heading mb-2">Catatan Keterangan</label>
                        <textarea name="catatan" id="catatan_download_form" rows="3" 
                                  oninput="document.getElementById('catatan_transition_form').value = this.value"
                                  placeholder="Catatan keterangan draf surat (opsional)..."
                                  class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle"></textarea>
                    </div>
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-sm font-bold bg-brand text-white border border-brand shadow-sm hover:shadow-md active:shadow-inset transition-all">
                        <i class="fa-solid fa-file-word mr-2"></i> Download Draf Word
                    </button>
                </form>
            </div>
        </div>
        @endif

        @if($currentStatus === 'Menentukan Jadwal Berkas Fisik' || $currentStatus === 'Menunggu Verifikasi')
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 space-y-4">
                <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3">Penjadwalan Berkas Fisik</h6>
                <p class="text-xs text-body font-medium leading-relaxed">
                    Silakan tentukan jadwal penyerahan berkas fisik asli melalui halaman penjadwalan.
                </p>
                <a href="{{ route('admin.permohonan.penjadwalan', $permohonan->id) }}" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-sm font-bold bg-brand text-white border border-brand shadow-sm hover:shadow-md transition-all">
                    <i class="fa-solid fa-calendar-plus mr-2"></i> Tentukan Jadwal
                </a>
            </div>
        @endif

        @if($permohonan->tanggal_verifikasi_fisik && !in_array($permohonan->status, ['Proses Pembuatan Surat', 'Surat Selesai', 'Selesai', 'Ditolak']))
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 space-y-3">
                <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3">Jadwal Pengecekan Berkas Fisik</h6>
                <div class="text-xs space-y-2 text-body">
                    <div class="flex justify-between">
                        <span class="font-medium text-body-subtle">Hari:</span>
                        <span class="font-bold text-heading">{{ $permohonan->hari_verifikasi_fisik ?? \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->locale('id')->isoFormat('dddd') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="font-medium text-body-subtle">Tanggal:</span>
                        <span class="font-bold text-heading">{{ \Carbon\Carbon::parse($permohonan->tanggal_verifikasi_fisik)->isoFormat('D MMMM Y') }}</span>
                    </div>
                </div>
            </div>
        @endif

        @if($nextStatus)
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
                @if($nextStatus === 'Menunggu Verifikasi Verifikator 1' && !$allApproved)
                    <div class="mb-4 p-4 rounded-xl bg-neutral-secondary-medium text-body-subtle text-xs border border-border-default flex items-start gap-2">
                        <i class="fa-solid fa-circle-info mt-0.5"></i>
                        <span>Harap verifikasi dan setujui seluruh dokumen anggota terlebih dahulu sebelum melanjutkan ke langkah berikutnya.</span>
                    </div>
                @endif
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" enctype="multipart/form-data"
                      data-loading-title="Memperbarui Status Permohonan..."
                      data-loading-sub="Sistem sedang memperbarui status dan mengirimkan notifikasi."
                      class="space-y-4">
                    @csrf
                    <fieldset @disabled(($isCompletedWithBas && auth()->user()->role === 'admin') || ($nextStatus === 'Menunggu Verifikasi Verifikator 1' && !$allApproved)) class="space-y-4 w-full {{ ($nextStatus === 'Menunggu Verifikasi Verifikator 1' && !$allApproved) ? 'opacity-65' : '' }}">
                    
                    <div>
                        <label class="block text-[14px] font-bold text-heading mb-1.5">Status Baru yang Diajukan</label>
                        <div class="px-4 py-3 bg-brand/5 border border-brand/20 rounded-base text-brand font-bold text-sm flex items-center gap-2">
                            <i class="fa-solid fa-arrow-right-long"></i> {{ $nextStatus }}
                        </div>
                        <input type="hidden" name="status" value="{{ $nextStatus }}">
                    </div>

                    <!-- 1. Menentukan Jadwal Verifikasi / Berkas Fisik -->
                    @if(in_array($nextStatus, ['Menentukan Jadwal Verifikasi', 'Menentukan Jadwal Berkas Fisik', 'Menunggu Verifikasi Verifikator 1']))
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

                    <!-- 2. Menentukan Jadwal Sumpah (current status) → Proses Pembuatan Surat -->
                    @if($nextStatus === 'Proses Pembuatan Surat')
                        <div class="p-4 rounded-xl bg-brand/5 border border-brand/20 space-y-4">
                            <p class="text-[13px] font-semibold text-brand flex items-center gap-2">
                                <i class="fa-solid fa-calendar-days"></i>
                                Tentukan Jadwal Pelaksanaan Sumpah Advokat
                            </p>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[13px] font-medium text-heading mb-1">Tanggal Sumpah <span class="text-fg-danger">*</span></label>
                                    <input type="date" name="tanggal_sumpah" min="{{ now()->toDateString() }}" required
                                           class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                </div>
                                <div>
                                    <label class="block text-[13px] font-medium text-heading mb-1">Jam Sumpah <span class="text-fg-danger">*</span></label>
                                    <input type="time" name="jam_sumpah" required
                                           class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                </div>
                            </div>
                            <div x-data="{ 
                                selectedRoom: '{{ $rooms->first()?->name ?? '' }}',
                                customRoom: '',
                                init() { this.updateHidden(); },
                                updateHidden() {
                                    document.getElementById('lokasi_sumpah').value = this.selectedRoom === 'new_room' ? this.customRoom : this.selectedRoom;
                                }
                            }">
                                <label class="block text-[13px] font-medium text-heading mb-1">Lokasi Sumpah (Ruangan Sidang) <span class="text-fg-danger">*</span></label>
                                <select @change="selectedRoom = $event.target.value; updateHidden();" class="block w-full rounded-base border border-border-default-medium bg-white text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all">
                                    @foreach($rooms as $room)
                                        <option value="{{ $room?->name }}">{{ $room?->name }}</option>
                                    @endforeach
                                    <option value="new_room">+ Ajukan Ruangan Baru</option>
                                </select>
                                <div x-show="selectedRoom === 'new_room'" class="mt-3">
                                    <label class="block text-[12px] font-medium text-heading mb-1">Nama Ruangan Baru <span class="text-fg-danger">*</span></label>
                                    <input type="text" x-model="customRoom" @input="updateHidden()" placeholder="Masukkan nama ruangan baru..."
                                           class="block w-full rounded-base border border-border-default-medium bg-white shadow-inset text-[13px] text-heading py-2 px-3 focus:outline-none focus:border-brand transition-all">
                                </div>
                                <input type="hidden" name="lokasi_sumpah" id="lokasi_sumpah" value="">
                            </div>
                        </div>
                        <div class="p-4 rounded-xl bg-info-soft border border-border-info-subtle text-fg-info text-[13px]">
                            <i class="fa-solid fa-gears mr-1"></i> Sistem akan secara otomatis meng-generate draf surat pengantar (Word/PDF) saat status ini disimpan.
                        </div>
                    @endif

                    <!-- 4. Surat Selesai (Upload signed PDF) -->
                    @if($nextStatus === 'Surat Selesai')
                        <div class="p-4 rounded-xl bg-success-soft border border-border-success-subtle space-y-3">
                            <label class="block text-[13px] font-bold text-fg-success-strong">Unggah Surat Pengantar Final Bertanda Tangan (PDF) <span class="text-fg-danger">*</span></label>
                            <input type="file" name="surat_bertanda_tangan" accept=".pdf" required
                                   class="block w-full text-sm text-heading file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-red-600 file:text-white hover:file:bg-red-700 transition-all cursor-pointer">
                            <p class="text-[12px] text-fg-success font-medium">Hanya menerima file PDF dengan ukuran maksimal 2MB.</p>
                        </div>
                    @endif

                    <div class="mb-4">
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan Keterangan</label>
                        <textarea name="catatan" id="catatan_transition_form" rows="3"
                                  placeholder="Catatan keterangan (opsional)..."
                                  class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle"></textarea>
                    </div>

                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 rounded-base text-[15px] font-bold bg-brand text-white shadow-sm hover:shadow-md hover:opacity-95 active:shadow-inset transition-all border border-brand-softer">
                        <i class="fas fa-save mr-2"></i> Simpan
                    </button>
                    </fieldset>
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
            <div class="max-h-[300px] overflow-y-auto pr-3 pl-1 py-1 custom-scrollbar" style="max-height: 300px; overflow-y: auto;">
                <div class="relative border-l border-brand ml-2 space-y-6 pb-2" style="border-left: 2px solid var(--color-brand, #8b1e1e); margin-left: 8px; position: relative;">
                    @forelse($permohonan->riwayatStatus()->orderBy('changed_at', 'desc')->get() as $riwayat)
                    <div class="relative pl-6" style="position: relative; padding-left: 24px;">
                        <div class="absolute left-0 top-1.5 w-2.5 h-2.5 rounded-full bg-brand -translate-x-1/2" style="background-color: var(--color-brand, #8b1e1e);"></div>
                        <div class="text-[13px] text-heading font-bold">{{ $riwayat->status_baru }}</div>
                        <div class="text-[11px] text-body-subtle">{{ \Carbon\Carbon::parse($riwayat->changed_at)->translatedFormat('d M Y - H:i') }}</div>
                        @php
                            $isPhysicalNote = $riwayat->keterangan && (
                                str_contains(strtolower($riwayat->keterangan), 'mohon bawa') || 
                                str_contains(strtolower($riwayat->keterangan), 'berkas fisik')
                            );
                        @endphp
                        @if($riwayat->keterangan && !str_starts_with($riwayat->status_baru, 'Menunggu Verifikasi Verifikator') && !($isPhysicalNote && !in_array($riwayat->status_baru, ['Menentukan Jadwal Berkas Fisik', 'Verifikasi Berkas Fisik'])))
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
