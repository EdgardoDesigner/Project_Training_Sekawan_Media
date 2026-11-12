@extends('layouts.app')

@section('title', 'Log Aktivitas Sistem - NikelFleet')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Audit Trail & Log Aktivitas</h1>
            <p class="text-xs sm:text-sm text-slate-500">Pencatatan rekam jejak otomatis setiap aksi transaksi di sistem NikelFleet</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-semibold bg-blue-100 text-blue-800 px-3 py-1 rounded-full">
                {{ $logs->total() }} Total Log Tercatat
            </span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form x-ref="filterForm" method="GET" action="{{ route('admin.logs.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari deskripsi aktivitas..." 
                       class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-2.5"></i>
            </div>

            @php
                $aksiMap = [
                    'BUAT_PEMESANAN' => 'Buat Pemesanan',
                    'SETUJUI_LEVEL_1' => 'Setujui Level 1',
                    'SETUJUI_LEVEL_2' => 'Setujui Level 2',
                    'TOLAK_PEMESANAN' => 'Tolak Pemesanan',
                    'SELESAIKAN_PEMESANAN' => 'Selesaikan Perjalanan',
                    'LOGIN' => 'Login',
                    'LOGOUT' => 'Logout',
                ];
                $currentAksiLabel = $aksiMap[request('aksi')] ?? 'Semua Tipe Aksi';
            @endphp
            <div class="sm:w-56 relative" x-data="{ open: false, label: '{{ $currentAksiLabel }}', value: '{{ request('aksi') }}' }">
                <input type="hidden" name="aksi" :value="value">
                <button type="button" @click="open = !open" 
                        class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white text-slate-800 transition outline-none">
                    <span x-text="label"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <!-- Dropdown Menu -->
                <div x-show="open" @click.away="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-72 overflow-y-auto">
                    <button type="button" @click="value = ''; label = 'Semua Tipe Aksi'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Semua Tipe Aksi</span>
                        <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    @foreach($aksiMap as $val => $lbl)
                    <button type="button" @click="value = '{{ $val }}'; label = '{{ $lbl }}'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>{{ $lbl }}</span>
                        <i x-show="value === '{{ $val }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    @endforeach
                </div>
            </div>

            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition active:scale-95 flex items-center justify-center space-x-1.5">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span>Filter</span>
            </button>
            @if(request()->hasAny(['search', 'aksi']))
            <a href="{{ route('admin.logs.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition shrink-0 flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Log Table & Cards -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Mobile Card List -->
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($logs as $log)
            <div class="p-4 space-y-1.5 text-xs">
                <div class="flex items-center justify-between">
                    <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded text-[11px]">
                        {{ $log->aksi }}
                    </span>
                    <span class="text-[10px] text-slate-400">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </span>
                </div>
                <div class="text-slate-700 leading-relaxed font-medium">
                    {{ $log->deskripsi }}
                </div>
                <div class="text-[10px] text-slate-400 flex items-center justify-between pt-1">
                    <span>Pelaku: <strong class="text-slate-600">{{ $log->pengguna->nama ?? 'Sistem' }}</strong></span>
                    <span>IP: {{ $log->alamat_ip ?? '-' }}</span>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 text-xs">
                Belum ada aktivitas yang tercatat.
            </div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase tracking-wider font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Waktu</th>
                        <th class="py-3 px-4">Aksi</th>
                        <th class="py-3 px-4">Pengguna</th>
                        <th class="py-3 px-4">Deskripsi Aktivitas</th>
                        <th class="py-3 px-4">Alamat IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4 text-slate-500 font-mono text-[11px] whitespace-nowrap">
                            {{ $log->created_at->format('d M Y H:i:s') }}
                        </td>
                        <td class="py-3 px-4">
                            <span class="font-bold font-mono text-[10px] px-2 py-0.5 rounded
                                @if(str_contains($log->aksi, 'BUAT')) bg-blue-100 text-blue-800
                                @elseif(str_contains($log->aksi, 'SETUJUI')) bg-emerald-100 text-emerald-800
                                @elseif(str_contains($log->aksi, 'TOLAK')) bg-rose-100 text-rose-800
                                @elseif(str_contains($log->aksi, 'SELESAIKAN')) bg-teal-100 text-teal-800
                                @else bg-slate-100 text-slate-700 @endif">
                                {{ $log->aksi }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-semibold text-slate-800 whitespace-nowrap">
                            {{ $log->pengguna->nama ?? 'Sistem' }}
                            <div class="text-[10px] text-slate-400 font-normal">{{ $log->pengguna->jabatan ?? '' }}</div>
                        </td>
                        <td class="py-3 px-4 text-slate-700 leading-relaxed">
                            {{ $log->deskripsi }}
                        </td>
                        <td class="py-3 px-4 text-slate-400 font-mono text-[11px] whitespace-nowrap">
                            {{ $log->alamat_ip ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">
                            Tidak ada log aktivitas yang cocok dengan pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $logs->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
