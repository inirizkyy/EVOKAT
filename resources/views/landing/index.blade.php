@extends('layouts.frontend')
@section('title', 'Beranda')

@section('content')
<!-- Hero Section -->
<section class="relative pt-20 pb-32 lg:pt-32 lg:pb-48 bg-transparent overflow-hidden">
    <!-- Premium Gradient Background -->
    <div class="absolute inset-0 bg-gradient-to-br from-neutral-primary via-neutral-primary-soft to-brand/5 opacity-80"></div>
    <div class="absolute right-0 top-0 w-2/3 h-full opacity-10 pointer-events-none" style="background-image: radial-gradient(circle at top right, var(--color-brand) 0%, transparent 70%);"></div>
    
    <!-- Majestic Watermark -->
    <div class="absolute right-[-5%] top-1/2 -translate-y-1/2 text-brand opacity-5 pointer-events-none select-none z-0">
        <i class="fa-solid fa-scale-unbalanced text-[40rem] rotate-12"></i>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-8 items-center">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-3 px-5 py-2.5 rounded-full bg-white shadow-sm border border-border-default mb-8">
                    <span class="relative flex h-3 w-3">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-brand opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-3 w-3 bg-brand"></span>
                    </span>
                    <span class="text-sm font-bold tracking-wide text-heading uppercase">Pengadilan Tinggi Tanjungkarang</span>
                </div>
                
                <h1 class="font-['Playfair_Display'] text-5xl md:text-6xl lg:text-7xl font-bold text-heading leading-[1.1] mb-8">
                    Sistem Digitalisasi <br />
                    <span class="text-brand relative inline-block">
                        Sumpah Advokat
                        <svg class="absolute w-full h-3 -bottom-1 left-0 text-brand opacity-30" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 10 100 5" stroke="currentColor" stroke-width="4" fill="none"/></svg>
                    </span>
                </h1>
                
                <p class="text-lg text-body leading-relaxed mb-10 border-l-4 border-brand pl-6 bg-white/50 py-2 pr-4 rounded-r-2xl backdrop-blur-sm">
                    Akses layanan permohonan sumpah profesi advokat dengan lebih mudah, cepat, dan transparan. Wujudkan pelayanan peradilan yang prima dan modern.
                </p>
                
                <div class="flex flex-col sm:flex-row items-center gap-4">
                    <a href="{{ url('/permohonan') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 rounded-full text-[16px] font-bold bg-brand text-white shadow-lg shadow-brand/30 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                        Mulai Pengajuan <i class="fa-solid fa-arrow-right-long ml-3"></i>
                    </a>
                    <a href="{{ url('/alur') }}" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-4 rounded-full text-[16px] font-bold bg-white text-heading shadow-md hover:shadow-lg hover:-translate-y-1 transition-all duration-300 border border-border-default">
                        <i class="fa-solid fa-book-open mr-3 text-brand"></i> Pelajari Prosedur
                    </a>
                </div>
            </div>
            
            <div class="hidden lg:block relative">
                <div class="relative w-full aspect-square rounded-[3rem] bg-white shadow-2xl p-4 border border-border-default transform rotate-3 hover:rotate-0 transition-transform duration-700 flex items-center justify-center">
                    <div class="absolute inset-0 border border-brand opacity-20 rounded-[3rem] m-2 pointer-events-none"></div>
                    <div class="w-full h-full rounded-[2.5rem] bg-neutral-primary-soft flex items-center justify-center p-12 shadow-inset">
                        <i class="fa-solid fa-scale-balanced text-[12rem] xl:text-[16rem] text-brand drop-shadow-md"></i>
                    </div>
                    
                    <!-- Floating Stat -->
                    <div class="absolute -left-10 top-20 bg-white p-5 rounded-2xl shadow-xl border border-border-default flex items-center gap-4 animate-[bounce_4s_infinite]">
                        <div class="w-12 h-12 rounded-full bg-brand/10 text-brand flex items-center justify-center text-xl border border-brand/20">
                            <i class="fa-solid fa-gavel"></i>
                        </div>
                        <div>
                            <p class="text-xs text-body-subtle font-semibold uppercase">Layanan Informasi</p>
                            <p class="text-lg font-bold text-heading">Terpadu</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Statistik -->
