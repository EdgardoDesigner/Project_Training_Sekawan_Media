@extends('layouts.app')

@section('title', 'Daftar Pemesanan Kendaraan - NikelFleet')

@section('content')
<div class="space-y-5">

    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Pemesanan Kendaraan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Kelola riwayat dan status persetujuan pemakaian kendaraan operasional tambang</p>
        </div>
        <a href="{{ route('admin.pemesanan.create') }}" 
           class="inline-flex items-center justify-center space-x-1.5 bg-slate-900 hover:bg-slate-800 text-white font-bold px-4 py-2.5 rounded-xl shadow-sm transition active:scale-95 text-xs sm:text-sm">
            <i data-lucide="plus" class="w-4 h-4 stroke-[2.5]"></i>
            <span>Buat Pemesanan</span>
        </a>
    </div>

    <!-- Filter & Search Bar with Alpine.js Custom Dropdown (100% In-Frame) -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm">
        <form x-ref="filterForm" method="GET" action="{{ route('admin.pemesanan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode booking, nama pemesan, divisi..." 
                       class="w-full pl-9 pr-3 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-slate-900 outline-none bg-slate-50 hover:bg-white transition">
                <i data-lucide="search" class="w-4 h-4 text-slate-400 absolute left-3 top-3"></i>
            </div>

            @php
                $statusMap = [
                    'menunggu_level_1' => 'Menunggu Level 1',
                    'menunggu_level_2' => 'Menunggu Level 2',
                    'disetujui' => 'Disetujui (Siap Jalan)',
                    'ditolak' => 'Ditolak',
                    'selesai' => 'Selesai',
                ];
                $currentStatusLabel = $statusMap[request('status')] ?? 'Semua Status';
            @endphp
            <div class="sm:w-56 relative" x-data="{ open: false, label: '{{ $currentStatusLabel }}', value: '{{ request('status') }}' }">
                <input type="hidden" name="status" :value="value">
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

            <button type="submit" class="px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white text-xs sm:text-sm font-bold rounded-xl shadow-sm transition active:scale-95 flex items-center justify-center space-x-1.5">
                <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                <span>Filter</span>
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.pemesanan.index') }}" class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl text-xs font-semibold transition shrink-0 flex items-center justify-center">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Data List: Mobile Cards + Desktop Table -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Mobile Card View (shown on screen < 768px) -->
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($pemesanan as $p)
            <div class="p-4 space-y-2.5">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200">
                        {{ $p->kode_pemesanan }}
                    </span>
                    @if($p->status_pemesanan === 'menunggu_level_1')
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                            <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                            <span>Menunggu L1</span>
                        </span>
                    @elseif($p->status_pemesanan === 'menunggu_level_2')
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                            <i data-lucide="clock" class="w-3 h-3 text-blue-600"></i>
                            <span>Menunggu L2</span>
                        </span>
                    @elseif($p->status_pemesanan === 'disetujui')
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                            <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                            <span>Disetujui</span>
                        </span>
                    @elseif($p->status_pemesanan === 'ditolak')
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                            <i data-lucide="x-circle" class="w-3 h-3 text-rose-600"></i>
                            <span>Ditolak</span>
                        </span>
                    @elseif($p->status_pemesanan === 'selesai')
                        <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                            <i data-lucide="check-check" class="w-3 h-3 text-slate-500"></i>
                            <span>Selesai</span>
                        </span>
                    @endif
                </div>

                <div>
                    <div class="text-sm font-bold text-slate-800">{{ $p->nama_pemesan }}</div>
                    <div class="text-xs text-slate-500">{{ $p->divisi_pemesan }} &bull; {{ $p->keperluan }}</div>
                </div>

                <div class="bg-slate-50 p-2.5 rounded-xl space-y-1 text-xs text-slate-600">
                    <div class="flex justify-between">
                        <span class="text-slate-400">Unit:</span>
                        <span class="font-semibold text-slate-700">{{ $p->kendaraan->nomor_lambung }} ({{ $p->kendaraan->merk_model }})</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Driver:</span>
                        <span class="font-semibold text-slate-700">{{ $p->driver->nama_driver }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Tujuan:</span>
                        <span class="font-semibold text-slate-700">{{ $p->lokasiTujuan->nama_lokasi }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-slate-400">Jadwal:</span>
                        <span class="font-semibold text-slate-700">{{ $p->tanggal_mulai->format('d/m/Y H:i') }} - {{ $p->tanggal_selesai->format('d/m/Y H:i') }}</span>
                    </div>
                </div>

                <a href="{{ route('admin.pemesanan.show', $p->id) }}" 
                   class="block text-center w-full py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold rounded-lg transition">
                    Lihat Detail & Status Approval &rarr;
                </a>
            </div>
            @empty
            <div class="p-8 text-center text-slate-400 text-xs">
                Tidak ada data pemesanan yang sesuai.
            </div>
            @endforelse
        </div>

        <!-- Desktop Table View (shown on screen >= 768px) -->
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600 font-bold border-b border-slate-200 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Kode Booking</th>
                        <th class="py-3 px-4">Pemesan</th>
                        <th class="py-3 px-4">Kendaraan & Driver</th>
                        <th class="py-3 px-4">Tujuan</th>
                        <th class="py-3 px-4">Waktu Pemakaian</th>
                        <th class="py-3 px-4">Status Approval</th>
                        <th class="py-3 px-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($pemesanan as $p)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3.5 px-4 font-bold text-slate-800 tracking-tight">
                            {{ $p->kode_pemesanan }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-800">{{ $p->nama_pemesan }}</div>
                            <div class="text-[11px] text-slate-500">{{ $p->divisi_pemesan }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-800">{{ $p->kendaraan->nomor_lambung }} - {{ $p->kendaraan->merk_model }}</div>
                            <div class="text-[11px] text-slate-500">Driver: {{ $p->driver->nama_driver }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-medium text-slate-700">
                            {{ $p->lokasiTujuan->nama_lokasi }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600 text-[11px]">
                            <div>Mulai: {{ $p->tanggal_mulai->format('d M Y H:i') }}</div>
                            <div>Sampai: {{ $p->tanggal_selesai->format('d M Y H:i') }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            @if($p->status_pemesanan === 'menunggu_level_1')
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                                    <i data-lucide="clock" class="w-3 h-3 text-amber-600"></i>
                                    <span>Menunggu L1</span>
                                </span>
                            @elseif($p->status_pemesanan === 'menunggu_level_2')
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                                    <i data-lucide="clock" class="w-3 h-3 text-blue-600"></i>
                                    <span>Menunggu L2</span>
                                </span>
                            @elseif($p->status_pemesanan === 'disetujui')
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                                    <i data-lucide="check-circle-2" class="w-3 h-3 text-emerald-600"></i>
                                    <span>Disetujui</span>
                                </span>
                            @elseif($p->status_pemesanan === 'ditolak')
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                                    <i data-lucide="x-circle" class="w-3 h-3 text-rose-600"></i>
                                    <span>Ditolak</span>
                                </span>
                            @elseif($p->status_pemesanan === 'selesai')
                                <span class="inline-flex items-center gap-1 text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200 px-2.5 py-0.5 rounded-full shrink-0 whitespace-nowrap">
                                    <i data-lucide="check-check" class="w-3 h-3 text-slate-500"></i>
                                    <span>Selesai</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('admin.pemesanan.show', $p->id) }}" 
                               class="text-xs font-semibold px-2.5 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition inline-block">
                                Detail &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400">
                            Belum ada pemesanan kendaraan yang ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($pemesanan->hasPages())
        <div class="p-4 border-t border-slate-100">
            {{ $pemesanan->links() }}
        </div>
        @endif

    </div>

</div>
@endsection

