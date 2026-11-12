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
           class="inline-flex items-center justify-center space-x-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold px-4 py-2.5 rounded-xl shadow transition text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path>
            </svg>
            <span>+ Buat Pemesanan</span>
        </a>
    </div>

    <!-- Filter & Search Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.pemesanan.index') }}" class="flex flex-col sm:flex-row gap-3">
            <div class="flex-1 relative">
                <input type="text" name="search" value="{{ request('search') }}" 
                       placeholder="Cari kode booking, nama pemesan, divisi..." 
                       class="w-full pl-9 pr-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                <svg class="w-4 h-4 text-slate-400 absolute left-3 top-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
            </div>

            <div class="sm:w-48">
                <select name="status" onchange="this.form.submit()" 
                        class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Status</option>
                    <option value="menunggu_level_1" {{ request('status') === 'menunggu_level_1' ? 'selected' : '' }}>⏳ Menunggu Level 1</option>
                    <option value="menunggu_level_2" {{ request('status') === 'menunggu_level_2' ? 'selected' : '' }}>⏳ Menunggu Level 2</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>✓ Disetujui (Siap Jalan)</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>✕ Ditolak</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>✓ Selesai</option>
                </select>
            </div>

            <button type="submit" class="px-4 py-2 bg-slate-800 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-900 transition">
                Filter
            </button>
            @if(request()->hasAny(['search', 'status']))
            <a href="{{ route('admin.pemesanan.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-700 text-xs sm:text-sm font-medium flex items-center justify-center">
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
                    <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">
                        {{ $p->kode_pemesanan }}
                    </span>
                    @if($p->status_pemesanan === 'menunggu_level_1')
                        <span class="text-[11px] font-semibold bg-amber-100 text-amber-800 px-2 py-0.5 rounded-full">
                            ⏳ Menunggu L1
                        </span>
                    @elseif($p->status_pemesanan === 'menunggu_level_2')
                        <span class="text-[11px] font-semibold bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full">
                            ⏳ Menunggu L2
                        </span>
                    @elseif($p->status_pemesanan === 'disetujui')
                        <span class="text-[11px] font-semibold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                            ✓ Disetujui
                        </span>
                    @elseif($p->status_pemesanan === 'ditolak')
                        <span class="text-[11px] font-semibold bg-rose-100 text-rose-800 px-2 py-0.5 rounded-full">
                            ✕ Ditolak
                        </span>
                    @elseif($p->status_pemesanan === 'selesai')
                        <span class="text-[11px] font-semibold bg-slate-100 text-slate-700 px-2 py-0.5 rounded-full">
                            ✓ Selesai
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
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-800">
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
                                <span class="text-[11px] font-semibold bg-amber-100 text-amber-800 px-2.5 py-1 rounded-full">
                                    ⏳ Menunggu L1
                                </span>
                            @elseif($p->status_pemesanan === 'menunggu_level_2')
                                <span class="text-[11px] font-semibold bg-blue-100 text-blue-800 px-2.5 py-1 rounded-full">
                                    ⏳ Menunggu L2
                                </span>
                            @elseif($p->status_pemesanan === 'disetujui')
                                <span class="text-[11px] font-semibold bg-emerald-100 text-emerald-800 px-2.5 py-1 rounded-full">
                                    ✓ Disetujui
                                </span>
                            @elseif($p->status_pemesanan === 'ditolak')
                                <span class="text-[11px] font-semibold bg-rose-100 text-rose-800 px-2.5 py-1 rounded-full">
                                    ✕ Ditolak
                                </span>
                            @elseif($p->status_pemesanan === 'selesai')
                                <span class="text-[11px] font-semibold bg-slate-100 text-slate-700 px-2.5 py-1 rounded-full">
                                    ✓ Selesai
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

