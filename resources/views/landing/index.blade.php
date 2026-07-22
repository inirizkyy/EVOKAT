@extends('layouts.frontend')
@section('title', 'Beranda')

@push('styles')
<style>
    html, body {
        height: 100vh;
        overflow: hidden;
    }
</style>
@endpush

@section('content')
<!-- Hero Section -->
<section class="relative min-h-[calc(100vh-88px)] flex items-center overflow-hidden">

    <!-- Full-width Building Background (blurred) -->
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat scale-105"
         style="background-image: url('{{ asset('img/gedungpt.png') }}'); filter: blur(3px);"></div>

    <!-- White fade overlay on the left so dark text is readable -->
    <div class="absolute inset-0" style="background: linear-gradient(to right, rgba(255,255,255,0.92) 0%, rgba(255,255,255,0.75) 40%, rgba(255,255,255,0.15) 70%, rgba(255,255,255,0) 100%);"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 w-full py-16 lg:py-0">
        <div class="max-w-xl">
            <!-- Badge -->
            <div class="inline-flex items-center gap-3 px-4 py-2 rounded-full bg-white shadow-sm border border-border-default mb-6">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-brand"></span>
                </span>
                <span class="text-xs font-bold tracking-widest text-heading uppercase">Pengadilan Tinggi Tanjungkarang</span>
            </div>

            <!-- Heading -->
            <h1 class="font-['Playfair_Display'] text-5xl md:text-6xl lg:text-7xl font-bold text-heading leading-[1.1] mb-6">
                Sistem Digitalisasi <br />
                <span class="text-brand relative inline-block">
                    Sumpah Advokat
                    <svg class="absolute w-full h-3 -bottom-1 left-0 text-brand opacity-40" viewBox="0 0 100 10" preserveAspectRatio="none">
                        <path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="4" fill="none"/>
                    </svg>
                </span>
            </h1>

            <!-- Description -->
            <p class="text-base text-body leading-relaxed mb-10 border-l-4 border-brand pl-8 py-1 max-w-md">
                Akses layanan permohonan sumpah profesi advokat dengan lebih mudah, cepat, dan transparan. Wujudkan pelayanan peradilan yang prima dan modern.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row items-start gap-4">
                <a href="{{ url('/permohonan') }}"
                   class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-lg shadow-brand/30 hover:shadow-xl hover:bg-brand-strong hover:-translate-y-0.5 transition-all duration-300">
                    Mulai Pengajuan <i class="fa-solid fa-arrow-right-long ml-3"></i>
                </a>
                <a href="{{ url('/alur') }}"
                   class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-white text-heading shadow-md hover:shadow-lg hover:-translate-y-0.5 transition-all duration-300 border border-border-default">
                    <i class="fa-solid fa-book-open mr-3 text-brand"></i> Pelajari Prosedur
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
