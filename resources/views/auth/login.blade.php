<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk — NikelFleet</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/lucide@latest"></script>
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
                            500: '#10b981',
                            600: '#059669',
                            700: '#047857',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        *, body, button, input, select, textarea {
            font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif !important;
            font-feature-settings: "tnum" 1, "cv02" 1, "cv03" 1, "cv04" 1, "cv11" 1;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 font-sans text-slate-100 antialiased">

    <div class="w-full max-w-md space-y-6">
        
        <!-- Header Brand -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-emerald-400 text-slate-950 shadow-lg shadow-emerald-500/20">
                <i data-lucide="layers" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Nikel<span class="text-brand-500">Fleet</span></h1>
            <p class="text-xs text-slate-400">Sistem Monitoring & Pemesanan Kendaraan Operasional</p>
        </div>

        <!-- Login Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
            
            <!-- Auth Navigation Tabs -->
            <div class="grid grid-cols-2 gap-1 p-1 bg-slate-950 border border-slate-800 rounded-xl mb-6">
                <a href="{{ route('login') }}" 
                   class="py-2 text-center text-xs font-bold rounded-lg bg-brand-600 text-white shadow-sm flex items-center justify-center space-x-1.5">
                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                    <span>Masuk</span>
                </a>
                <a href="{{ route('register') }}" 
                   class="py-2 text-center text-xs font-bold rounded-lg text-slate-400 hover:text-white transition flex items-center justify-center space-x-1.5">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                    <span>Daftar Akun</span>
                </a>
            </div>

            <div class="mb-5">
                <h2 class="text-base font-bold text-white">Masuk ke Akun Anda</h2>
                <p class="text-xs text-slate-400 mt-0.5">Gunakan email perusahaan untuk mengakses dashboard.</p>
            </div>

            @if($errors->any())
            <div class="mb-4 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-3.5 py-2.5 rounded-xl text-xs flex items-center space-x-2">
                <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 flex-shrink-0"></i>
                <span>{{ $errors->first() }}</span>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-4 bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 px-3.5 py-2.5 rounded-xl text-xs flex items-center space-x-2">
                <i data-lucide="check-circle" class="w-4 h-4 text-emerald-400 flex-shrink-0"></i>
                <span>{{ session('success') }}</span>
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email Perusahaan</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="nama@nikelfleet.com"
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi</label>
                    <div class="relative">
                        <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                        <input type="password" id="password" name="password" required
                               placeholder="••••••••"
                               class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                        
                        <!-- Toggle Password Visibility Button -->
                        <button type="button" onclick="togglePasswordVisibility('password', 'eye-icon-login')" 
                                class="absolute right-3.5 top-3 text-slate-400 hover:text-white transition focus:outline-none"
                                title="Lihat Kata Sandi">
                            <i data-lucide="eye" id="eye-icon-login" class="w-4 h-4"></i>
                        </button>
                    </div>
                </div>

                <div class="flex items-center justify-between text-xs pt-1">
                    <label class="inline-flex items-center text-slate-400 hover:text-slate-300 cursor-pointer select-none group">
                        <div class="relative flex items-center justify-center mr-2">
                            <input type="checkbox" name="remember" class="peer sr-only">
                            <div class="w-4 h-4 rounded border border-slate-700 bg-transparent transition-colors peer-checked:bg-brand-600 peer-checked:border-brand-600 group-hover:border-slate-500 flex items-center justify-center"></div>
                            <svg class="w-2.5 h-2.5 text-white absolute pointer-events-none hidden peer-checked:block stroke-[3.5]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                        <span>Ingat sesi masuk</span>
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-brand-600/25 transition active:scale-95 flex items-center justify-center space-x-2">
                    <span>Masuk ke Sistem</span>
                    <i data-lucide="arrow-right" class="w-4 h-4"></i>
                </button>

                <div class="text-center pt-2">
                    <span class="text-xs text-slate-400">Belum memiliki akun? </span>
                    <a href="{{ route('register') }}" class="text-xs font-bold text-brand-400 hover:text-brand-300 underline underline-offset-2">
                        Daftar di sini
                    </a>
                </div>
            </form>

            <!-- Quick Demo Credentials Box -->
            <div class="mt-6 pt-5 border-t border-slate-800/80">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider mb-2.5 flex items-center space-x-1.5">
                    <i data-lucide="key" class="w-3.5 h-3.5 text-brand-400"></i>
                    <span>Akun Demo Cepat (Klik untuk Mengisi):</span>
                </div>
                
                <div class="space-y-1.5">
                    <button type="button" onclick="fillCredentials('admin@nikelfleet.com', 'password123')"
                            class="w-full py-2 px-3 rounded-lg bg-slate-950 hover:bg-slate-800/90 text-left border border-slate-800 text-xs flex items-center justify-between transition group">
                        <div class="flex items-center space-x-2.5">
                            <i data-lucide="user-cog" class="w-4 h-4 text-blue-400"></i>
                            <div>
                                <div class="font-semibold text-slate-200">Admin Pool Kendaraan</div>
                                <div class="text-[10px] text-slate-400">admin@nikelfleet.com</div>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600 group-hover:text-slate-300"></i>
                    </button>

                    <button type="button" onclick="fillCredentials('spv.tambang@nikelfleet.com', 'password123')"
                            class="w-full py-2 px-3 rounded-lg bg-slate-950 hover:bg-slate-800/90 text-left border border-slate-800 text-xs flex items-center justify-between transition group">
                        <div class="flex items-center space-x-2.5">
                            <i data-lucide="check-check" class="w-4 h-4 text-emerald-400"></i>
                            <div>
                                <div class="font-semibold text-slate-200">Approver Level 1 (Spv Tambang)</div>
                                <div class="text-[10px] text-slate-400">spv.tambang@nikelfleet.com</div>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600 group-hover:text-slate-300"></i>
                    </button>

                    <button type="button" onclick="fillCredentials('gm.operasional@nikelfleet.com', 'password123')"
                            class="w-full py-2 px-3 rounded-lg bg-slate-950 hover:bg-slate-800/90 text-left border border-slate-800 text-xs flex items-center justify-between transition group">
                        <div class="flex items-center space-x-2.5">
                            <i data-lucide="shield-check" class="w-4 h-4 text-purple-400"></i>
                            <div>
                                <div class="font-semibold text-slate-200">Approver Level 2 (GM Operasional)</div>
                                <div class="text-[10px] text-slate-400">gm.operasional@nikelfleet.com</div>
                            </div>
                        </div>
                        <i data-lucide="chevron-right" class="w-3.5 h-3.5 text-slate-600 group-hover:text-slate-300"></i>
                    </button>
                </div>
            </div>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }

        function togglePasswordVisibility(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (!input || !icon) return;

            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>
</body>
</html>
