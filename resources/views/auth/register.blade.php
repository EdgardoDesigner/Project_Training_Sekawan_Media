<!DOCTYPE html>
<html lang="id" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Akun Baru — NikelFleet</title>
    
    <!-- Google Fonts: Plus Jakarta Sans -->
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
<body class="min-h-screen flex items-center justify-center p-4 bg-gradient-to-b from-slate-950 via-slate-900 to-slate-950 font-sans text-slate-100 antialiased py-10">

    <div class="w-full max-w-lg space-y-6">
        
        <!-- Header Brand -->
        <div class="text-center space-y-2">
            <div class="inline-flex items-center justify-center w-12 h-12 rounded-2xl bg-gradient-to-tr from-brand-600 to-emerald-400 text-slate-950 shadow-lg shadow-emerald-500/20">
                <i data-lucide="layers" class="w-6 h-6 stroke-[2.5]"></i>
            </div>
            <h1 class="text-2xl font-black tracking-tight text-white">Nikel<span class="text-brand-500">Fleet</span></h1>
            <p class="text-xs text-slate-400">Registrasi Personel Manajemen & Operasional Tambang</p>
        </div>

        <!-- Register Card -->
        <div class="bg-slate-900/90 border border-slate-800 rounded-2xl p-6 sm:p-8 shadow-2xl backdrop-blur-xl">
            
            <!-- Auth Navigation Tabs -->
            <div class="grid grid-cols-2 gap-1 p-1 bg-slate-950 border border-slate-800 rounded-xl mb-6">
                <a href="{{ route('login') }}" 
                   class="py-2 text-center text-xs font-bold rounded-lg text-slate-400 hover:text-white transition flex items-center justify-center space-x-1.5">
                    <i data-lucide="log-in" class="w-3.5 h-3.5"></i>
                    <span>Masuk</span>
                </a>
                <a href="{{ route('register') }}" 
                   class="py-2 text-center text-xs font-bold rounded-lg bg-brand-600 text-white shadow-sm flex items-center justify-center space-x-1.5">
                    <i data-lucide="user-plus" class="w-3.5 h-3.5"></i>
                    <span>Daftar Akun</span>
                </a>
            </div>

            <div class="mb-5">
                <h2 class="text-base font-bold text-white">Buat Akun Baru</h2>
                <p class="text-xs text-slate-400 mt-0.5">Lengkapi profil Anda untuk mulai mengakses sistem.</p>
            </div>

            @if($errors->any())
            <div class="mb-5 bg-rose-500/10 border border-rose-500/30 text-rose-300 px-4 py-3 rounded-xl text-xs space-y-1">
                <div class="font-bold flex items-center space-x-1.5">
                    <i data-lucide="alert-circle" class="w-4 h-4 text-rose-400 shrink-0"></i>
                    <span>Terjadi kesalahan pengisian form:</span>
                </div>
                <ul class="list-disc pl-5 space-y-0.5 text-[11px] text-rose-300/90">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form action="{{ route('register.post') }}" method="POST" class="space-y-4">
                @csrf

                <!-- Nama Lengkap -->
                <div>
                    <label for="nama" class="block text-xs font-semibold text-slate-300 mb-1.5">Nama Lengkap</label>
                    <div class="relative">
                        <i data-lucide="user" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                        <input type="text" id="nama" name="nama" value="{{ old('nama') }}" required autofocus
                               placeholder="Contoh: Rian Pratama, S.T."
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                    </div>
                </div>

                <!-- Email Perusahaan -->
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-300 mb-1.5">Email Perusahaan</label>
                    <div class="relative">
                        <i data-lucide="mail" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required
                               placeholder="nama@nikelfleet.com"
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                    </div>
                </div>

                <!-- Peran & Jabatan Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="peran" class="block text-xs font-semibold text-slate-300 mb-1.5">Peran Akun</label>
                        <div class="relative">
                            <select id="peran" name="peran" required
                                    class="w-full appearance-none pl-3.5 pr-9 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white outline-none transition cursor-pointer">
                                <option value="admin" {{ old('peran') === 'admin' ? 'selected' : '' }}>Admin Pool Kendaraan</option>
                                <option value="penyetuju" {{ old('peran') === 'penyetuju' ? 'selected' : '' }}>Pihak Penyetuju (Approver)</option>
                            </select>
                            <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-3 text-slate-400">
                                <i data-lucide="chevron-down" class="w-4 h-4"></i>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="jabatan" class="block text-xs font-semibold text-slate-300 mb-1.5">Jabatan / Divisi</label>
                        <div class="relative">
                            <i data-lucide="briefcase" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                            <input type="text" id="jabatan" name="jabatan" value="{{ old('jabatan') }}"
                                   placeholder="Misal: Spv Mine Ops"
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                        </div>
                    </div>
                </div>

                <!-- Nomor Telepon -->
                <div>
                    <label for="nomor_telepon" class="block text-xs font-semibold text-slate-300 mb-1.5">Nomor Telepon / WhatsApp (Opsional)</label>
                    <div class="relative">
                        <i data-lucide="phone" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                        <input type="text" id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}"
                               placeholder="0812xxxxxxxx"
                               class="w-full pl-10 pr-3.5 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                    </div>
                </div>

                <!-- Kata Sandi & Konfirmasi Grid -->
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                    <div>
                        <label for="reg-password" class="block text-xs font-semibold text-slate-300 mb-1.5">Kata Sandi</label>
                        <div class="relative">
                            <i data-lucide="lock" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                            <input type="password" id="reg-password" name="password" required
                                   placeholder="Minimal 6 karakter"
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                            
                            <button type="button" onclick="togglePasswordVisibility('reg-password', 'eye-icon-reg1')" 
                                    class="absolute right-3.5 top-3 text-slate-400 hover:text-white transition focus:outline-none"
                                    title="Lihat Kata Sandi">
                                <i data-lucide="eye" id="eye-icon-reg1" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>

                    <div>
                        <label for="reg-password-confirm" class="block text-xs font-semibold text-slate-300 mb-1.5">Ulangi Kata Sandi</label>
                        <div class="relative">
                            <i data-lucide="shield-check" class="w-4 h-4 text-slate-500 absolute left-3.5 top-3"></i>
                            <input type="password" id="reg-password-confirm" name="password_confirmation" required
                                   placeholder="Ulangi kata sandi"
                                   class="w-full pl-10 pr-10 py-2.5 rounded-xl bg-slate-950 border border-slate-800 focus:border-brand-500 focus:ring-1 focus:ring-brand-500 text-xs sm:text-sm text-white placeholder-slate-500 outline-none transition">
                            
                            <button type="button" onclick="togglePasswordVisibility('reg-password-confirm', 'eye-icon-reg2')" 
                                    class="absolute right-3.5 top-3 text-slate-400 hover:text-white transition focus:outline-none"
                                    title="Lihat Kata Sandi">
                                <i data-lucide="eye" id="eye-icon-reg2" class="w-4 h-4"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Submit Button -->
                <button type="submit" 
                        class="w-full py-2.5 px-4 rounded-xl bg-brand-600 hover:bg-brand-500 text-white font-bold text-xs sm:text-sm shadow-lg shadow-brand-600/25 transition active:scale-95 flex items-center justify-center space-x-2 mt-2">
                    <i data-lucide="user-plus" class="w-4 h-4"></i>
                    <span>Daftar Akun Baru</span>
                </button>

                <div class="text-center pt-2">
                    <span class="text-xs text-slate-400">Sudah memiliki akun terdaftar? </span>
                    <a href="{{ route('login') }}" class="text-xs font-bold text-brand-400 hover:text-brand-300 underline underline-offset-2">
                        Masuk di sini
                    </a>
                </div>
            </form>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            lucide.createIcons();
        });

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

