<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'E-Advokat') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
        
        <!-- FontAwesome -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased text-body bg-neutral-primary-soft">
        <div class="min-h-screen flex">
            <!-- Left Side: Form Container -->
            <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 lg:px-24 py-12 bg-neutral-primary relative z-10 shadow-2xl overflow-hidden">
                <!-- Abstract Pattern/Image -->
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, var(--color-border-default-strong) 1px, transparent 0); background-size: 32px 32px;"></div>
                
                <div class="relative z-10 w-full max-w-md mx-auto">
                    <!-- Logo -->
                    <div class="mb-12">
                        <a href="/" class="inline-flex items-center gap-3 group">
                            <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-brand to-[#7B191C] text-white flex items-center justify-center text-xl shadow-md group-hover:scale-105 transition-transform">
                                <i class="fa-solid fa-scale-balanced"></i>
                            </div>
                            <div>
                                <span class="block font-['Playfair_Display'] text-2xl font-bold text-heading leading-none">E-Advokat</span>
                                <span class="block text-[11px] font-bold text-brand uppercase tracking-widest mt-1">Pengadilan Tinggi</span>
                            </div>
                        </a>
                    </div>

                    <!-- Form Content -->
                    {{ $slot }}
                    
                    <!-- Footer -->
                    <div class="mt-16 text-center lg:text-left text-sm text-body-subtle">
                        &copy; {{ date('Y') }} Pengadilan Tinggi Tanjungkarang. All rights reserved.
                    </div>
                </div>
            </div>
            
            <!-- Right Side: Cover Image -->
            <div class="hidden lg:flex lg:w-1/2 relative bg-gradient-to-br from-[#B3262A] via-[#851C1F] to-[#4A1012] overflow-hidden items-center justify-center">
                <!-- Abstract Pattern/Image -->
                <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, white 1px, transparent 0); background-size: 32px 32px;"></div>
                
                <!-- Decorative Light Glow -->
                <div class="absolute top-0 left-1/4 w-1/2 h-1/2 bg-white/5 blur-[120px] rounded-full pointer-events-none"></div>
                
                <!-- Decorative Icon -->
                <div class="absolute -right-20 -bottom-20 text-white opacity-5 text-[30rem] pointer-events-none select-none">
                    <i class="fa-solid fa-scale-balanced"></i>
                </div>

                <div class="relative z-10 p-16 max-w-2xl text-center">
                    <div class="w-20 h-20 rounded-full bg-white/10 backdrop-blur-md flex items-center justify-center border border-white/20 mx-auto mb-8 shadow-xl text-white">
                        <i class="fa-solid fa-gavel text-4xl"></i>
                    </div>
                    <h2 class="font-['Playfair_Display'] text-4xl font-bold mb-6 leading-tight drop-shadow-md text-white">Sistem Informasi Manajemen<br>Penyumpahan Advokat</h2>
                    <p class="text-lg text-white/90 leading-relaxed font-light">
                        Platform digital yang memudahkan proses administrasi, verifikasi, dan pendaftaran sumpah profesi advokat secara terintegrasi dan profesional.
                    </p>
                </div>
            </div>
        </div>
    </body>
</html>
