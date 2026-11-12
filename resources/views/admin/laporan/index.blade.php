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
    <div class="bg-white p-5 rounded-3xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3.5">
            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ request('start_date', $startDate->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ request('end_date', $endDate->format('Y-m-d')) }}"
                       class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Status</label>
                <select name="status" class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                    <option value="">Semua Status</option>
                    <option value="disetujui" {{ request('status') === 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                    <option value="menunggu_level_1" {{ request('status') === 'menunggu_level_1' ? 'selected' : '' }}>Menunggu L1</option>
                    <option value="menunggu_level_2" {{ request('status') === 'menunggu_level_2' ? 'selected' : '' }}>Menunggu L2</option>
                    <option value="ditolak" {{ request('status') === 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>

            <div>
                <label class="block text-[11px] font-bold text-slate-500 uppercase mb-1">Lokasi Tujuan</label>
                <select name="lokasi_id" class="w-full px-3 py-2 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none bg-white">
                    <option value="">Semua Lokasi</option>
                    @foreach($lokasiList as $loc)
                    <option value="{{ $loc->id }}" {{ request('lokasi_id') == $loc->id ? 'selected' : '' }}>
                        {{ $loc->nama_lokasi }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end space-x-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 text-white text-xs sm:text-sm font-bold rounded-xl hover:bg-slate-800 transition">
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>

    <!-- Data Table & Mobile Cards -->
    <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden">
        
        <!-- Mobile Cards -->
        <div class="divide-y divide-slate-100 md:hidden">
            @forelse($pemesanan as $p)
            <div class="p-4 space-y-2 text-xs">
                <div class="flex justify-between items-center">
                    <span class="font-mono font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded">{{ $p->kode_pemesanan }}</span>
                    <span class="font-bold text-[10px] px-2 py-0.5 rounded-full
                        {{ $p->status_pemesanan === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 
                           ($p->status_pemesanan === 'ditolak' ? 'bg-rose-100 text-rose-800' : 'bg-slate-100 text-slate-700') }}">
                        {{ strtoupper(str_replace('_', ' ', $p->status_pemesanan)) }}
                    </span>
                </div>
                <div class="font-bold text-slate-900 text-sm">{{ $p->nama_pemesan }} ({{ $p->divisi_pemesan }})</div>
                <div class="text-slate-500">🚗 {{ $p->kendaraan->nomor_lambung }} &bull; 📍 {{ $p->lokasiTujuan->nama_lokasi }}</div>
                <div class="text-[11px] text-slate-400">📅 {{ $p->tanggal_mulai->format('d/m/Y H:i') }} - {{ $p->tanggal_selesai->format('d/m/Y H:i') }}</div>
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
                        <td class="py-3.5 px-4 font-mono font-bold text-slate-800">{{ $p->kode_pemesanan }}</td>
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
