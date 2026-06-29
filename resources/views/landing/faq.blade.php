@extends('layouts.frontend')
@section('title', 'FAQ (Tanya Jawab)')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Frequently Asked Questions</h1>
            <div class="w-24 h-1 bg-brand mx-auto rounded-full"></div>
            <p class="text-body-subtle mt-6 max-w-2xl mx-auto text-lg">Pertanyaan yang sering diajukan terkait layanan E-Advokat Pengadilan Tinggi Tanjungkarang.</p>
        </div>
        
        <div class="space-y-4 relative" x-data="{ active: null }">
            <!-- Decorative Law Book Watermark -->
            <div class="absolute -left-20 top-20 text-brand opacity-5 text-[20rem] pointer-events-none select-none -rotate-12 -z-10">
                <i class="fa-solid fa-book-open-reader"></i>
            </div>

            @forelse($faqs as $index => $faq)
            <div class="bg-white rounded-2xl border border-border-default shadow-sm hover:shadow-md transition-all overflow-hidden group"
                 x-data="{ id: {{ $index }}, get isActive() { return this.active === this.id } }"
                 :class="isActive ? 'border-brand/40 shadow-md ring-1 ring-brand/10' : ''">
                
                <button @click="active = isActive ? null : id" 
                        class="w-full text-left px-6 sm:px-8 py-5 flex items-center justify-between focus:outline-none focus-visible:ring-2 focus-visible:ring-brand focus-visible:ring-opacity-50">
                    <div class="flex items-center gap-4">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-bold text-sm transition-colors duration-300 shrink-0"
                             :class="isActive ? 'bg-brand text-white' : 'bg-neutral-primary-soft text-brand border border-border-default group-hover:bg-brand/10'">
                            Q
                        </div>
                        <span class="font-bold text-[16px] transition-colors duration-300" :class="isActive ? 'text-brand' : 'text-heading group-hover:text-brand'">{{ $faq->pertanyaan }}</span>
                    </div>
                    <span class="w-8 h-8 rounded-full flex items-center justify-center flex-shrink-0 transition-all duration-300" 
                          :class="isActive ? 'bg-brand/10 text-brand rotate-180' : 'bg-transparent text-body-subtle group-hover:text-brand group-hover:bg-brand/5'">
                        <i class="fa-solid fa-chevron-down text-sm"></i>
                    </span>
                </button>
                
                <div x-show="isActive" 
                     x-collapse 
                     x-cloak
                     class="px-6 sm:px-8 pb-6 pt-0">
                    <div class="text-[15px] text-body leading-relaxed pt-5 border-t border-border-default/50 relative pl-12">
                        <div class="absolute left-0 top-5 w-8 h-8 rounded-full bg-success/10 text-success border border-success/20 flex items-center justify-center font-bold text-sm shrink-0">
                            A
                        </div>
                        {!! nl2br(e($faq->jawaban)) !!}
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-16 bg-white rounded-2xl border border-border-default shadow-sm relative overflow-hidden">
                <div class="absolute -right-10 -bottom-10 text-brand opacity-5 text-[10rem] pointer-events-none select-none z-0">
                    <i class="fa-solid fa-folder-open"></i>
                </div>
                <div class="w-20 h-20 rounded-full bg-neutral-primary-soft shadow-inner border border-border-default mx-auto flex items-center justify-center text-body-subtle mb-5 text-3xl relative z-10">
                    <i class="fa-regular fa-comments"></i>
                </div>
                <p class="text-heading font-bold text-xl mb-2 relative z-10">Belum Ada FAQ</p>
                <p class="text-body-subtle text-[15px] relative z-10">Pertanyaan dan jawaban akan segera ditambahkan di sini.</p>
            </div>
            @endforelse
        </div>
        
    </div>
</section>

<!-- Include Alpine.js Collapse Plugin if not included globally (it's best to include via CDN in layout but we can ensure it works without collapse plugin too, or use simple x-show if x-collapse fails. Let's provide basic CSS for x-cloak just in case) -->
<style>
    [x-cloak] { display: none !important; }
</style>
@endsection
