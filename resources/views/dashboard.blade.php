@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6 mb-6">
    
    <!-- Card: Total Permohonan -->
    <div class="bg-neutral-primary-soft rounded-base shadow-md border-l-4 border-brand p-6">
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
    <div class="bg-neutral-primary-soft rounded-base shadow-md border-l-4 border-warning p-6">
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
    <div class="bg-neutral-primary-soft rounded-base shadow-md border-l-4 border-success p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-fg-success uppercase tracking-wider mb-1">Disetujui & Dijadwalkan</p>
                <h3 class="text-2xl font-bold text-heading m-0">{{ \App\Models\Permohonan::whereIn('status', ['Disetujui', 'Dijadwalkan Sumpah'])->count() }}</h3>
            </div>
            <div class="w-[48px] h-[48px] rounded-full bg-success-soft flex items-center justify-center">
                <i class="fa-solid fa-check-circle text-2xl text-fg-success"></i>
            </div>
        </div>
    </div>
    
    <!-- Card: Selesai -->
    <div class="bg-neutral-primary-soft rounded-base shadow-md border-l-4 border-secondary p-6">
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

</div>

@endsection
