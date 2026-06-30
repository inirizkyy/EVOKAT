@extends('layouts.frontend')
@section('title', 'Persyaratan Sumpah Advokat')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Persyaratan Administrasi</h1>
            <div class="w-24 h-1 bg-brand mx-auto rounded-full"></div>
            <p class="text-body-subtle mt-6 max-w-2xl mx-auto text-lg">Berikut adalah daftar kelengkapan dokumen yang harus dipersiapkan sebelum mengajukan permohonan sumpah advokat.</p>
        </div>
        
        <div class="mb-12">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($persyaratan as $index => $item)
                <div class="bg-neutral-primary-soft rounded-2xl p-6 shadow-sm border border-border-default hover:shadow-lg hover:border-brand/40 transition-all duration-300 relative overflow-hidden group flex flex-col h-full">
                    
                    <!-- Decorative Background Watermark -->
                    <div class="absolute -right-6 -bottom-6 text-brand opacity-5 text-[8rem] group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none select-none z-0">
                        <i class="fa-solid fa-scale-balanced"></i>
                    </div>

                    <div class="flex justify-between items-start mb-4 relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-neutral-primary shadow-inner border border-border-default flex items-center justify-center text-brand text-xl font-bold group-hover:bg-brand group-hover:text-white transition-colors duration-300">
                            {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                        </div>
                        <div>
                            @if($item->is_required)
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-brand/10 border border-brand/20 text-brand shadow-sm tracking-wider uppercase">Wajib</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1.5 rounded-full text-[11px] font-bold bg-neutral-secondary-medium border border-border-default text-body-subtle shadow-sm tracking-wider uppercase">Opsional</span>
                            @endif
                        </div>
                    </div>

                    <div class="relative z-10 flex-grow">
                        <h3 class="font-['Playfair_Display'] text-[19px] font-bold text-heading leading-snug mb-3 group-hover:text-brand transition-colors duration-300">
                            {{ $item->nama_persyaratan }}
                        </h3>
                        <p class="text-[14px] text-body leading-relaxed">
                            {{ $item->deskripsi }}
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-neutral-primary-soft rounded-[2rem] shadow-md border border-border-default overflow-hidden relative">
            <div class="absolute top-0 right-0 w-64 h-64 bg-info/5 rounded-full blur-[50px] -z-10 pointer-events-none"></div>
            
            <div class="p-8 sm:p-12 border-t border-border-default">
                <div class="mb-8 p-6 rounded-2xl bg-neutral-primary border border-border-default shadow-inset flex flex-col sm:flex-row items-center sm:items-start gap-4">
                    <div class="w-12 h-12 rounded-full bg-info/10 text-info flex items-center justify-center shrink-0 border border-info/20">
                        <i class="fa-solid fa-cloud-arrow-up text-xl"></i>
                    </div>
                    <div class="text-center sm:text-left">
                        <h4 class="font-['Playfair_Display'] font-bold text-lg text-heading mb-1">Ketentuan Berkas Digital</h4>
                        <p class="text-[14px] text-body leading-relaxed">Seluruh dokumen persyaratan harus dipindai/di-scan dan diunggah dalam format <strong class="text-info">PDF</strong> dengan ukuran maksimal <strong class="text-brand">2 MB</strong> untuk setiap filenya. Pastikan pindaian terlihat tajam dan terbaca jelas.</p>
                    </div>
                </div>
                
                <div class="text-center mt-10">
                    <a href="{{ url('/permohonan') }}" class="inline-flex justify-center items-center px-10 py-4 rounded-full text-[16px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-1 hover:bg-brand-soft active:shadow-inset active:translate-y-0 transition-all duration-300 border border-brand-softer group">
                        Ajukan Permohonan Sekarang <i class="fa-solid fa-arrow-right ml-3 group-hover:translate-x-1 transition-transform"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
