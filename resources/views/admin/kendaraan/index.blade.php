@extends('layouts.app')

@section('title', 'Monitoring Armada Kendaraan - NikelFleet')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Monitoring Armada Kendaraan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Pantau konsumsi BBM, jadwal servis berkala, dan penempatan unit di lokasi tambang</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-semibold bg-emerald-100 text-emerald-800 px-3 py-1 rounded-full">
                {{ $kendaraan->count() }} Total Unit Armada
            </span>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/80 shadow-sm">
        <form method="GET" action="{{ route('admin.kendaraan.index') }}" class="grid grid-cols-1 sm:grid-cols-4 gap-3">
            <div>
                <select name="jenis" onchange="this.form.submit()" 
                        class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Jenis (Orang & Barang)</option>
                    <option value="angkutan_orang" {{ request('jenis') === 'angkutan_orang' ? 'selected' : '' }}>Angkutan Orang</option>
                    <option value="angkutan_barang" {{ request('jenis') === 'angkutan_barang' ? 'selected' : '' }}>Angkutan Barang</option>
                </select>
            </div>

            <div>
                <select name="kepemilikan" onchange="this.form.submit()" 
                        class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Kepemilikan (Milik & Sewa)</option>
                    <option value="milik_perusahaan" {{ request('kepemilikan') === 'milik_perusahaan' ? 'selected' : '' }}>Milik Perusahaan</option>
                    <option value="sewa" {{ request('kepemilikan') === 'sewa' ? 'selected' : '' }}>Sewa (Vendor Rental)</option>
                </select>
            </div>

            <div>
                <select name="status" onchange="this.form.submit()" 
                        class="w-full py-2 px-3 text-xs sm:text-sm rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none">
                    <option value="">Semua Status Operasional</option>
                    <option value="tersedia" {{ request('status') === 'tersedia' ? 'selected' : '' }}>Tersedia (Ready)</option>
                    <option value="digunakan" {{ request('status') === 'digunakan' ? 'selected' : '' }}>Sedang Digunakan</option>
                    <option value="perbaikan" {{ request('status') === 'perbaikan' ? 'selected' : '' }}>Dalam Perbaikan</option>
                </select>
            </div>

            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2 px-4 bg-slate-800 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-900 transition">
                    Terapkan
                </button>
                @if(request()->hasAny(['jenis', 'kepemilikan', 'status']))
                <a href="{{ route('admin.kendaraan.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-800 text-xs font-semibold">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Vehicle Cards Grid (Mobile-First Card Layout) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($kendaraan as $v)
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3.5 hover:shadow-md transition">
            
            <!-- Header Card: Lambung, Plat & Status -->
            <div class="flex items-start justify-between">
                <div>
                    <div class="flex items-center space-x-2">
                        <span class="font-mono text-sm font-black text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200">
                            {{ $v->nomor_lambung }}
                        </span>
                        <span class="text-xs font-bold text-slate-600">
                            {{ $v->nomor_polisi }}
                        </span>
                    </div>
                    <div class="text-sm font-extrabold text-slate-800 mt-1">
                        {{ $v->merk_model }}
                    </div>
                </div>

                @if($v->status_kendaraan === 'tersedia')
                    <span class="text-[11px] font-bold bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded-full">
                        ● Tersedia
                    </span>
                @elseif($v->status_kendaraan === 'digunakan')
                    <span class="text-[11px] font-bold bg-blue-100 text-blue-800 px-2 py-0.5 rounded-full animate-pulse">
                        ● Digunakan
                    </span>
                @else
                    <span class="text-[11px] font-bold bg-rose-100 text-rose-800 px-2 py-0.5 rounded-full">
                        ● Perbaikan
                    </span>
                @endif
            </div>

            <!-- Tags: Jenis & Kepemilikan -->
            <div class="flex flex-wrap gap-1.5 text-[11px]">
                <span class="bg-slate-100 text-slate-700 font-medium px-2 py-0.5 rounded">
                    {{ $v->jenis_kendaraan === 'angkutan_orang' ? '👥 Angkutan Orang' : '📦 Angkutan Barang' }}
                </span>
                @if($v->kepemilikan === 'milik_perusahaan')
                    <span class="bg-blue-50 text-blue-700 font-medium px-2 py-0.5 rounded border border-blue-200">
                        🏢 Milik Sendiri
                    </span>
                @else
                    <span class="bg-purple-50 text-purple-700 font-medium px-2 py-0.5 rounded border border-purple-200" title="{{ $v->nama_vendor_sewa }}">
                        🤝 Sewa ({{ Str::limit($v->nama_vendor_sewa, 15) }})
                    </span>
                @endif
                <span class="bg-emerald-50 text-emerald-700 font-medium px-2 py-0.5 rounded border border-emerald-200">
                    📍 {{ $v->lokasi->kode_lokasi }}
                </span>
            </div>

            <!-- Specs & Monitoring: Odometer & BBM -->
            <div class="bg-slate-50 p-3 rounded-xl space-y-1.5 text-xs">
                <div class="flex justify-between">
                    <span class="text-slate-400">Lokasi Penempatan:</span>
                    <span class="font-medium text-slate-700">{{ $v->lokasi->nama_lokasi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Odometer Terkini:</span>
                    <span class="font-bold text-slate-800">{{ number_format($v->odometer_km) }} KM</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Kapasitas Tangki:</span>
                    <span class="font-medium text-slate-700">{{ $v->kapasitas_tangki }} Liter</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-400">Konsumsi BBM:</span>
                    <span class="font-medium text-slate-700">&plusmn; {{ $v->konsumsi_bbm_per_liter }} KM / Liter</span>
                </div>
            </div>

            <!-- Service Schedule Status -->
            @php
                $today = \Carbon\Carbon::today();
                $nextService = $v->tanggal_servis_berikutnya;
                $isOverdue = $nextService && $nextService->isPast();
                $isNear = $nextService && !$isOverdue && $nextService->diffInDays($today) <= 14;
            @endphp
            <div class="p-2.5 rounded-xl text-xs flex items-center justify-between
                {{ $isOverdue ? 'bg-rose-50 text-rose-800 border border-rose-200' : 
                   ($isNear ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-slate-50 text-slate-600') }}">
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-400">Jadwal Servis Berkala</div>
                    <div class="font-semibold mt-0.5">
                        {{ $nextService ? $nextService->format('d M Y') : 'Belum Dijadwalkan' }}
                    </div>
                </div>
                <div>
                    @if($isOverdue)
                        <span class="text-[10px] font-bold bg-rose-200 text-rose-900 px-2 py-0.5 rounded">
                            ⚠️ Terlewat!
                        </span>
                    @elseif($isNear)
                        <span class="text-[10px] font-bold bg-amber-200 text-amber-900 px-2 py-0.5 rounded">
                            🔧 Segera Servis
                        </span>
                    @else
                        <span class="text-[10px] font-bold bg-slate-200 text-slate-700 px-2 py-0.5 rounded">
                            ✓ Terjadwal
                        </span>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-3 bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
            Tidak ada kendaraan yang sesuai dengan filter.
        </div>
        @endforelse
    </div>

</div>
@endsection

