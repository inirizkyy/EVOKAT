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
    @if(!request()->is('/'))
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
                    <p class="text-base text-body-subtle leading-relaxed">
                        Sistem Informasi Permohonan Sumpah Advokat Pengadilan Tinggi Tanjungkarang. Melayani dengan cepat, transparan, dan akuntabel.
                    </p>
                </div>

                <!-- Lokasi Kantor -->
                <div>
                    <h5 class="font-bold text-heading text-xl mb-6 relative inline-block">
                        Peta Lokasi
                        <span class="absolute bottom-[-8px] left-0 w-1/2 h-0.5 bg-brand rounded-full"></span>
                    </h5>
                    @php
                        $pengaturanFooter = \App\Models\PengaturanWebsite::first();
                        $mapsSearchUrl = 'https://maps.google.com/?q=' . urlencode(($pengaturanFooter && $pengaturanFooter->alamat) ? $pengaturanFooter->alamat : 'Pengadilan Tinggi Tanjungkarang');
                    @endphp
                    <div class="space-y-4">
                        <div class="w-full h-36 rounded-2xl overflow-hidden shadow-inset bg-neutral-secondary-medium border border-border-default relative group">
                            @if($pengaturanFooter && $pengaturanFooter->maps_embed)
                                {!! str_replace('<iframe', '<iframe class="w-full h-full border-0 grayscale group-hover:grayscale-0 transition-all duration-700"', $pengaturanFooter->maps_embed) !!}
                            @else
                                <iframe class="w-full h-full border-0 grayscale group-hover:grayscale-0 transition-all duration-700" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3971.970258169134!2d105.25902517565313!3d-5.421508294558231!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e40da42c8d23461%3A0x9599da5ea9079133!2sPengadilan%20Tinggi%20Tanjungkarang!5e0!3m2!1sen!2sid!4v1689874839818!5m2!1sen!2sid" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                            @endif
                        </div>
                        <a href="{{ $mapsSearchUrl }}" target="_blank" rel="noopener noreferrer" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-full text-[14px] font-bold bg-neutral-primary-soft text-heading shadow-sm hover:shadow-md hover:bg-neutral-secondary-soft active:shadow-inset transition-all border border-border-default hover:text-brand">
                            <i class="fa-solid fa-up-right-from-square text-xs text-brand"></i>
                            Buka di Google Maps
                        </a>
                    </div>
                </div>

                <!-- Layanan Publik -->
                <div>
                    <h5 class="font-bold text-heading text-xl mb-6 relative inline-block">
                        Layanan Publik
                        <span class="absolute bottom-[-8px] left-0 w-1/2 h-0.5 bg-brand rounded-full"></span>
                    </h5>
                    <ul class="space-y-3">
                        <li><a href="{{ url('/permohonan') }}" class="text-base text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Ajukan Permohonan</a></li>
                        <li><a href="{{ url('/tracking') }}" class="text-base text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Cek Status</a></li>
                        <li><a href="{{ url('/alur') }}" class="text-base text-body-subtle hover:text-brand transition-colors flex items-center"><i class="fa-solid fa-angle-right text-[10px] mr-2"></i> Alur Pengajuan</a></li>
                    </ul>
                </div>

                <!-- Kontak -->
                <div>
                    <h5 class="font-bold text-heading text-xl mb-6 relative inline-block">
                        Hubungi Kami
                        <span class="absolute bottom-[-8px] left-0 w-1/2 h-0.5 bg-brand rounded-full"></span>
                    </h5>
                    <ul class="space-y-4">
                        <li class="flex items-start gap-3 text-base text-body-subtle">
                            <div class="mt-0.5 w-8 h-8 shrink-0 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-brand">
                                <i class="fa-solid fa-location-dot"></i>
                            </div>
                            <span>Jl. Cut Mutia No.42, Bandar Lampung</span>
                        </li>
                        <li class="flex items-center gap-3 text-base text-body-subtle">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-brand">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <span>(0721) 482431</span>
                        </li>
                        <li class="flex items-center gap-3 text-base text-body-subtle">
                            <div class="w-8 h-8 shrink-0 rounded-full bg-neutral-primary-soft shadow-inset border border-border-default flex items-center justify-center text-brand">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <span>info@pt-tanjungkarang.go.id</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="mt-16 pt-8 border-t border-border-default flex flex-col md:flex-row justify-between items-center gap-4">
                <p class="text-sm text-body-subtle text-center md:text-left">
                    &copy; {{ date('Y') }} Pengadilan Tinggi Tanjungkarang. Hak Cipta Dilindungi.
                </p>
                <div class="flex gap-4">
                    <a href="https://www.facebook.com/pt.tanjungkarang/" class="w-8 h-8 rounded-full bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-body-subtle hover:text-brand hover:shadow-md transition-all">
                        <i class="fa-brands fa-facebook-f"></i>
                    </a>
                    <a href="#" class="w-8 h-8 rounded-full bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-body-subtle hover:text-brand hover:shadow-md transition-all">
                        <i class="fa-brands fa-twitter"></i>
                    </a>
                    <a href="https://www.instagram.com/pttanjungkarang/" class="w-8 h-8 rounded-full bg-neutral-primary-soft shadow-sm border border-border-default flex items-center justify-center text-body-subtle hover:text-brand hover:shadow-md transition-all">
                        <i class="fa-brands fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </footer>
    @endif

    <x-chat-widget />

    <!-- Komponen Reusable Loading Overlay -->
    <div id="global-loading-overlay" style="display: none; z-index: 999999;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-md flex flex-col items-center justify-center transition-all duration-300">
        <div class="relative flex flex-col items-center gap-4 p-8 text-center max-w-md mx-4 bg-white rounded-2xl shadow-2xl border border-gray-100">
            <!-- Spinner Icon -->
            <div class="relative flex items-center justify-center">
                <div class="w-16 h-16 rounded-full border-4 border-red-200 border-t-red-700 animate-spin"></div>
                <i class="fa-solid fa-scale-balanced absolute text-red-700 text-lg"></i>
            </div>
            
            <div>
                <h5 id="global-loading-title" class="text-lg font-bold text-gray-900">Memproses Data...</h5>
                <p id="global-loading-sub" class="text-sm text-gray-600 mt-1.5 leading-relaxed">Harap tunggu, sistem sedang memproses permintaan Anda.</p>
            </div>
        </div>
    </div>

    <!-- Modal Notification Error Ukuran File (>2MB) -->
    <div id="file-size-error-modal" style="display: none; z-index: 999999;" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm flex items-center justify-center p-4">
        <div class="bg-white rounded-2xl p-6 sm:p-8 max-w-md w-full shadow-2xl border border-red-100 flex flex-col items-center text-center space-y-4 transition-all">
            <div class="w-16 h-16 rounded-full bg-red-100 border border-red-200 flex items-center justify-center text-red-600 shadow-sm">
                <i class="fa-solid fa-triangle-exclamation text-3xl"></i>
            </div>
            <div class="space-y-2">
                <h4 class="text-lg font-bold text-gray-900">Ukuran File Melebihi Batas (2MB)!</h4>
                <p id="file-size-error-text" class="text-sm text-gray-600 leading-relaxed"></p>
            </div>
            <button type="button" onclick="closeFileSizeErrorModal()" class="w-full py-3 px-6 rounded-xl font-bold text-sm bg-red-600 hover:bg-red-700 active:bg-red-800 text-white shadow-md transition-all cursor-pointer">
                <i class="fa-solid fa-check mr-2"></i>Pilih Ulang File
            </button>
        </div>
    </div>

    <script>
    function showFileSizeErrorModal(fileName, fileSizeMb, maxMb) {
        const modal = document.getElementById('file-size-error-modal');
        const textEl = document.getElementById('file-size-error-text');
        if (textEl) {
            textEl.innerHTML = `File <strong class="text-red-700 font-mono">${fileName}</strong> berukuran <strong class="text-red-700">${fileSizeMb} MB</strong>.<br><br>File hanya dengan ukuran maksimal <strong class="text-red-700">${maxMb} MB</strong>. Silakan kecilkan ukuran file Anda.`;
        }
        if (modal) {
            modal.style.display = 'flex';
        }
    }

    function closeFileSizeErrorModal() {
        const modal = document.getElementById('file-size-error-modal');
        if (modal) {
            modal.style.display = 'none';
        }
    }

    function showGlobalLoading(title = 'Memproses Data...', sub = 'Harap tunggu, sistem sedang memproses permintaan Anda.') {
        const overlay = document.getElementById('global-loading-overlay');
        const titleEl = document.getElementById('global-loading-title');
        const subEl   = document.getElementById('global-loading-sub');
        
        if (titleEl) titleEl.textContent = title;
        if (subEl) subEl.textContent = sub;
        if (overlay) {
            overlay.classList.remove('hidden');
            overlay.style.display = 'flex';
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Instant client-side file size validation (>2MB blocker)
        document.addEventListener('change', function(e) {
            const input = e.target;
            if (!input || input.tagName !== 'INPUT' || input.type !== 'file') return;
            
            if (input.files && input.files[0]) {
                const file = input.files[0];
                const maxMb = parseFloat(input.getAttribute('data-max-size')) || 2;
                const maxBytes = maxMb * 1024 * 1024;
                
                if (file.size > maxBytes) {
                    const sizeInMb = (file.size / (1024 * 1024)).toFixed(2);
                    input.value = ''; // Reset file input immediately
                    showFileSizeErrorModal(file.name, sizeInMb, maxMb);
                }
            }
        }, true);

        document.addEventListener('submit', function(e) {
            const form = e.target;
            if (!form || form.tagName !== 'FORM') return;

            if (typeof form.checkValidity === 'function' && !form.checkValidity()) {
                return;
            }

            const title = form.getAttribute('data-loading-title') || 'Memproses Data...';
            const sub   = form.getAttribute('data-loading-sub')   || 'Harap tunggu, sistem sedang memproses permintaan Anda.';
            showGlobalLoading(title, sub);
            
            const submitBtn = form.querySelector('button[type="submit"], input[type="submit"]');
            if (submitBtn) {
                setTimeout(() => {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-60', 'cursor-not-allowed');
                }, 50);
            }
        }, true);
    });
    </script>

    @stack('scripts')
</body>
</html>
