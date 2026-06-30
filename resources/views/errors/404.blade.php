<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Tidak Ditemukan - EVOKAT</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Playfair+Display:ital,wght@0,600;0,700;1,600&display=swap" rel="stylesheet">
    
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-neutral-primary text-body font-sans antialiased relative min-h-screen flex items-center justify-center overflow-hidden">
    <!-- Abstract Pattern/Image -->
    <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(circle at 2px 2px, var(--color-border-default-strong) 1px, transparent 0); background-size: 32px 32px;"></div>
    
    <!-- Decorative Icon Background -->
    <div class="absolute right-0 top-1/2 -translate-y-1/2 translate-x-1/4 text-brand opacity-5 text-[30rem] pointer-events-none select-none -z-10">
        <i class="fa-solid fa-scale-unbalanced"></i>
    </div>

    <div class="relative z-10 w-full max-w-2xl mx-auto px-6 text-center">
        <h1 class="font-['Playfair_Display'] text-[8rem] sm:text-[10rem] font-bold text-brand leading-none mb-4 drop-shadow-sm">404</h1>
        
        <div class="w-20 h-1 bg-brand mx-auto rounded-full mb-8"></div>
        
        <h2 class="text-2xl sm:text-3xl font-bold text-heading mb-4">Halaman Tidak Ditemukan</h2>
        <p class="text-lg text-body-subtle mb-10 max-w-lg mx-auto">
            Maaf, halaman yang Anda tuju tidak dapat ditemukan atau telah dipindahkan. Silakan kembali ke Beranda untuk melanjutkan aktivitas Anda.
        </p>
        
        <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
            <a href="{{ url('/') }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-brand text-white shadow-md hover:shadow-lg hover:-translate-y-0.5 active:translate-y-0 transition-all border border-brand-softer w-full sm:w-auto group">
                <i class="fa-solid fa-house mr-2 transition-transform group-hover:-translate-y-0.5"></i> Kembali ke Beranda
            </a>
            <a href="{{ url('/tracking') }}" class="inline-flex justify-center items-center px-8 py-3.5 rounded-full text-[15px] font-bold bg-white text-heading shadow-sm hover:shadow-md hover:text-brand hover:border-brand-softer active:translate-y-0 transition-all border border-border-default-strong w-full sm:w-auto">
                <i class="fa-solid fa-magnifying-glass mr-2"></i> Lacak Permohonan
            </a>
        </div>
    </div>
</body>
</html>
