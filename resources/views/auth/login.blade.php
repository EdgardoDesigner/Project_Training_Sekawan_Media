<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - NikelFleet</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gradient-to-br from-slate-900 via-slate-800 to-emerald-950 min-h-screen flex items-center justify-center p-4">

    <div class="w-full max-w-md">
        <!-- Logo & Title -->
        <div class="text-center mb-6">
            <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-500 text-white font-black text-2xl shadow-xl shadow-emerald-500/20 mb-3">
                ⚡
            </div>
            <h1 class="text-2xl sm:text-3xl font-extrabold text-white tracking-tight">Nikel<span class="text-emerald-400">Fleet</span></h1>
            <p class="text-xs sm:text-sm text-slate-400 mt-1">Sistem Pemesanan & Monitoring Kendaraan Tambang</p>
        </div>

        <!-- Login Card -->
        <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-2xl border border-white/20 p-6 sm:p-8">
            <h2 class="text-lg font-bold text-slate-800 mb-1">Masuk ke Akun Anda</h2>
            <p class="text-xs text-slate-500 mb-6">Silakan masukkan email dan password untuk melanjutkan.</p>

            @if($errors->any())
            <div class="mb-4 bg-rose-50 border border-rose-200 text-rose-700 px-4 py-3 rounded-xl text-xs font-medium">
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('success'))
            <div class="mb-4 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-xl text-xs font-medium">
                {{ session('success') }}
            </div>
            @endif

            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="email" class="block text-xs font-semibold text-slate-700 mb-1">Email Perusahaan</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                           placeholder="nama@nikelfleet.com"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-slate-700 mb-1">Password</label>
                    <input type="password" id="password" name="password" required
                           placeholder="••••••••"
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500 text-sm outline-none transition">
                </div>

                <div class="flex items-center justify-between text-xs">
                    <label class="flex items-center text-slate-600 cursor-pointer">
                        <input type="checkbox" name="remember" class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-500 mr-2">
                        Ingat Saya
                    </label>
                </div>

                <button type="submit" 
                        class="w-full py-3 px-4 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-lg shadow-emerald-600/30 transition transform active:scale-98">
                    Masuk ke Sistem
                </button>
            </form>

            <!-- Quick Demo Login Helpers -->
            <div class="mt-6 pt-5 border-t border-slate-200">
                <div class="text-[11px] font-semibold text-slate-400 uppercase tracking-wider text-center mb-3">
                    ⚡ Klik Cepat untuk Uji Coba Demo Akun:
                </div>
                <div class="grid grid-cols-1 gap-2">
                    <button type="button" onclick="fillCredentials('admin@nikelfleet.com', 'password123')"
                            class="w-full py-2 px-3 bg-blue-50 hover:bg-blue-100 text-blue-700 rounded-lg text-xs font-semibold flex items-center justify-between border border-blue-200 transition">
                        <span>👤 Admin Pool Kendaraan</span>
                        <span class="text-[10px] bg-blue-200 text-blue-800 px-1.5 py-0.5 rounded">Isi Form</span>
                    </button>
                    <button type="button" onclick="fillCredentials('spv.tambang@nikelfleet.com', 'password123')"
                            class="w-full py-2 px-3 bg-emerald-50 hover:bg-emerald-100 text-emerald-700 rounded-lg text-xs font-semibold flex items-center justify-between border border-emerald-200 transition">
                        <span>✅ Approver Level 1 (Spv Tambang)</span>
                        <span class="text-[10px] bg-emerald-200 text-emerald-800 px-1.5 py-0.5 rounded">Isi Form</span>
                    </button>
                    <button type="button" onclick="fillCredentials('gm.operasional@nikelfleet.com', 'password123')"
                            class="w-full py-2 px-3 bg-purple-50 hover:bg-purple-100 text-purple-700 rounded-lg text-xs font-semibold flex items-center justify-between border border-purple-200 transition">
                        <span>⭐ Approver Level 2 (GM Operasional)</span>
                        <span class="text-[10px] bg-purple-200 text-purple-800 px-1.5 py-0.5 rounded">Isi Form</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function fillCredentials(email, password) {
            document.getElementById('email').value = email;
            document.getElementById('password').value = password;
        }
    </script>
</body>
</html>

