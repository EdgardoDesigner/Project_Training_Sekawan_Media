<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'NikelFleet - Monitoring Kendaraan Tambang')</title>

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        },
                        mining: {
                            800: '#1e293b',
                            900: '#0f172a',
                            950: '#020617',
                        }
                    },
                    animation: {
                        'bounce-short': 'bounce 0.8s ease-in-out 1',
                        'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                    }
                }
            }
        }
    </script>

    <!-- Lucide Icons (Ultra Crisp Modern Icons) -->
    <script src="https://unpkg.com/lucide@latest"></script>

    <!-- Alpine.js (Micro-interactions & reactive components) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- SweetAlert2 (Delightful Animated Dialogs & Toasts) -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Canvas Confetti (Celebration Micro-Interaction) -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.9.2/dist/confetti.browser.min.js"></script>

    <!-- Chart.js (Interactive Animated Graphs) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        [x-cloak] { display: none !important; }
        /* Smooth Scrolling & Transitions */
        html { scroll-behavior: smooth; }
        .glass-card {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
    </style>
</head>
<body class="bg-slate-50 text-slate-800 min-h-screen flex flex-col font-sans antialiased pb-20 md:pb-0 selection:bg-emerald-500 selection:text-white"
      x-data="{ mobileMenuOpen: false }">

    <!-- Top Navigation Bar -->
    <header class="bg-mining-950/95 backdrop-blur-md text-white sticky top-0 z-30 shadow-lg border-b border-slate-800">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 group">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-tr from-emerald-600 to-emerald-400 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20 group-hover:scale-105 transition transform">
                            <i data-lucide="zap" class="w-5 h-5 text-mining-950 stroke-[2.5]"></i>
                        </div>
                        <div>
                            <div class="font-black text-lg tracking-tight text-white flex items-center gap-1.5">
                                Nikel<span class="text-emerald-400">Fleet</span>
                                <span class="hidden sm:inline-block text-[10px] bg-emerald-950/80 text-emerald-400 font-bold px-2 py-0.5 rounded-full border border-emerald-800/80">
                                    Mining Fleet Ops
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links with Lucide Icons -->
                <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="flex items-center space-x-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <i data-lucide="layout-dashboard" class="w-4 h-4"></i>
                        <span>Dashboard</span>
                    </a>

                    <a href="{{ route('admin.pemesanan.index') }}" 
                       class="flex items-center space-x-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.pemesanan.*') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <i data-lucide="calendar-check" class="w-4 h-4"></i>
                        <span>Pemesanan</span>
                    </a>

                    <a href="{{ route('admin.kendaraan.index') }}" 
                       class="flex items-center space-x-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.kendaraan.*') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                        <span>Armada</span>
                    </a>

                    <a href="{{ route('admin.laporan.index') }}" 
                       class="flex items-center space-x-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.laporan.*') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                        <span>Laporan</span>
                    </a>

                    <a href="{{ route('admin.logs.index') }}" 
                       class="flex items-center space-x-2 px-3.5 py-2 rounded-xl text-xs font-bold transition-all duration-200 {{ request()->routeIs('admin.logs.*') ? 'bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 shadow-sm' : 'text-slate-300 hover:bg-slate-800/70 hover:text-white' }}">
                        <i data-lucide="shield-check" class="w-4 h-4"></i>
                        <span>Audit Log</span>
                    </a>
                </nav>

                <!-- User Profile & Logout -->
                <div class="flex items-center space-x-3">
                    @auth
                    <div class="text-right hidden sm:block">
                        <div class="text-xs font-bold text-slate-100 flex items-center justify-end gap-1.5">
                            <i data-lucide="user" class="w-3.5 h-3.5 text-emerald-400"></i>
                            {{ Auth::user()->nama }}
                        </div>
                        <div class="text-[10px] text-emerald-400/90 font-medium tracking-wider">
                            {{ Auth::user()->jabatan ?? ucfirst(Auth::user()->peran) }}
                        </div>
                    </div>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="button" 
                                onclick="confirmLogout()"
                                class="p-2 sm:px-3 sm:py-1.5 rounded-xl text-slate-300 hover:text-white hover:bg-rose-500/20 hover:border-rose-500/40 border border-slate-700/80 transition text-xs font-semibold flex items-center space-x-1.5">
                            <i data-lucide="log-out" class="w-4 h-4 text-rose-400"></i>
                            <span class="hidden sm:inline">Keluar</span>
                        </button>
                    </form>
                    @endauth
                </div>

            </div>
        </div>
    </header>

    <!-- Main Content Container -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-4 sm:px-6 lg:px-8 py-6">
        @yield('content')
    </main>

    <!-- Mobile-First Bottom Navigation Bar with Micro-interactions -->
    <nav class="md:hidden fixed bottom-0 left-0 right-0 bg-white/95 backdrop-blur-lg border-t border-slate-200/80 z-40 shadow-2xl px-2 py-1.5 flex justify-around items-center">
        
        <a href="{{ route('admin.dashboard') }}" 
           class="flex flex-col items-center py-1 px-3 text-[11px] transition transform active:scale-90 {{ request()->routeIs('admin.dashboard') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
            <i data-lucide="layout-dashboard" class="w-5 h-5 mb-0.5"></i>
            <span>Home</span>
        </a>

        <a href="{{ route('admin.pemesanan.index') }}" 
           class="flex flex-col items-center py-1 px-3 text-[11px] transition transform active:scale-90 {{ request()->routeIs('admin.pemesanan.*') && !request()->routeIs('admin.pemesanan.create') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
            <i data-lucide="calendar-check" class="w-5 h-5 mb-0.5"></i>
            <span>Pesan</span>
        </a>

        <!-- FAB (Floating Action Button) for New Booking -->
        <a href="{{ route('admin.pemesanan.create') }}" 
           class="flex flex-col items-center -mt-6 bg-gradient-to-tr from-emerald-600 to-emerald-400 text-white rounded-full p-3.5 shadow-xl shadow-emerald-500/40 hover:scale-105 active:scale-95 transition transform border-4 border-white">
            <i data-lucide="plus" class="w-6 h-6 stroke-[3]"></i>
        </a>

        <a href="{{ route('admin.kendaraan.index') }}" 
           class="flex flex-col items-center py-1 px-3 text-[11px] transition transform active:scale-90 {{ request()->routeIs('admin.kendaraan.*') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
            <i data-lucide="truck" class="w-5 h-5 mb-0.5"></i>
            <span>Armada</span>
        </a>

        <a href="{{ route('admin.logs.index') }}" 
           class="flex flex-col items-center py-1 px-3 text-[11px] transition transform active:scale-90 {{ request()->routeIs('admin.logs.*') ? 'text-emerald-600 font-extrabold' : 'text-slate-500 hover:text-slate-800 font-medium' }}">
            <i data-lucide="history" class="w-5 h-5 mb-0.5"></i>
            <span>Audit</span>
        </a>

    </nav>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200/80 py-4 mt-auto">
        <div class="max-w-7xl mx-auto px-4 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-500">
            <div class="flex items-center space-x-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                <span>NikelFleet Operating System v1.0</span>
            </div>
            <div>
                &copy; {{ date('Y') }} PT Nikel Mining Nusantara. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    <!-- Global Scripts & SweetAlert2 Flash Notifications -->
    <script>
        // Inisialisasi Lucide Icons
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        // Konfirmasi Logout dengan SweetAlert2
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Keluar',
                text: 'Apakah Anda yakin ingin keluar dari sesi kerja ini?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#059669',
                cancelButtonColor: '#94a3b8',
                confirmButtonText: 'Ya, Keluar',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                customClass: {
                    popup: 'rounded-2xl',
                    confirmButton: 'rounded-xl text-xs font-bold px-4 py-2.5',
                    cancelButton: 'rounded-xl text-xs font-bold px-4 py-2.5'
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logout-form').submit();
                }
            });
        }

        // Trigger SweetAlert Flash Notification dari Session Laravel
        @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: "{{ session('success') }}",
            timer: 4000,
            timerProgressBar: true,
            confirmButtonColor: '#059669',
            customClass: { popup: 'rounded-2xl' }
        });
        @endif

        @if(session('error'))
        Swal.fire({
            icon: 'error',
            title: 'Perhatian!',
            text: "{{ session('error') }}",
            confirmButtonColor: '#e11d48',
            customClass: { popup: 'rounded-2xl' }
        });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
