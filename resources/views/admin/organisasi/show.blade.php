@extends('layouts.admin')
@section('title', 'Detail Organisasi')

@section('actions')
<a href="{{ route('admin.organisasi.index') }}" class="inline-flex items-center px-4 py-2 rounded-base text-[14px] font-medium bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md hover:text-brand-strong active:shadow-inset transition-all border border-brand">
    <i class="fas fa-arrow-left mr-2"></i> Kembali
</a>
@endsection

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Card -->
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default p-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <span class="text-xs font-bold uppercase tracking-wider text-body-subtle">Nama Organisasi Advokat</span>
            <h2 class="text-2xl font-bold text-heading mt-1 flex items-center gap-2">
                <i class="fa-solid fa-building-columns text-brand"></i> {{ $organization->nama_organisasi }}
                @if($organization->singkatan)
                    <span class="text-brand">({{ $organization->singkatan }})</span>
                @endif
            </h2>
        </div>
        <div class="flex gap-3">
            @if($organization->status === 'Aktif')
                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-success-soft text-fg-success-strong">Aktif</span>
            @elseif($organization->status === 'Nonaktif')
                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-neutral-secondary-medium text-body-subtle">Nonaktif</span>
            @else
                <span class="px-3 py-1.5 text-xs font-bold rounded-full bg-warning-soft text-fg-warning">Menunggu Persetujuan</span>
            @endif
            <a href="{{ route('admin.organisasi.edit', $organization->id) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-base text-xs font-bold bg-neutral-primary-soft text-warning border border-border-default hover:shadow-sm transition-all">
                <i class="fa-solid fa-pen mr-1.5"></i> Edit
            </a>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-6">
        <!-- Total Anggota -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-sm border border-border-default p-5 flex flex-col justify-between">
            <span class="text-xs font-bold text-body-subtle uppercase tracking-wider">Total Anggota</span>
            <h3 class="text-2xl font-bold text-heading mt-2">{{ $organization->pemohons_count }}</h3>
        </div>

        <!-- Total Permohonan -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-sm border border-border-default p-5 flex flex-col justify-between">
            <span class="text-xs font-bold text-body-subtle uppercase tracking-wider">Total Permohonan</span>
            <h3 class="text-2xl font-bold text-heading mt-2">{{ $organization->permohonans_count }}</h3>
        </div>

        <!-- Sedang Diverifikasi -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-sm border border-border-default p-5 flex flex-col justify-between border-l-4 border-warning">
            <span class="text-xs font-bold text-fg-warning uppercase tracking-wider">Sedang Diverifikasi</span>
            <h3 class="text-2xl font-bold text-heading mt-2">{{ $stats['sedang_diverifikasi'] }}</h3>
        </div>

        <!-- Disetujui -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-sm border border-border-default p-5 flex flex-col justify-between border-l-4 border-brand">
            <span class="text-xs font-bold text-brand uppercase tracking-wider">Disetujui</span>
            <h3 class="text-2xl font-bold text-heading mt-2">{{ $stats['disetujui'] }}</h3>
        </div>

        <!-- Ditolak -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-sm border border-border-default p-5 flex flex-col justify-between border-l-4 border-danger">
            <span class="text-xs font-bold text-fg-danger uppercase tracking-wider">Ditolak</span>
            <h3 class="text-2xl font-bold text-heading mt-2">{{ $stats['ditolak'] }}</h3>
        </div>

        <!-- Sudah Disumpah -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-sm border border-border-default p-5 flex flex-col justify-between border-l-4 border-secondary">
            <span class="text-xs font-bold text-secondary uppercase tracking-wider">Sudah Disumpah</span>
            <h3 class="text-2xl font-bold text-heading mt-2">{{ $stats['sudah_disumpah'] }}</h3>
        </div>
    </div>

    <!-- Applicants Table -->
    <div class="bg-white/60 backdrop-blur-xl rounded-xl shadow-sm border border-border-default overflow-hidden">
        <div class="py-5 px-6 border-b border-border-default bg-neutral-primary-soft">
            <h6 class="m-0 font-bold text-heading text-base flex items-center gap-2">
                <i class="fa-solid fa-users text-brand"></i> Daftar Anggota Pemohon
            </h6>
        </div>
        <div class="p-6">
            <div class="overflow-x-auto w-full">
                <table class="w-full text-left whitespace-nowrap text-[14px] text-body">
                    <thead class="bg-neutral-secondary-soft border-b border-border-default text-body font-medium">
                        <tr>
                            <th class="px-6 py-4 font-medium w-[80px]">No</th>
                            <th class="px-6 py-4 font-medium">Nama Pemohon</th>
                            <th class="px-6 py-4 font-medium">Nomor Registrasi</th>
                            <th class="px-6 py-4 font-medium">Tanggal Permohonan</th>
                            <th class="px-6 py-4 font-medium">Status Verifikasi</th>
                            <th class="px-6 py-4 font-medium w-[100px]">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/60 divide-y divide-border-default">
                        @forelse($pemohons as $index => $pemohon)
                            <tr>
                                <td class="px-6 py-4">{{ $pemohons->firstItem() + $index }}</td>
                                <td class="px-6 py-4 font-bold text-heading">{{ $pemohon->nama_lengkap }}</td>
                                <td class="px-6 py-4">{{ $pemohon->permohonan->nomor_permohonan ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $pemohon->permohonan ? \Carbon\Carbon::parse($pemohon->permohonan->tanggal_pengajuan)->translatedFormat('d M Y') : '-' }}</td>
                                <td class="px-6 py-4">
                                    @if($pemohon->status_verifikasi === 'Disetujui')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-success-soft text-fg-success-strong">Disetujui</span>
                                    @elseif($pemohon->status_verifikasi === 'Ditolak')
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-danger-soft text-fg-danger-strong">Ditolak</span>
                                    @else
                                        <span class="px-2.5 py-1 text-xs font-bold rounded-full bg-warning-soft text-fg-warning">Pending</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <a href="{{ route('admin.permohonan.member-show', $pemohon->id) }}" class="inline-flex items-center justify-center w-8 h-8 rounded-base bg-neutral-primary-soft text-brand shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-body-subtle italic">Belum ada anggota terdaftar untuk organisasi ini.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if($pemohons->hasPages())
                <div class="mt-6">
                    {{ $pemohons->links() }}
                </div>
            @endif
        </div>
    </div>

</div>
@endsection
