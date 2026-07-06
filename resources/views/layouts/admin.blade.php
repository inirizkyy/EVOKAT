<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Admin EVOKAT</title>
    <!-- Google Fonts: Nunito Sans -->
    <link href="https://fonts.googleapis.com/css2?family=Nunito+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
    <!-- Tailwind CSS (Vite) -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="bg-neutral-primary-soft text-body font-sans antialiased relative z-0">
    <!-- Global Dotted Pattern Background -->
    <div class="fixed inset-0 opacity-20 -z-20 pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, var(--color-border-default-strong) 1px, transparent 0); background-size: 32px 32px;"></div>
    <!-- Decorative Top Gradient -->
    <div class="fixed top-0 left-0 right-0 h-[500px] bg-gradient-to-b from-brand-softer via-brand-softer/30 to-transparent -z-10 pointer-events-none"></div>

    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <div class="w-[256px] flex-shrink-0 flex flex-col bg-neutral-primary-soft/80 backdrop-blur-md border-r border-border-default z-20">
            <a href="{{ route('dashboard') }}" class="flex items-center p-6 mb-2 no-underline text-heading hover:text-brand transition-colors">
                <i class="fa-solid fa-scale-balanced text-2xl mr-3 text-brand"></i>
                <span class="text-xl font-bold">EVOKAT</span>
            </a>
            
            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                <a href="{{ route('dashboard') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('dashboard') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-gauge w-6"></i>
                    <span class="font-medium text-[14px]">Dashboard</span>
                </a>
                
                <a href="{{ route('admin.permohonan.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.permohonan.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-file-lines w-6"></i>
                    <span class="font-medium text-[14px]">Permohonan Sumpah</span>
                </a>
                
                <a href="{{ route('admin.buku-registrasi.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.buku-registrasi.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-address-book w-6"></i>
                    <span class="font-medium text-[14px]">Buku Registrasi Advokat</span>
                </a>

                <a href="{{ route('admin.chat.index') }}" class="flex items-center justify-between px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.chat.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <div class="flex items-center">
                        <i class="fa-solid fa-comments w-6"></i>
                        <span class="font-medium text-[14px]">Live Chat</span>
                    </div>
                    <span id="sidebar-chat-badge" class="hidden bg-red-500 text-white text-xs font-bold px-2 py-1 rounded-full"></span>
                </a>

                
                <div class="pt-6 pb-2 px-4 text-xs font-semibold uppercase tracking-wider text-body-subtle">
                    Master Data
                </div>
                
                <a href="{{ route('admin.persyaratan.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.persyaratan.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-list-check w-6"></i>
                    <span class="font-medium text-[14px]">Persyaratan</span>
                </a>
                
                <a href="{{ route('admin.berita.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.berita.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-newspaper w-6"></i>
                    <span class="font-medium text-[14px]">Berita & Info</span>
                </a>
                
                <a href="{{ route('admin.faq.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.faq.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-circle-question w-6"></i>
                    <span class="font-medium text-[14px]">FAQ</span>
                </a>
                
                <a href="{{ route('admin.surat-template.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.surat-template.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-file-word w-6"></i>
                    <span class="font-medium text-[14px]">Template Surat</span>
                </a>
                
                <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.pengaturan.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                    <i class="fa-solid fa-gear w-6"></i>
                    <span class="font-medium text-[14px]">Pengaturan Website</span>
                </a>
            </nav>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden bg-transparent">
            
            <!-- Header (Navbar) -->
            <header class="bg-transparent border-b border-border-default/50 shadow-sm z-10 px-6 py-4 flex justify-between items-center">
                <div class="flex items-center lg:hidden">
                    <!-- Mobile Menu Button (Optional implementation) -->
                    <button class="text-body hover:text-heading p-2 rounded-base hover:shadow-sm focus:shadow-inset transition-all">
                        <i class="fa-solid fa-bars text-lg"></i>
                    </button>
                </div>
                
                <div class="ml-auto flex items-center">
                    <!-- User Dropdown (Alpine.js is highly recommended here, but we will use standard HTML/CSS dropdown mapping for now or stick to Alpine if we include it) -->
                    <div class="relative group">
                        <button class="flex items-center space-x-3 text-heading p-2 rounded-base hover:shadow-sm transition-all focus:outline-none">
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()->name ?? 'Admin' }}&background=E2E8F0&color=31344B" alt="" class="w-8 h-8 rounded-full shadow-sm">
                            <span class="font-semibold text-[14px]">{{ Auth::user()->name ?? 'Administrator' }}</span>
                            <i class="fa-solid fa-chevron-down text-body text-xs ml-1"></i>
                        </button>
                        
                        <!-- Dropdown Menu -->
                        <div class="absolute right-0 mt-2 w-48 bg-neutral-primary-soft border border-border-default rounded-base shadow-lg opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 p-2">
                            <a href="{{ route('profile.edit') }}" class="flex items-center w-full px-4 py-2 text-[14px] font-medium text-body hover:bg-neutral-tertiary-medium hover:text-heading rounded-[6px] transition-colors">
                                Profile
                            </a>
                            <div class="h-px bg-border-default my-2"></div>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="flex items-center w-full px-4 py-2 text-[14px] font-medium text-fg-danger hover:bg-neutral-tertiary-medium rounded-[6px] transition-colors">
                                    Sign out
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Main Container -->
            <main class="flex-1 overflow-x-hidden overflow-y-auto bg-transparent p-6 lg:p-8">
                <!-- Alerts -->
                @if(session('success'))
                <div class="p-4 mb-6 rounded-base border border-border-success-subtle bg-success-soft text-fg-success-strong flex items-center shadow-sm">
                    <i class="fa-solid fa-check-circle mr-3"></i>
                    <p class="text-[14px] m-0">{{ session('success') }}</p>
                </div>
                @endif
                
                @if(session('error'))
                <div class="p-4 mb-6 rounded-base border border-border-danger-subtle bg-danger-soft text-fg-danger-strong flex items-center shadow-sm">
                    <i class="fa-solid fa-triangle-exclamation mr-3"></i>
                    <p class="text-[14px] m-0">{{ session('error') }}</p>
                </div>
                @endif

                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-semibold text-heading">@yield('title')</h2>
                    <div>@yield('actions')</div>
                </div>

                @yield('content')
            </main>
            
            <footer class="bg-transparent border-t border-border-default/50 text-center py-6 text-[14px] text-body-subtle">
                © {{ date('Y') }} EVOKAT Pengadilan Tinggi Tanjungkarang
            </footer>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const badge = document.getElementById('sidebar-chat-badge');
            
            function fetchUnreadCount() {
                fetch('{{ route('admin.chat.unread') }}', {
                    headers: { 'Accept': 'application/json' }
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success && data.count > 0) {
                        badge.textContent = data.count;
                        badge.classList.remove('hidden');
                    } else {
                        badge.classList.add('hidden');
                    }
                })
                .catch(err => console.error('Error fetching unread count:', err));
            }

            fetchUnreadCount();
            setInterval(fetchUnreadCount, 10000); // Check every 10 seconds
        });
    </script>
    @stack('scripts')
</body>
</html>
