<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NikelFleet — Sistem Manajemen Kendaraan Tambang')</title>

    <!-- Google Fonts: Plus Jakarta Sans (Unified Global Font) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                        mono: ['"Plus Jakarta Sans"', 'system-ui', '-apple-system', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                            900: '#064e3b',
                        },
                        obsidian: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Canvas Confetti -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        *, body, button, input, select, textarea, .font-mono, [class*="font-mono"] {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
            font-feature-settings: "tnum" 1, "cv02" 1, "cv03" 1, "cv04" 1, "cv11" 1;
        }
    </style>
</head>
<body class="min-h-screen flex flex-col font-sans bg-slate-50 text-slate-900 antialiased pb-20 md:pb-0">

    <!-- Navigation Header -->
    <header class="bg-obsidian-950 text-white sticky top-0 z-40 border-b border-slate-800/80 shadow-md">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Brand / Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-3 group">
                        <div class="w-9 h-9 rounded-xl bg-gradient-to-tr from-brand-600 to-emerald-400 flex items-center justify-center text-obsidian-950 shadow-md shadow-brand-500/20 group-hover:scale-105 transition-transform">
                            <i data-lucide="layers" class="w-5 h-5 stroke-[2.5]"></i>
                        </div>
                        <div class="flex items-baseline space-x-2">
                            <span class="text-base font-extrabold tracking-tight text-white">Nikel<span class="text-brand-500">Fleet</span></span>
                            <span class="hidden sm:inline-flex items-center px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                                Mining Ops
                            </span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                <nav class="hidden md:flex items-center space-x-1">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="inline-flex items-center space-x-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.dashboard') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4 text-brand-500"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pemesanan.index') }}" 
                       class="inline-flex items-center space-x-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.pemesanan.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <i data-lucide="calendar" class="w-4 h-4 text-blue-400"></i>
                        <span>Pemesanan</span>
                    </a>

                    <a href="{{ route('admin.kendaraan.index') }}" 
                       class="inline-flex items-center space-x-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.kendaraan.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <i data-lucide="truck" class="w-4 h-4 text-amber-400"></i>
                        <span>Armada</span>
                    </a>

                    <a href="{{ route('admin.laporan.index') }}" 
                       class="inline-flex items-center space-x-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.laporan.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4 text-emerald-400"></i>
                        <span>Laporan</span>
                    </a>

                    <a href="{{ route('admin.logs.index') }}" 
                       class="inline-flex items-center space-x-2 px-3 py-2 rounded-lg text-xs font-semibold transition {{ request()->routeIs('admin.logs.*') ? 'bg-slate-800 text-white' : 'text-slate-400 hover:text-white hover:bg-slate-900' }}">
                        <i data-lucide="shield-check" class="w-4 h-4 text-purple-400"></i>
                        <span>Audit Log</span>
                    </a>
                </nav>

                <!-- User Profile & Logout -->
                <div class="flex items-center space-x-3">
                    @auth
                    <div class="hidden sm:flex items-center space-x-2.5 pl-3 border-l border-slate-800">
                        <div class="w-8 h-8 rounded-lg bg-slate-800 border border-slate-700 flex items-center justify-center font-bold text-xs text-brand-400">
                            {{ substr(Auth::user()->nama, 0, 2) }}
                        </div>
                        <div class="text-right leading-tight">
                            <div class="text-xs font-bold text-white">{{ Auth::user()->nama }}</div>
                            <div class="text-[10px] text-slate-400 font-medium">{{ Auth::user()->jabatan ?? ucfirst(Auth::user()->peran) }}</div>
                        </div>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="button" 
                                onclick="confirmLogout()"
                                class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-lg text-xs font-semibold text-slate-400 hover:text-rose-400 hover:bg-slate-900 border border-slate-800 transition">
                            <i data-lucide="log-out" class="w-3.5 h-3.5"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Mobile-First Bottom Navigation Bar (No emojis, ultra-clean stroke icons) -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white border-t border-slate-200 z-40 shadow-xl px-2 py-1 flex justify-around items-center">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex flex-col items-center py-1 px-3 text-[10px] font-semibold transition active:scale-95 {{ request()->routeIs('admin.dashboard') ? 'text-brand-600' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 mb-0.5"></i>
            <span>Beranda</span>
        </a>

        <a href="{{ route('admin.pemesanan.index') }}" 
           class="flex flex-col items-center py-1 px-3 text-[10px] font-semibold transition active:scale-95 {{ request()->routeIs('admin.pemesanan.*') && !request()->routeIs('admin.pemesanan.create') ? 'text-brand-600' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="calendar" class="w-5 h-5 mb-0.5"></i>
            <span>Pesanan</span>
        </a>

        <!-- FAB for New Booking -->
        <a href="{{ route('admin.pemesanan.create') }}" 
           class="flex items-center justify-center -mt-5 w-12 h-12 bg-brand-600 text-white rounded-2xl shadow-lg shadow-brand-600/30 hover:bg-brand-700 active:scale-90 transition-transform border-4 border-white">
            <i data-lucide="plus" class="w-6 h-6 stroke-[2.5]"></i>
        </a>

        <a href="{{ route('admin.kendaraan.index') }}" 
           class="flex flex-col items-center py-1 px-3 text-[10px] font-semibold transition active:scale-95 {{ request()->routeIs('admin.kendaraan.*') ? 'text-brand-600' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="truck" class="w-5 h-5 mb-0.5"></i>
            <span>Armada</span>
        </a>

        <a href="{{ route('admin.laporan.index') }}" 
           class="flex flex-col items-center py-1 px-3 text-[10px] font-semibold transition active:scale-95 {{ request()->routeIs('admin.laporan.*') ? 'text-brand-600' : 'text-slate-500 hover:text-slate-800' }}">
            <i data-lucide="file-spreadsheet" class="w-5 h-5 mb-0.5"></i>
            <span>Laporan</span>
        </a>

    </nav>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                <span class="font-medium text-slate-600">NikelFleet Management System</span>
            </div>
            <div>
                &copy; {{ date('Y') }} PT Nikel Mining Nusantara. Seluruh Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <!-- Global Scripts -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
            if (window.Chart) {
                Chart.defaults.font.family = "'Plus Jakarta Sans', sans-serif";
            }
        });
        document.addEventListener('alpine:initialized', () => {
            lucide.createIcons();
        });

        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin mengakhiri sesi kerja ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#64748b',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5',
                    cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            timer: 3500,
            timerProgressBar: true,
            confirmButtonColor: '#059669',
            customClass: { popup: 'rounded-2xl' }
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Perhatian',
            text: "{{ session('error') }}",
            confirmButtonColor: '#e11d48',
            customClass: { popup: 'rounded-2xl' }
        });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
