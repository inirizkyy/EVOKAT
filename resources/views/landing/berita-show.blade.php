@extends('layouts.frontend')
@section('title', $berita->judul)

@section('content')
<!-- Header Page -->
<section class="relative pt-32 pb-20 bg-neutral-primary-soft overflow-hidden">
    <!-- Decorative background elements -->
    <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-30 mix-blend-overlay"></div>
    <div class="absolute top-0 right-0 w-1/2 h-full bg-gradient-to-l from-brand/5 to-transparent pointer-events-none"></div>
    
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-brand/10 text-brand text-xs font-bold uppercase tracking-widest mb-6 border border-brand/20">
            <i class="fa-solid fa-newspaper"></i> Berita & Pengumuman
        </div>
        <h1 class="font-['Playfair_Display'] text-3xl md:text-5xl font-bold text-heading leading-tight mb-6">
            {{ $berita->judul }}
        </h1>
        <div class="flex items-center justify-center gap-4 text-sm font-medium text-body-subtle">
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-calendar text-brand"></i>
                {{ \Carbon\Carbon::parse($berita->published_at)->translatedFormat('d F Y') }}
            </div>
            <div class="w-1.5 h-1.5 rounded-full bg-border-strong"></div>
            <div class="flex items-center gap-2">
                <i class="fa-regular fa-user text-brand"></i>
                Admin PT Tanjungkarang
            </div>
        </div>
    </div>
</section>

<!-- Content Section -->
<section class="py-20 bg-transparent">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        @if($berita->thumbnail)
        <div class="w-full h-[400px] md:h-[500px] rounded-[2rem] overflow-hidden shadow-xl border border-border-default mb-16 relative group">
            <img src="{{ asset('storage/'.$berita->thumbnail) }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" alt="{{ $berita->judul }}">
        </div>
        @else
        <div class="w-full h-[400px] md:h-[500px] rounded-[2rem] overflow-hidden shadow-xl border border-border-default mb-16 relative group bg-neutral-primary flex items-center justify-center">
            <i class="fa-solid fa-newspaper text-9xl text-border-strong opacity-50"></i>
        </div>
        @endif

        <div class="bg-white rounded-[2rem] p-8 md:p-12 shadow-sm border border-border-default">
            <div class="prose prose-lg max-w-none text-body leading-relaxed 
                prose-headings:font-['Playfair_Display'] prose-headings:font-bold prose-headings:text-heading 
                prose-p:mb-6 prose-a:text-brand prose-a:font-semibold hover:prose-a:text-brand-strong 
                prose-img:rounded-2xl prose-img:shadow-md">
                {!! $berita->isi !!}
            </div>

            <div class="mt-16 pt-8 border-t border-border-default flex justify-between items-center">
                <a href="{{ url('/') }}" class="inline-flex items-center gap-2 px-6 py-3 rounded-full bg-neutral-primary-soft hover:bg-neutral-primary text-body hover:text-heading font-semibold transition-colors border border-border-default shadow-sm">
                    <i class="fa-solid fa-arrow-left"></i> Kembali ke Beranda
                </a>

                <!-- Share buttons (optional UI) -->
                <div class="flex items-center gap-3">
                    <span class="text-sm font-bold text-body-subtle uppercase tracking-wider hidden sm:inline-block mr-2">Bagikan:</span>
                    <button class="w-10 h-10 rounded-full bg-neutral-primary-soft hover:bg-brand hover:text-white border border-border-default text-body flex items-center justify-center transition-all shadow-sm">
                        <i class="fa-brands fa-whatsapp"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-neutral-primary-soft hover:bg-[#1877F2] hover:text-white border border-border-default text-body flex items-center justify-center transition-all shadow-sm">
                        <i class="fa-brands fa-facebook-f"></i>
                    </button>
                    <button class="w-10 h-10 rounded-full bg-neutral-primary-soft hover:bg-[#1DA1F2] hover:text-white border border-border-default text-body flex items-center justify-center transition-all shadow-sm">
                        <i class="fa-brands fa-twitter"></i>
                    </button>
                </div>
            </div>
        </div>
        
    </div>
</section>
@endsection
