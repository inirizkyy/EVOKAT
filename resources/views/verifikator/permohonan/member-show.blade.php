@extends('layouts.admin')
@section('title', 'Detail & Verifikasi Anggota')

@section('actions')
<a href="{{ route($role . '.permohonan.show', $permohonan->id) }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali ke Detail Permohonan
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-8">
    
    <!-- Kolom Kiri: Profil Anggota -->
    <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col h-full">
        <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
            <h6 class="m-0 font-bold text-heading">Profil Anggota Pemohon</h6>
        </div>
        <div class="p-6 space-y-4 text-sm">
            <div class="flex flex-col border-b border-border-default border-dashed pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Nama Lengkap &amp; Gelar</span>
                <span class="text-heading font-bold text-lg">{{ $pemohon->nama_lengkap }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default border-dashed pb-3 font-mono">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">NIK (16 Digit)</span>
                <span class="text-heading font-semibold">{{ $pemohon->nik }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default border-dashed pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Tempat, Tanggal Lahir</span>
                <span class="text-heading font-semibold">{{ $pemohon->tempat_lahir }}, {{ \Carbon\Carbon::parse($pemohon->tanggal_lahir)->translatedFormat('d F Y') }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default border-dashed pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Jenis Kelamin</span>
                <span class="text-heading font-semibold">{{ $pemohon->jenis_kelamin == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
            </div>
            <div class="flex flex-col border-b border-border-default border-dashed pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Email Aktif Anggota</span>
                <span class="text-heading font-semibold">{{ $pemohon->email }}</span>
            </div>
            <div class="flex flex-col pb-3">
                <span class="text-xs text-body-subtle font-semibold uppercase tracking-wider mb-1">Organisasi</span>
                <span class="text-heading font-bold">{{ $permohonan->organisasi->nama_organisasi ?? '-' }}</span>
            </div>
        </div>

        <!-- Riwayat Status Timeline (Scrollable) -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col p-6 mt-6">
            <h6 class="font-bold text-heading mb-4">Riwayat Status Permohonan</h6>
            <div class="max-h-[280px] overflow-y-auto pr-2 pl-1 py-1 custom-scrollbar" style="max-height: 280px; overflow-y: auto;">
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
                            <div class="text-[12px] text-body mt-1.5 bg-white p-2 rounded border border-border-default shadow-sm font-medium whitespace-normal break-words">{{ $riwayat->keterangan }}</div>
                        @endif
                    </div>
                    @empty
                    <div class="pl-4 text-body-subtle text-xs italic">Belum ada riwayat.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Validasi Ulang Dokumen Persyaratan -->
    <div class="lg:col-span-2 space-y-8 min-w-0">
        
        <form action="{{ route($role . '.permohonan.member-verifikasi', [$permohonan->id, $pemohon->id]) }}" method="POST"
              data-loading-title="Menyimpan Hasil Pemeriksaan Dokumen..."
              data-loading-sub="Harap tunggu, hasil verifikasi dokumen sedang disimpan.">
            @csrf
            <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col mb-8">
                <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base flex justify-between items-center">
                    <h6 class="m-0 font-bold text-heading">Pemeriksaan &amp; Validasi Ulang Dokumen</h6>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded text-xs font-bold {{ $pemohon->status_verifikasi === 'Disetujui' ? 'bg-success-soft text-fg-success-strong' : ($pemohon->status_verifikasi === 'Ditolak' ? 'bg-danger-soft text-fg-danger-strong' : 'bg-warning-soft text-fg-warning') }}">
                        Status: {{ $pemohon->status_verifikasi }}
                    </span>
                </div>
                <div class="p-0">
                    <div class="overflow-x-auto w-full">
                        <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                            <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                                <tr>
                                    <th class="px-6 py-3">Persyaratan Dokumen</th>
                                    <th class="px-6 py-3 text-center">File Dokumen</th>
                                    <th class="px-6 py-3">Keputusan Verifikator</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white font-medium">
                                @foreach($pemohon->dokumenPersyaratan as $dok)
                                    <!-- Row Utama Dokumen -->
                                    <tr class="transition-colors duration-150 hover:bg-neutral-secondary-soft/50">
                                        <td class="px-6 pt-4 pb-2 whitespace-normal font-semibold text-heading border-t border-border-default">
                                            {{ $dok->masterPersyaratan->nama_persyaratan ?? 'Persyaratan Dokumen' }}
                                            @if($dok->masterPersyaratan?->is_required) <span class="text-fg-danger">*</span> @endif
                                        </td>
                                        <td class="px-6 pt-4 pb-2 text-center border-t border-border-default">
                                            @if($dok->file_path)
                                                <a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-base text-xs font-bold bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md border border-brand transition-all">
                                                    <i class="fa-solid fa-file-pdf mr-1.5"></i> Lihat File
                                                </a>
                                            @else
                                                <span class="text-fg-danger text-xs font-bold">Belum diunggah</span>
                                            @endif
                                        </td>
                                        <td class="px-6 pt-4 pb-2 border-t border-border-default">
                                            <div class="flex items-center gap-4">
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="dokumen[{{ $dok->id }}]" value="Valid" 
                                                           {{ old("dokumen.{$dok->id}", $dok->status_dokumen) == 'Valid' ? 'checked' : '' }} required 
                                                           class="text-success focus:ring-success w-4 h-4">
                                                    <span class="text-xs text-heading font-bold">Valid</span>
                                                </label>
                                                <label class="inline-flex items-center gap-1.5 cursor-pointer">
                                                    <input type="radio" name="dokumen[{{ $dok->id }}]" value="Tidak Valid" 
                                                           {{ old("dokumen.{$dok->id}", $dok->status_dokumen) == 'Tidak Valid' ? 'checked' : '' }} required 
                                                           class="text-danger focus:ring-danger w-4 h-4">
                                                    <span class="text-xs text-heading font-bold">Tidak Valid</span>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                    <!-- Row Catatan Dokumen -->
                                    <tr class="transition-colors duration-150 border-b border-border-default hover:bg-neutral-secondary-soft/50">
                                        <td colspan="3" class="px-6 pb-4 pt-1">
                                            <div class="flex flex-col gap-1.5">
                                                <label class="text-[11px] font-bold text-body-subtle flex items-center gap-1">
                                                    <i class="fa-regular fa-comment-dots text-brand"></i>
                                                    <span>Catatan / Alasan (Opsional):</span>
                                                </label>
                                                <input type="text" name="keterangan_dokumen[{{ $dok->id }}]" 
                                                       value="{{ old("keterangan_dokumen.{$dok->id}", $dok->keterangan) }}" 
                                                       placeholder="Tulis alasan jika dokumen tidak valid atau perlu revisi..."
                                                       class="block w-full rounded border border-border-default bg-white text-xs py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all font-normal shadow-sm">
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-3 rounded-base text-[16px] font-bold bg-brand text-white shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:opacity-95">
                <i class="fa-solid fa-circle-check mr-2"></i> Simpan Hasil Validasi Ulang Dokumen
            </button>
        </form>

    </div>
</div>
@endsection
