@extends('layouts.frontend')
@section('title', 'Kontak Kami')

@section('content')
<section class="py-12 lg:py-20 bg-transparent min-h-[calc(100vh-88px)]">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center mb-12">
            <h1 class="font-['Playfair_Display'] text-3xl md:text-4xl font-bold text-heading mb-4">Kontak Kami</h1>
            <div class="w-24 h-1 bg-brand mx-auto rounded-full"></div>
            <p class="text-body-subtle mt-6 max-w-2xl mx-auto text-lg">Hubungi kami jika Anda memiliki pertanyaan atau membutuhkan bantuan terkait layanan E-Advokat.</p>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
            <!-- Alamat -->
            <div class="bg-neutral-primary-soft rounded-[2rem] p-8 text-center shadow-sm border border-border-default hover:shadow-lg transition-all group duration-300 flex flex-col items-center relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-brand opacity-5 text-[8rem] pointer-events-none select-none group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 z-0">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="w-20 h-20 rounded-full bg-neutral-primary shadow-inset border border-border-default flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300 relative z-10 text-brand">
                    <i class="fa-solid fa-location-dot text-3xl"></i>
                </div>
                <h4 class="font-['Playfair_Display'] text-xl font-bold text-heading mb-3 relative z-10 group-hover:text-brand transition-colors">Alamat Instansi</h4>
                <p class="text-[15px] text-body-subtle leading-relaxed flex-grow relative z-10">{{ $pengaturan->alamat ?? 'Jl. Cut Mutia No.42, Bandar Lampung' }}</p>
            </div>
            
            <!-- Telepon -->
            <div class="bg-neutral-primary-soft rounded-[2rem] p-8 text-center shadow-sm border border-border-default hover:shadow-lg transition-all group duration-300 flex flex-col items-center relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-brand opacity-5 text-[8rem] pointer-events-none select-none group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 z-0">
                    <i class="fa-solid fa-phone-volume"></i>
                </div>
                <div class="w-20 h-20 rounded-full bg-neutral-primary shadow-inset border border-border-default flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300 relative z-10 text-brand">
                    <i class="fa-solid fa-phone text-3xl"></i>
                </div>
                <h4 class="font-['Playfair_Display'] text-xl font-bold text-heading mb-3 relative z-10 group-hover:text-brand transition-colors">Telepon</h4>
                <p class="text-[15px] text-body-subtle leading-relaxed flex-grow font-semibold text-brand-strong relative z-10">{{ $pengaturan->telepon ?? '(0721) 482431' }}</p>
            </div>
            
            <!-- Email -->
            <div class="bg-neutral-primary-soft rounded-[2rem] p-8 text-center shadow-sm border border-border-default hover:shadow-lg transition-all group duration-300 flex flex-col items-center relative overflow-hidden">
                <div class="absolute -right-4 -bottom-4 text-brand opacity-5 text-[8rem] pointer-events-none select-none group-hover:scale-110 group-hover:-rotate-12 transition-transform duration-500 z-0">
                    <i class="fa-solid fa-envelope-open-text"></i>
                </div>
                <div class="w-20 h-20 rounded-full bg-neutral-primary shadow-inset border border-border-default flex items-center justify-center mb-6 group-hover:scale-110 group-hover:bg-brand group-hover:text-white transition-all duration-300 relative z-10 text-brand">
                    <i class="fa-solid fa-envelope text-3xl"></i>
                </div>
                <h4 class="font-['Playfair_Display'] text-xl font-bold text-heading mb-3 relative z-10 group-hover:text-brand transition-colors">Email</h4>
                <p class="text-[15px] text-body-subtle leading-relaxed flex-grow font-semibold text-brand-strong relative z-10">{{ $pengaturan->email ?? 'info@pt-tanjungkarang.go.id' }}</p>
            </div>
        </div>
        
        <div class="bg-neutral-primary-soft rounded-[2rem] p-2 shadow-md border border-border-default overflow-hidden relative group">
            <div class="absolute inset-0 border-4 border-neutral-primary-soft rounded-[2rem] z-10 pointer-events-none"></div>
            <div class="w-full h-[400px] md:h-[500px] rounded-2xl overflow-hidden shadow-inset bg-neutral-secondary-medium">
                @if($pengaturan && $pengaturan->maps_embed)
                    {!! str_replace('<iframe', '<iframe class="w-full h-full border-0 grayscale group-hover:grayscale-0 transition-all duration-700"', $pengaturan->maps_embed) !!}
                @else
                    <iframe class="w-full h-full border-0 grayscale group-hover:grayscale-0 transition-all duration-700" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.970258169134!2d105.25902517565313!3d-5.421508294558231!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da42c8d23461%3A0x9599da5ea9079133!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sen!2sid!4v1689874839818!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                @endif
            </div>
        </div>
        
    </div>
</section>
@endsection
