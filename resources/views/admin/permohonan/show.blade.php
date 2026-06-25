@extends('layouts.admin')
@section('title', 'Detail Permohonan')

@section('actions')
<a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
    
    <!-- Data Pemohon -->
    <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col h-full">
        <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
            <h6 class="m-0 font-bold text-heading">Data Pemohon</h6>
        </div>
        <div class="p-6 flex-1">
            <div class="space-y-4">
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">NIK</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->nik ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Nama Lengkap</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->nama_lengkap ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Tempat, Tanggal Lahir</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->tempat_lahir ?? '-' }}, {{ $permohonan->pemohon->tanggal_lahir ? \Carbon\Carbon::parse($permohonan->pemohon->tanggal_lahir)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Jenis Kelamin</span>
                    <span class="text-heading sm:w-2/3">{{ ($permohonan->pemohon->jenis_kelamin ?? '') == 'L' ? 'Laki-Laki' : 'Perempuan' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Alamat</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->alamat ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Email</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->email ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">No. HP</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->no_hp ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Organisasi Advokat</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->pemohon->organisasi->nama_organisasi ?? '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Status Permohonan</span>
                    <span class="sm:w-2/3">
                        @if($permohonan->status == 'Menunggu Verifikasi')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Menunggu</span>
                        @elseif($permohonan->status == 'Disetujui')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong">Disetujui</span>
                        @elseif($permohonan->status == 'Selesai')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-brand-softer border border-border-brand-subtle text-fg-brand-strong">Selesai</span>
                        @elseif($permohonan->status == 'Ditolak')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Ditolak</span>
                        @else
                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-warning-soft border border-border-warning-subtle text-fg-warning">{{ $permohonan->status }}</span>
                        @endif
                    </span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Tanggal Pengajuan</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->tanggal_pengajuan ? \Carbon\Carbon::parse($permohonan->tanggal_pengajuan)->translatedFormat('d F Y') : '-' }}</span>
                </div>
                <div class="flex flex-col sm:flex-row border-b border-border-default pb-3">
                    <span class="text-body font-medium sm:w-1/3">Catatan</span>
                    <span class="text-heading sm:w-2/3">{{ $permohonan->catatan ?? '-' }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan -->
    <div class="flex flex-col space-y-8">
        <!-- Dokumen Persyaratan -->
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Dokumen Persyaratan</h6>
            </div>
            <div class="p-0 flex-1">
                <div class="overflow-x-auto w-full">
                    <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                        <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                            <tr>
                                <th class="px-6 py-3 font-medium">Nama Persyaratan</th>
                                <th class="px-6 py-3 font-medium">File</th>
                                <th class="px-6 py-3 font-medium">Status Dokumen</th>
                            </tr>
                        </thead>
                        <tbody class="bg-neutral-primary divide-y divide-border-default">
                            @forelse($permohonan->dokumenPersyaratan as $dok)
                                <tr class="hover:bg-neutral-secondary-soft transition-colors">
                                    <td class="px-6 py-4 whitespace-normal">{{ $dok->masterPersyaratan->nama_persyaratan ?? 'Persyaratan' }}</td>
                                    <td class="px-6 py-4">
                                        @if($dok->file_path)
                                            <a href="{{ asset('storage/'.$dok->file_path) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 rounded-base text-sm font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
                                                <i class="fas fa-file-alt mr-2"></i> Lihat
                                            </a>
                                        @else
                                            <span class="text-fg-danger">Belum diunggah</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($dok->status_dokumen == 'Valid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-success-soft border border-border-success-subtle text-fg-success-strong">Valid</span>
                                        @elseif($dok->status_dokumen == 'Tidak Valid')
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-danger-soft border border-border-danger-subtle text-fg-danger-strong">Tidak Valid</span>
                                        @else
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-default text-xs font-medium bg-neutral-secondary-medium border border-border-default text-heading">Pending</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-body-subtle">Belum ada dokumen yang diunggah.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Aksi Verifikasi -->
        @if($permohonan->status == 'Menunggu Verifikasi')
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-heading">Aksi Verifikasi</h6>
            </div>
            <div class="p-6">
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-[14px] font-medium text-heading mb-2">Status Verifikasi</label>
                        <select name="status" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all" required>
                            <option value="">-- Pilih Status --</option>
                            <option value="Disetujui">Disetujui (Dokumen Lengkap & Valid)</option>
                            <option value="Ditolak">Ditolak (Ada Kekurangan/Tidak Valid)</option>
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-[14px] font-medium text-heading mb-2">Catatan</label>
                        <textarea name="catatan" class="block w-full rounded-base border border-border-default-medium bg-transparent shadow-inset text-[14px] text-heading py-2 px-3 focus:outline-none focus:border-brand focus:ring-1 focus:ring-brand transition-all placeholder:text-body-subtle" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                    </div>
                    
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[16px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-brand hover:text-brand-strong">
                        <i class="fas fa-save mr-2"></i> Simpan Verifikasi
                    </button>
                </form>
            </div>
        </div>
        @elseif($permohonan->status == 'Dijadwalkan Sumpah')
        <div class="bg-neutral-primary-soft rounded-base shadow-md border border-border-default flex flex-col">
            <div class="py-4 px-6 border-b border-border-default bg-neutral-primary-soft rounded-t-base">
                <h6 class="m-0 font-bold text-fg-success-strong">Penyelesaian Sumpah</h6>
            </div>
            <div class="p-6 text-center">
                <p class="text-body mb-6">Klik tombol di bawah ini jika pemohon telah selesai melaksanakan sumpah advokat.</p>
                <form action="{{ route('admin.permohonan.verifikasi', $permohonan->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin menandai pemohon ini telah SELESAI disumpah?')">
                    @csrf
                    <input type="hidden" name="status" value="Selesai">
                    <input type="hidden" name="catatan" value="Telah melaksanakan Sumpah Advokat.">
                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2 rounded-base text-[16px] font-medium bg-neutral-primary-soft text-success shadow-sm hover:shadow-md active:shadow-inset transition-all border border-success hover:text-fg-success-strong">
                        <i class="fas fa-flag-checkered mr-2"></i> Tandai Selesai (Disumpah)
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
