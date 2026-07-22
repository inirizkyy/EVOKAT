@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-6">
    
    <!-- Card: Total Permohonan -->
    <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-brand p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-fg-brand uppercase tracking-wider mb-1">Total Permohonan</p>
                <h3 class="text-2xl font-bold text-heading m-0">{{ \App\Models\Permohonan::count() }}</h3>
            </div>
            <div class="w-[48px] h-[48px] rounded-full bg-brand-softer flex items-center justify-center">
                <i class="fa-solid fa-file-lines text-2xl text-fg-brand"></i>
            </div>
        </div>
    </div>
    
    <!-- Card: Menunggu Verifikasi -->
    <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-warning p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-fg-warning uppercase tracking-wider mb-1">Menunggu Verifikasi</p>
                <h3 class="text-2xl font-bold text-heading m-0">{{ \App\Models\Permohonan::where('status', 'Menunggu Verifikasi')->count() }}</h3>
            </div>
            <div class="w-[48px] h-[48px] rounded-full bg-warning-soft flex items-center justify-center">
                <i class="fa-solid fa-clock text-2xl text-fg-warning"></i>
            </div>
        </div>
    </div>
    
    <!-- Card: Disetujui & Dijadwalkan -->
    <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-success p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-fg-success uppercase tracking-wider mb-1">Disetujui & Dijadwalkan</p>
                <h3 class="text-2xl font-bold text-heading m-0">{{ \App\Models\Permohonan::whereIn('status', ['Siap Penjadwalan Pengecekan Berkas Fisik', 'Menentukan Jadwal Verifikasi', 'Menentukan Jadwal Sumpah', 'Proses Pembuatan Surat', 'Surat Selesai', 'Disetujui', 'Dijadwalkan Sumpah'])->count() }}</h3>
            </div>
            <div class="w-[48px] h-[48px] rounded-full bg-success-soft flex items-center justify-center">
                <i class="fa-solid fa-check-circle text-2xl text-fg-success"></i>
            </div>
        </div>
    </div>
    
    <!-- Card: Selesai -->
    <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-secondary p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Selesai (Disumpah)</p>
                <h3 class="text-2xl font-bold text-heading m-0">{{ \App\Models\Permohonan::where('status', 'Selesai')->count() }}</h3>
            </div>
            <div class="w-[48px] h-[48px] rounded-full flex items-center justify-center shadow-inset">
                <i class="fa-solid fa-flag-checkered text-2xl text-secondary"></i>
            </div>
        </div>
    </div>

    <!-- Card: Total Organisasi -->
    <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-info p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-fg-info uppercase tracking-wider mb-1">Total Organisasi</p>
                <h3 class="text-2xl font-bold text-heading m-0">{{ \App\Models\Organization::count() }}</h3>
            </div>
            <div class="w-[48px] h-[48px] rounded-full bg-info-soft flex items-center justify-center">
                <i class="fa-solid fa-building-columns text-2xl text-fg-info"></i>
            </div>
        </div>
    </div>

</div>

<!-- Top 5 Organizations and Quick Actions -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
    <div class="lg:col-span-2 bg-white/60 backdrop-blur-xl rounded-base shadow-md border border-border-default p-6 flex flex-col">
        <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3 mb-4 flex items-center gap-2">
            <i class="fa-solid fa-ranking-star text-brand"></i> 5 Organisasi dengan Permohonan Terbanyak
        </h6>
        <div class="space-y-4 flex-1 flex flex-col justify-center">
            @php
                $topOrganizations = \App\Models\Organization::withCount('permohonans')
                    ->orderBy('permohonans_count', 'desc')
                    ->take(5)
                    ->get();
                $maxPermohonan = $topOrganizations->max('permohonans_count') ?: 1;
            @endphp
            @forelse($topOrganizations as $index => $org)
                @php
                    $percent = round(($org->permohonans_count / $maxPermohonan) * 100);
                @endphp
                <div class="space-y-1">
                    <div class="flex justify-between items-center text-sm font-semibold">
                        <span class="text-heading">{{ $index + 1 }}. {{ $org->nama_organisasi }} @if($org->singkatan) ({{ $org->singkatan }}) @endif</span>
                        <span class="text-brand font-bold">{{ $org->permohonans_count }} Permohonan</span>
                    </div>
                    <div class="w-full bg-neutral-secondary-soft rounded-full h-3">
                        <div class="bg-brand h-3 rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                    </div>
                </div>
            @empty
                <p class="text-sm text-body-subtle italic text-center py-4">Belum ada data organisasi.</p>
            @endforelse
        </div>
    </div>
    
    <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border border-border-default p-6 flex flex-col justify-between">
        <div>
            <h6 class="m-0 font-bold text-heading text-base border-b border-border-default pb-3 mb-4">
                Aksi Cepat
            </h6>
            <p class="text-sm text-body-subtle mb-6">Gunakan menu ini untuk mengakses langsung pengelolaan master data organisasi mitra atau pendaftaran permohonan sumpah baru.</p>
        </div>
        <div class="flex flex-col gap-3">
            <a href="{{ route('admin.organisasi.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-base text-sm font-bold bg-neutral-primary-soft text-brand shadow-sm border border-brand hover:shadow-md transition-all">
                <i class="fa-solid fa-plus mr-2"></i> Tambah / Kelola Organisasi
            </a>
            <a href="{{ route('admin.permohonan.index') }}" class="inline-flex items-center justify-center px-4 py-2.5 rounded-base text-sm font-bold bg-brand text-white shadow-sm hover:shadow-md transition-all">
                <i class="fa-solid fa-file-invoice mr-2"></i> Kelola Permohonan Sumpah
            </a>
        </div>
    </div>
</div>

@endsection
