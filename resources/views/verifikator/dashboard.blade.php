@extends('layouts.admin')
@section('title', 'Dashboard Verifikator')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-gradient-to-r from-brand to-brand-strong text-white rounded-base p-6 shadow-md">
        <h4 class="text-xl font-bold mb-1">Selamat Datang, {{ Auth::user()->name }}!</h4>
        <p class="text-sm opacity-90">Anda masuk sebagai <strong>{{ strtoupper(Auth::user()->role) }}</strong>. Gunakan panel ini untuk memverifikasi dokumen permohonan sumpah advokat.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Card: Queue to Verify -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-warning p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-fg-warning uppercase tracking-wider mb-1">Antrean Verifikasi Anda</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countQueue }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-warning-soft flex items-center justify-center">
                    <i class="fa-solid fa-clock text-2xl text-fg-warning"></i>
                </div>
            </div>
        </div>

        <!-- Card: Total Permohonan -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-info p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-fg-info uppercase tracking-wider mb-1">Total Permohonan</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countAll }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-info-soft flex items-center justify-center">
                    <i class="fa-solid fa-file-lines text-2xl text-fg-info"></i>
                </div>
            </div>
        </div>

        <!-- Card: Selesai -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-success p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-fg-success uppercase tracking-wider mb-1">Selesai (Disumpah)</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countSelesai }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-success-soft flex items-center justify-center">
                    <i class="fa-solid fa-flag-checkered text-2xl text-fg-success"></i>
                </div>
            </div>
        </div>

        <!-- Card: Buku Registrasi -->
        <div class="bg-white/60 backdrop-blur-xl rounded-base shadow-md border-l-4 border-secondary p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-xs font-bold text-secondary uppercase tracking-wider mb-1">Buku Registrasi</p>
                    <h3 class="text-2xl font-bold text-heading m-0">{{ $countBukuReg }}</h3>
                </div>
                <div class="w-[48px] h-[48px] rounded-full bg-neutral-secondary-medium flex items-center justify-center shadow-inset">
                    <i class="fa-solid fa-address-book text-2xl text-secondary"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Action Card -->
    <div class="bg-white rounded-xl shadow-sm border border-border-default p-6">
        <h5 class="font-bold text-heading text-base mb-4 flex items-center gap-2">
            <i class="fa-solid fa-circle-info text-brand"></i> Petunjuk Verifikator
        </h5>
        <ul class="list-disc list-outside ml-6 space-y-2 text-sm text-body font-medium">
            <li>Buka menu <strong>Permohonan Sumpah</strong> di sidebar untuk melihat daftar permohonan yang memerlukan verifikasi Anda.</li>
            <li>Klik tombol <strong>Detail</strong> pada setiap baris untuk memeriksa berkas pendukung pemohon.</li>
            <li>Klik <strong>Setujui</strong> jika seluruh dokumen lengkap dan sesuai. Ini akan mengirim permohonan ke tingkat verifikasi berikutnya.</li>
            <li>Klik <strong>Tolak</strong> jika terdapat ketidaksesuaian dokumen. Anda diwajibkan menuliskan catatan penolakan yang rinci.</li>
            <li>Buku Registrasi Advokat dapat diakses secara <strong>read-only</strong> untuk melihat data advokat yang telah disumpah.</li>
        </ul>
    </div>
</div>
@endsection
