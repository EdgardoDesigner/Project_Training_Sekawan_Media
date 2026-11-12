@extends('layouts.app')

@section('title', 'Laporan Periodik Pemesanan - NikelFleet')

@section('content')
<div class="space-y-6">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Laporan Periodik Pemesanan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Rekapitulasi penggunaan kendaraan tambang dan ekspor ke format Excel</p>
        </div>

        <div>
            <a href="{{ route('admin.laporan.excel', request()->all()) }}" 
               class="inline-flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold px-5 py-3 rounded-2xl shadow-lg shadow-emerald-600/20 transition transform active:scale-95 text-xs sm:text-sm">
                <i data-lucide="file-spreadsheet" class="w-4 h-4"></i>
                <span>Unduh Laporan Excel (.xls)</span>
            </a>
        </div>
    </div>

    <!-- Filter Bar Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form x-ref="filterForm" method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                           class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none bg-slate-50 hover:bg-white text-slate-800 transition cursor-pointer">
                </div>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <div class="relative">
                    <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                           class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none bg-slate-50 hover:bg-white text-slate-800 transition cursor-pointer">
                </div>
            </div>

            <!-- Dropdown Status -->
            @php
                $statusMap = [
                    'disetujui' => 'Disetujui',
                    'selesai' => 'Selesai',
                    'menunggu_level_1' => 'Menunggu L1',
                    'menunggu_level_2' => 'Menunggu L2',
                    'ditolak' => 'Ditolak',
                ];
                $currentStatusLabel = $statusMap[request('status')] ?? 'Semua Status';
            @endphp
            <div class="relative" x-data="{ open: false, label: '{{ $currentStatusLabel }}', value: '{{ request('status') }}' }">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status</label>
                <input type="hidden" name="status" :value="value">
                <button type="button" @click="open = !open" 
                        class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white text-slate-800 transition outline-none">
                    <span x-text="label" class="truncate pr-1"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" @click.away="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs">
                    <button type="button" @click="value = ''; label = 'Semua Status'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Semua Status</span>
                        <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    @foreach($statusMap as $val => $lbl)
                    <button type="button" @click="value = '{{ $val }}'; label = '{{ $lbl }}'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>{{ $lbl }}</span>
                        <i x-show="value === '{{ $val }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    @endforeach
                </div>
            </div>

            <!-- Dropdown Lokasi -->
            @php
                $selectedLokasi = $lokasiList->firstWhere('id', request('lokasi_id'));
                $currentLokasiLabel = $selectedLokasi ? $selectedLokasi->nama_lokasi : 'Semua Lokasi';
            @endphp
            <div class="relative" x-data="{ open: false, label: '{{ addslashes($currentLokasiLabel) }}', value: '{{ request('lokasi_id') }}' }">
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Lokasi Tujuan</label>
                <input type="hidden" name="lokasi_id" :value="value">
                <button type="button" @click="open = !open" 
                        class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white text-slate-800 transition outline-none">
                    <span x-text="label" class="truncate pr-2"></span>
                    <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 shrink-0 transition-transform" :class="open ? 'rotate-180' : ''"></i>
                </button>

                <div x-show="open" @click.away="open = false" x-cloak
                     x-transition:enter="transition ease-out duration-100"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-56 overflow-y-auto">
                    <button type="button" @click="value = ''; label = 'Semua Lokasi'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Semua Lokasi</span>
                        <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    @foreach($lokasiList as $loc)
                    <button type="button" @click="value = '{{ $loc->id }}'; label = '{{ addslashes($loc->nama_lokasi) }}'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span class="truncate">{{ $loc->nama_lokasi }}</span>
                        <i x-show="value == '{{ $loc->id }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700 shrink-0"></i>
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition active:scale-95 flex items-center justify-center space-x-1.5">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan Filter</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table & Mobile Cards -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Mobile Cards -->
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($pemesanan as $p)
            <div class="p-4 space-y-2 text-xs">
                <div class="flex justify-between items-center">
                    <span class="font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">{{ $p->kode_pemesanan }}</span>
                    <span class="font-bold text-[10px] px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap
                        {{ $p->status_pemesanan === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                           ($p->status_pemesanan === 'ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                        {{ strtoupper(str_replace('_', ' ', $p->status_pemesanan)) }}
                    </span>
                </div>
                <div class="font-bold text-slate-900 text-sm">{{ $p->nama_pemesan }} ({{ $p->divisi_pemesan }})</div>
                <div class="text-slate-600 flex items-center space-x-2 flex-wrap">
                    <span class="inline-flex items-center space-x-1">
                        <i data-lucide="truck" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ $p->kendaraan->nomor_lambung }}</span>
                    </span>
                    <span class="text-slate-300">&bull;</span>
                    <span class="inline-flex items-center space-x-1">
                        <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                        <span>{{ $p->lokasiTujuan->nama_lokasi }}</span>
                    </span>
                </div>
                <div class="text-[11px] text-slate-400 flex items-center space-x-1">
                    <i data-lucide="calendar" class="w-3 h-3 text-slate-400"></i>
                    <span>{{ $p->tanggal_mulai->format('d/m/Y H:i') }} - {{ $p->tanggal_selesai->format('d/m/Y H:i') }}</span>
                </div>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 text-xs">Tidak ada data pemesanan pada periode ini.</div>
            @endforelse
        </div>

        <!-- Desktop Table -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Pemesan & Divisi</th>
                        <th class="py-3 px-4">Kendaraan</th>
                        <th class="py-3 px-4">Pengemudi</th>
                        <th class="py-3 px-4">Tujuan</th>
                        <th class="py-3 px-4">Waktu Pemakaian</th>
                        <th class="py-3 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pemesanan as $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800">{{ $p->kode_pemesanan }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">{{ $p->nama_pemesan }}</div>
                            <div class="text-[11px] text-slate-400">{{ $p->divisi_pemesan }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-800">{{ $p->kendaraan->nomor_lambung }}</div>
                            <div class="text-[11px] text-slate-400">{{ $p->kendaraan->merk_model }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-700">{{ $p->driver->nama_driver }}</td>
                        <td class="py-3.5 px-4 font-medium text-emerald-700">{{ $p->lokasiTujuan->nama_lokasi }}</td>
                        <td class="py-3.5 px-4 text-slate-500 text-[11px]">
                            {{ $p->tanggal_mulai->format('d M Y H:i') }} s/d {{ $p->tanggal_selesai->format('d M H:i') }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-[11px]">
                            <span class="px-2.5 py-1 rounded-full
                                {{ $p->status_pemesanan === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                                   ($p->status_pemesanan === 'ditolak' ? 'bg-rose-100 text-rose-800' : 
                                   ($p->status_pemesanan === 'selesai' ? 'bg-slate-100 text-slate-800' : 'bg-amber-100 text-amber-800')) }}">
                                {{ strtoupper(str_replace('_', ' ', $p->status_pemesanan)) }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">Tidak ada data pemesanan pada periode ini.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pemesanan->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pemesanan->links() }}
        </div>
        @endif

    </div>

</div>
@endsection
