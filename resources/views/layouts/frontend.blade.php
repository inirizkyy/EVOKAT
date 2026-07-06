<!DOCTYPE html>
<html lang="id" class="antialiased">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title') - EVOKAT Pengadilan Tinggi Tanjungkarang</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:wght@600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Vite (Tailwind CSS + AlpineJS) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    @stack('styles')
</head>
<body class="bg-neutral-primary-soft text-body font-sans min-h-screen flex flex-col pt-[88px] selection:bg-brand selection:text-white relative z-0">
    <!-- Global Dotted Pattern Background -->
    <div class="fixed inset-0 opacity-20 -z-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, var(--color-border-default-strong) 1px, transparent 0); background-size: 32px 32px;"></div>
    
    <!-- Decorative Top Gradient -->
    <div class="absolute top-0 left-0 right-0 h-[500px] bg-gradient-to-b from-brand-softer via-brand-softer/30 to-transparent -z-10 pointer-events-none"></div>

    <!-- Floating Navbar -->
    <nav x-data="{ open: false, scrolled: false }" 
         @scroll.window="scrolled = (window.pageYOffset > 20) ? true : false"
         :class="{ 'shadow-md bg-neutral-primary-soft/95 backdrop-blur-md border-b border-border-default': scrolled, 'bg-transparent border-transparent': !scrolled }"
         class="fixed top-0 left-0 right-0 z-50 transition-all duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <!-- Logo -->
                <div class="flex-shrink-0 flex items-center">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-base bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-brand group-hover:shadow-md transition-all">
                            <i class="fa-solid fa-scale-balanced text-lg"></i>
                        </div>
                        <span class="font-['Playfair_Display'] font-bold text-xl text-heading tracking-wide">EVOKAT</span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden lg:flex items-center space-x-1">
                    <a href="{{ url('/') }}" class="px-4 py-2 rounded-base text-[15px] font-medium transition-all {{ request()->is('/') ? 'text-brand bg-neutral-primary-soft shadow-inset border border-border-default-medium' : 'text-body hover:text-heading hover:bg-neutral-primary-soft' }}">Beranda</a>
                    <a href="{{ url('/informasi') }}" class="px-4 py-2 rounded-base text-[15px] font-medium transition-all {{ request()->is('informasi') ? 'text-brand bg-neutral-primary-soft shadow-inset border border-border-default-medium' : 'text-body hover:text-heading hover:bg-neutral-primary-soft' }}">Informasi</a>
                    <a href="{{ url('/persyaratan') }}" class="px-4 py-2 rounded-base text-[15px] font-medium transition-all {{ request()->is('persyaratan') ? 'text-brand bg-neutral-primary-soft shadow-inset border border-border-default-medium' : 'text-body hover:text-heading hover:bg-neutral-primary-soft' }}">Persyaratan</a>
                    <a href="{{ url('/alur') }}" class="px-4 py-2 rounded-base text-[15px] font-medium transition-all {{ request()->is('alur') ? 'text-brand bg-neutral-primary-soft shadow-inset border border-border-default-medium' : 'text-body hover:text-heading hover:bg-neutral-primary-soft' }}">Alur</a>
                    <a href="{{ url('/faq') }}" class="px-4 py-2 rounded-base text-[15px] font-medium transition-all {{ request()->is('faq') ? 'text-brand bg-neutral-primary-soft shadow-inset border border-border-default-medium' : 'text-body hover:text-heading hover:bg-neutral-primary-soft' }}">FAQ</a>
                    
                    <div class="h-6 w-px bg-border-default mx-2"></div>
                    
                    <a href="{{ url('/tracking') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-[15px] font-medium bg-neutral-primary-soft text-heading shadow-sm hover:shadow-md active:shadow-inset transition-all border border-border-default mr-2">
                        Cek Status
                    </a>
                    <a href="{{ url('/permohonan') }}" class="inline-flex items-center justify-center px-5 py-2.5 rounded-full text-[15px] font-medium bg-brand text-white shadow-sm hover:shadow-md hover:bg-brand-soft active:shadow-inset transition-all border border-brand-softer">
                        Ajukan Permohonan
                    </a>
                    

                </div>

                <!-- Mobile menu button -->
                <div class="flex items-center lg:hidden">
                    <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-base bg-neutral-primary-soft text-body hover:text-heading hover:shadow-md active:shadow-inset transition-all border border-border-default" aria-controls="mobile-menu" aria-expanded="false">
                        <span class="sr-only">Open main menu</span>
                        <i class="fa-solid fa-bars text-lg" x-show="!open"></i>
                        <i class="fa-solid fa-xmark text-lg" x-show="open" style="display: none;"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div x-show="open" class="lg:hidden border-t border-border-default bg-neutral-primary shadow-md" id="mobile-menu" style="display: none;">
            <div class="px-4 pt-2 pb-6 space-y-1">
                <a href="{{ url('/') }}" class="block px-4 py-3 rounded-base text-base font-medium {{ request()->is('/') ? 'text-brand bg-neutral-secondary-soft shadow-inset' : 'text-body hover:bg-neutral-secondary-soft' }}">Beranda</a>
                <a href="{{ url('/informasi') }}" class="block px-4 py-3 rounded-base text-base font-medium {{ request()->is('informasi') ? 'text-brand bg-neutral-secondary-soft shadow-inset' : 'text-body hover:bg-neutral-secondary-soft' }}">Informasi Layanan</a>
                <a href="{{ url('/persyaratan') }}" class="block px-4 py-3 rounded-base text-base font-medium {{ request()->is('persyaratan') ? 'text-brand bg-neutral-secondary-soft shadow-inset' : 'text-body hover:bg-neutral-secondary-soft' }}">Persyaratan</a>
                <a href="{{ url('/alur') }}" class="block px-4 py-3 rounded-base text-base font-medium {{ request()->is('alur') ? 'text-brand bg-neutral-secondary-soft shadow-inset' : 'text-body hover:bg-neutral-secondary-soft' }}">Alur</a>
                <a href="{{ url('/faq') }}" class="block px-4 py-3 rounded-base text-base font-medium {{ request()->is('faq') ? 'text-brand bg-neutral-secondary-soft shadow-inset' : 'text-body hover:bg-neutral-secondary-soft' }}">FAQ</a>
                <a href="{{ url('/kontak') }}" class="block px-4 py-3 rounded-base text-base font-medium {{ request()->is('kontak') ? 'text-brand bg-neutral-secondary-soft shadow-inset' : 'text-body hover:bg-neutral-secondary-soft' }}">Kontak</a>
                
                <div class="pt-4 mt-2 border-t border-border-default flex flex-col gap-3">
                    <a href="{{ url('/tracking') }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-full text-base font-medium bg-neutral-primary-soft text-heading shadow-sm border border-border-default">
                        Cek Status
                    </a>
                    <a href="{{ url('/permohonan') }}" class="w-full inline-flex items-center justify-center px-5 py-3 rounded-full text-base font-medium bg-brand text-white shadow-sm border border-brand-softer">
                        Ajukan Permohonan
                    </a>
                    

                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow flex flex-col">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="mt-auto bg-neutral-primary border-t border-border-default pt-16 pb-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 lg:gap-8">
                <!-- Branding -->
                <div class="lg:col-span-1">
                    <a href="{{ url('/') }}" class="flex items-center gap-2 mb-4">
                        <div class="w-10 h-10 rounded-base bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-brand">
                            <i class="fa-solid fa-scale-balanced text-lg"></i>
                        </div>
                        <span class="font-['Playfair_Display'] font-bold text-xl text-heading tracking-wide">EVOKAT</span>
                    </a>
                    <p class="text-[14px] text-body-subtle leading-relaxed">
                        Sistem Informasi Permohonan Sumpah Advokat Pengadilan Tinggi Tanjungkarang. Melayani dengan cepat, transparan, dan akuntabel.
                    </p>
                </div>

                <!-- Tautan Cepat -->
                <div>
                    <h5 class="font-bold text-heading text-lg mb-6 relative inline-block">
                        Tautan Cepat
                        <span class="absolute bottom-[-8px] left-0 w-1/2 h-0.5 bg-brand rounded-full"></span>
                    </h5>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Beranda</a></li>
                        <li><a href="{{ url('/informasi') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Informasi Layanan</a></li>
                        <li><a href="{{ url('/persyaratan') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Persyaratan</a></li>
                        <li><a href="{{ url('/faq') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> FAQ</a></li>
                    </ul>
                </div>

                <!-- Layanan Publik -->
                <div>
                    <h5 class="font-bold text-heading text-lg mb-6 relative inline-block">
                        Layanan Publik
                        <span class="absolute bottom-[-8px] left-0 w-1/2 h-0.5 bg-brand rounded-full"></span>
                    </h5>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/permohonan') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Ajukan Permohonan</a></li>
                        <li><a href="{{ url('/tracking') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Cek Status</a></li>
                        <li><a href="{{ url('/alur') }}" class="text-[14px] text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Alur Pengajuan</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h5 class="font-bold text-heading text-lg mb-6 relative inline-block">
                        Hubungi Kami
                        <span class="absolute bottom-[-8px] left-0 w-1/2 h-0.5 bg-brand rounded-full"></span>
                    </h5>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-[14px] text-body-subtle">
                            <div class="mt-0.5 w-8 h-8 shrink-0 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-brand">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <span>Jl. Cut Mutia No.42, Bandar Lampung</span>
                        </li>
                        <li class="flex items-center gap-3 text-[14px] text-body-subtle">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-brand">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span>(0721) 482431</span>
                        </li>
                        <li class="flex items-center gap-3 text-[14px] text-body-subtle">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-brand">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <span>info@pt-tanjungkarang.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-border-default flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-[13px] text-body-subtle text-center md:text-left">
                    &copy; {{ date('Y') }} Pengadilan Tinggi Tanjungkarang. Hak Cipta Dilindungi.
                </p>
                <div class="flex gap-4">
                    <a href="#" class="w-8 h-8 rounded-full bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-body-subtle hover:text-brand hover:shadow-md transition-all">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-body-subtle hover:text-brand hover:shadow-md transition-all">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-body-subtle hover:text-brand hover:shadow-md transition-all">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <x-chat-widget />
    @stack('scripts')
</body>
</html>
