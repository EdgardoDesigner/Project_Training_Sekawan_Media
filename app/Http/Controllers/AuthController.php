<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('approver.dashboard');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();

            LogAktivitas::catat('LOGIN', 'Pengguna masuk ke dalam sistem');

            if (Auth::user()->isAdmin()) {
                return redirect()->intended(route('admin.dashboard'));
            }

            return redirect()->intended(route('approver.dashboard'));
        }

        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return Auth::user()->isAdmin()
                ? redirect()->route('admin.dashboard')
                : redirect()->route('approver.dashboard');
        }

        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'peran' => ['required', 'in:admin,penyetuju'],
            'jabatan' => ['nullable', 'string', 'max:255'],
            'nomor_telepon' => ['nullable', 'string', 'max:20'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email perusahaan wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar di sistem.',
            'peran.required' => 'Pilih peran akun Anda.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min' => 'Kata sandi minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
        ]);

        $user = \App\Models\User::create([
            'nama' => $validated['nama'],
            'email' => $validated['email'],
            'peran' => $validated['peran'],
            'jabatan' => $validated['jabatan'] ?? ($validated['peran'] === 'admin' ? 'Admin Pool Kendaraan' : 'Approver Operasional'),
            'nomor_telepon' => $validated['nomor_telepon'] ?? null,
            'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        LogAktivitas::catat('REGISTER', "Pengguna {$user->nama} mendaftar sebagai {$user->peran}");

        $targetRoute = $user->isAdmin() ? 'admin.dashboard' : 'approver.dashboard';

        return redirect()->route($targetRoute)->with('success', 'Registrasi berhasil! Selamat datang di NikelFleet.');
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            LogAktivitas::catat('LOGOUT', 'Pengguna keluar dari sistem');
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar dari sistem.');
    }
}