<section class="py-0 relative z-20">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-20">
        <div class="bg-white rounded-[2rem] shadow-xl border border-border-default p-6 sm:p-10 relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-brand-softer via-brand to-brand-strong"></div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8 divide-y sm:divide-y-0 sm:divide-x divide-border-default">
                
                <!-- Total Permohonan -->
                <div class="text-center px-4 py-4 sm:py-0 group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-neutral-primary shadow-inner border border-border-default text-brand mb-4 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-file-lines text-2xl"></i>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold text-heading mb-1">{{ $stats['total'] }}</h2>
                    <p class="text-[13px] text-body-subtle font-bold uppercase tracking-widest">Total Permohonan</p>
                </div>
                
                <!-- Disetujui -->
                <div class="text-center px-4 py-4 sm:py-0 group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-neutral-primary shadow-inner border border-border-default text-success mb-4 group-hover:scale-110 group-hover:bg-success group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-check-double text-2xl"></i>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold text-heading mb-1">{{ $stats['disetujui'] }}</h2>
                    <p class="text-[13px] text-body-subtle font-bold uppercase tracking-widest">Disetujui</p>
                </div>
                
                <!-- Dijadwalkan Sumpah -->
                <div class="text-center px-4 py-4 sm:py-0 group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-neutral-primary shadow-inner border border-border-default text-warning mb-4 group-hover:scale-110 group-hover:bg-warning group-hover:text-white transition-all duration-300">
                        <i class="fa-regular fa-calendar-check text-2xl"></i>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold text-heading mb-1">{{ $stats['dijadwalkan'] }}</h2>
                    <p class="text-[13px] text-body-subtle font-bold uppercase tracking-widest">Dijadwalkan Sumpah</p>
                </div>
                
                <!-- Sumpah Selesai -->
                <div class="text-center px-4 py-4 sm:py-0 group">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-2xl bg-neutral-primary shadow-inner border border-border-default text-info mb-4 group-hover:scale-110 group-hover:bg-info group-hover:text-white transition-all duration-300">
                        <i class="fa-solid fa-flag-checkered text-2xl"></i>
                    </div>
                    <h2 class="text-4xl md:text-5xl font-['Playfair_Display'] font-bold text-heading mb-1">{{ $stats['selesai'] }}</h2>
                    <p class="text-[13px] text-body-subtle font-bold uppercase tracking-widest">Sumpah Selesai</p>
                </div>
                
            </div>
        </div>
    </div>
</section>

<!-- Berita Terbaru -->
<section class="py-24 bg-transparent">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col md:flex-row md:items-end justify-between mb-12 gap-6">
            <div class="max-w-2xl">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand/10 text-brand text-xs font-bold uppercase tracking-widest mb-4 border border-brand/20">
                    <i class="fa-solid fa-newspaper"></i> Publikasi
                </div>
                <h2 class="font-['Playfair_Display'] text-4xl font-bold text-heading">Berita & Pengumuman</h2>
            </div>
            <a href="#" class="inline-flex items-center gap-2 text-[15px] font-bold text-brand hover:text-brand-strong transition-colors group">
                Lihat Semua Berita <i class="fa-solid fa-arrow-right transform group-hover:translate-x-1 transition-transform"></i>
            </a>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($beritaTerbaru as $berita)
            <article class="bg-white rounded-3xl shadow-sm border border-border-default overflow-hidden group hover:shadow-xl transition-all duration-500 flex flex-col hover:-translate-y-2 hover:border-brand/30">
                <div class="h-56 overflow-hidden relative">
                    @if($berita->thumbnail)
                        <img src="{{ asset('storage/'.$berita->thumbnail) }}" class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-700" alt="{{ $berita->judul }}">
                    @else
                        <div class="w-full h-full bg-neutral-primary flex items-center justify-center transform group-hover:scale-105 transition-transform duration-700">
                            <i class="fa-solid fa-newspaper text-5xl text-border-strong opacity-50"></i>
                        </div>
                    @endif
                    <div class="absolute top-4 left-4">
                        <span class="px-4 py-1.5 bg-white/90 backdrop-blur-sm border border-border-default rounded-full text-xs font-bold text-brand shadow-sm">Berita Utama</span>
                    </div>
                </div>
                <div class="p-8 flex flex-col flex-grow relative">
                    <div class="flex items-center text-xs text-body-subtle font-medium tracking-wide uppercase mb-4">
                        <i class="fa-regular fa-calendar mr-2 text-brand"></i>
                        {{ \Carbon\Carbon::parse($berita->published_at)->format('d F Y') }}
                    </div>
                    <h3 class="text-xl font-bold text-heading mb-4 line-clamp-2 leading-snug group-hover:text-brand transition-colors">{{ $berita->judul }}</h3>
                    <p class="text-[15px] text-body line-clamp-3 mb-6 leading-relaxed">{{ Str::limit(strip_tags($berita->isi), 120) }}</p>
                    
                    <div class="mt-auto pt-5 border-t border-border-default/50">
                        <a href="{{ route('berita.show', $berita->slug) }}" class="inline-flex items-center text-sm font-bold text-brand group-hover:text-brand-strong">
                            Baca Selengkapnya <i class="fa-solid fa-arrow-right-long ml-2 transform group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </article>
            @empty
            <div class="col-span-full py-20 text-center bg-white rounded-3xl border border-border-default border-dashed">
                <div class="w-20 h-20 mx-auto bg-neutral-primary rounded-full flex items-center justify-center text-body-subtle mb-5 border border-border-default">
                    <i class="fa-regular fa-newspaper text-3xl"></i>
                </div>
                <h3 class="text-xl font-bold text-heading mb-2">Belum ada publikasi</h3>
                <p class="text-body-subtle">Berita dan informasi terbaru akan segera hadir.</p>
            </div>
            @endforelse
        </div>
    </div>
</section>
@endsection
