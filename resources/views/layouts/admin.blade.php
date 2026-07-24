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
            <a href="{{ in_array(Auth::user()?->role, ['verifikator1','verifikator2','verifikator3','verifikator4']) ? route(Auth::user()->role . '.dashboard') : (Auth::user()?->role === 'pemeriksa' ? route('pemeriksa.dashboard') : route('dashboard')) }}" class="flex items-center p-6 mb-2 no-underline text-heading hover:text-brand transition-colors">
                <i class="fa-solid fa-scale-balanced text-2xl mr-3 text-brand"></i>
                <span class="text-xl font-bold">EVOKAT</span>
            </a>
            
            <nav class="flex-1 px-4 space-y-2 overflow-y-auto">
                @if(Auth::user()?->role === 'admin')
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
                    
                    <a href="{{ route('admin.organisasi.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.organisasi.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-building-columns w-6"></i>
                        <span class="font-medium text-[14px]">Organisasi Advokat</span>
                    </a>
                    
                    <a href="{{ route('admin.faq.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.faq.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-circle-question w-6"></i>
                        <span class="font-medium text-[14px]">FAQ</span>
                    </a>
                    
                    <a href="{{ route('admin.surat-template.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.surat-template.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-file-word w-6"></i>
                        <span class="font-medium text-[14px]">Template Surat</span>
                    </a>
                    
                    <a href="{{ route('admin.room.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.room.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-door-open w-6"></i>
                        <span class="font-medium text-[14px]">Ruang Sidang</span>
                    </a>
                    
                    <a href="{{ route('admin.signatory.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.signatory.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-signature w-6"></i>
                        <span class="font-medium text-[14px]">Nama Penandatangan</span>
                    </a>
                    
                    <a href="{{ route('admin.leader.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.leader.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-user-tie w-6"></i>
                        <span class="font-medium text-[14px]">Pemimpin Sumpah</span>
                    </a>

                    <a href="{{ route('admin.pengaturan.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('admin.pengaturan.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-gear w-6"></i>
                        <span class="font-medium text-[14px]">Pengaturan Website</span>
                    </a>
                @elseif(Auth::user()?->role === 'pemeriksa')
                    <a href="{{ route('pemeriksa.dashboard') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs('pemeriksa.dashboard') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-gauge w-6"></i>
                        <span class="font-medium text-[14px]">Dashboard</span>
                    </a>
                @elseif(in_array(Auth::user()?->role, ['verifikator1', 'verifikator2', 'verifikator3', 'verifikator4']))
                    @php
                        $role = Auth::user()->role;
                    @endphp
                    <a href="{{ route($role . '.dashboard') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs($role . '.dashboard') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-gauge w-6"></i>
                        <span class="font-medium text-[14px]">Dashboard</span>
                    </a>
                    
                    <a href="{{ route($role . '.permohonan.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs($role . '.permohonan.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-file-lines w-6"></i>
                        <span class="font-medium text-[14px]">Permohonan Sumpah</span>
                    </a>
                    
                    <a href="{{ route($role . '.buku-registrasi.index') }}" class="flex items-center px-4 py-3 rounded-base transition-all {{ request()->routeIs($role . '.buku-registrasi.*') ? 'bg-white shadow-sm border border-border-default text-brand font-semibold' : 'text-body hover:bg-white/50 hover:text-heading' }}">
                        <i class="fa-solid fa-address-book w-6"></i>
                        <span class="font-medium text-[14px]">Buku Registrasi Advokat</span>
                    </a>
                @endif
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
                            <img src="https://ui-avatars.com/api/?name={{ Auth::user()?->name ?? 'Admin' }}&background=E2E8F0&color=31344B" alt="" class="w-8 h-8 rounded-full shadow-sm">
                            <span class="font-semibold text-[14px]">{{ Auth::user()?->name ?? 'Administrator' }}</span>
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
                <div class="flex justify-between items-center mb-8">
                    <h2 class="text-3xl font-semibold text-heading">@yield('title')</h2>
                    <div>@yield('actions')</div>
                </div>

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

                @yield('content')
            </main>
            
            <footer class="bg-transparent border-t border-border-default/50 text-center py-6 text-base text-body-subtle">
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

            // Set offline beacon when page is closed/unloaded
            window.addEventListener('beforeunload', function () {
                navigator.sendBeacon('{{ route('admin.set-offline') }}');
            });
        });
    </script>

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
