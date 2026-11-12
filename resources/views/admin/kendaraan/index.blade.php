@extends('layouts.app')

@section('title', 'Monitoring Armada Kendaraan — NikelFleet')

@section('content')
<div class="space-y-6">

    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Monitoring Armada Kendaraan</h1>
            <p class="text-xs sm:text-sm text-slate-500">Pantau konsumsi BBM, jadwal servis berkala, dan penempatan unit di 8 lokasi tambang</p>
        </div>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-semibold bg-slate-100 text-slate-700 px-3 py-1 rounded-full border border-slate-200">
                {{ $kendaraan->count() }} Total Unit Terdaftar
            </span>
        </div>
    </div>

    <!-- Filter Bar with Alpine.js Custom Dropdowns (100% In-Frame, No OS Glitch) -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <form x-ref="filterForm" method="GET" action="{{ route('admin.kendaraan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            <!-- Dropdown 1: Jenis Armada -->
            @php
                $currentJenisLabel = 'Semua Jenis Armada';
                if(request('jenis') === 'angkutan_orang') $currentJenisLabel = 'Angkutan Orang';
                if(request('jenis') === 'angkutan_barang') $currentJenisLabel = 'Angkutan Barang';
            @endphp
            <div class="relative" x-data="{ open: false, label: '{{ $currentJenisLabel }}', value: '{{ request('jenis') }}' }">
                <input type="hidden" name="jenis" :value="value">
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
                    <button type="button" @click="value = ''; label = 'Semua Jenis Armada'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Semua Jenis Armada</span>
                        <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'angkutan_orang'; label = 'Angkutan Orang'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Angkutan Orang</span>
                        <i x-show="value === 'angkutan_orang'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'angkutan_barang'; label = 'Angkutan Barang'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Angkutan Barang</span>
                        <i x-show="value === 'angkutan_barang'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                </div>
            </div>

            <!-- Dropdown 2: Kepemilikan -->
            @php
                $currentKepemilikanLabel = 'Semua Kepemilikan';
                if(request('kepemilikan') === 'milik_perusahaan') $currentKepemilikanLabel = 'Milik Perusahaan';
                if(request('kepemilikan') === 'sewa') $currentKepemilikanLabel = 'Sewa (Rental)';
            @endphp
            <div class="relative" x-data="{ open: false, label: '{{ $currentKepemilikanLabel }}', value: '{{ request('kepemilikan') }}' }">
                <input type="hidden" name="kepemilikan" :value="value">
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
                    <button type="button" @click="value = ''; label = 'Semua Kepemilikan'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Semua Kepemilikan</span>
                        <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'milik_perusahaan'; label = 'Milik Perusahaan'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Milik Perusahaan</span>
                        <i x-show="value === 'milik_perusahaan'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'sewa'; label = 'Sewa (Rental)'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Sewa (Rental)</span>
                        <i x-show="value === 'sewa'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                </div>
            </div>

            <!-- Dropdown 3: Status Operasi -->
            @php
                $currentStatusLabel = 'Semua Status Operasi';
                if(request('status') === 'tersedia') $currentStatusLabel = 'Tersedia (Ready)';
                if(request('status') === 'digunakan') $currentStatusLabel = 'Sedang Digunakan';
                if(request('status') === 'perbaikan') $currentStatusLabel = 'Dalam Perbaikan';
            @endphp
            <div class="relative" x-data="{ open: false, label: '{{ $currentStatusLabel }}', value: '{{ request('status') }}' }">
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
                    <button type="button" @click="value = ''; label = 'Semua Status Operasi'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Semua Status Operasi</span>
                        <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'tersedia'; label = 'Tersedia (Ready)'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Tersedia (Ready)</span>
                        <i x-show="value === 'tersedia'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'digunakan'; label = 'Sedang Digunakan'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Sedang Digunakan</span>
                        <i x-show="value === 'digunakan'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                    <button type="button" @click="value = 'perbaikan'; label = 'Dalam Perbaikan'; open = false; $refs.filterForm.submit()"
                            class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                        <span>Dalam Perbaikan</span>
                        <i x-show="value === 'perbaikan'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                    </button>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center space-x-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-slate-900 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-slate-800 transition flex items-center justify-center space-x-1.5 shadow-sm">
                    <i data-lucide="filter" class="w-3.5 h-3.5"></i>
                    <span>Terapkan</span>
                </button>
                @if(request()->hasAny(['jenis', 'kepemilikan', 'status']))
                <a href="{{ route('admin.kendaraan.index') }}" class="px-3 py-2 text-slate-500 hover:text-slate-800 text-xs font-semibold whitespace-nowrap">
                    Reset
                </a>
                @endif
            </div>

        </form>
    </div>

    <!-- Vehicle Cards Grid (Clean Minimalist Industrial Design with Real Photos) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5">
        @forelse($kendaraan as $v)
        <div class="bg-white rounded-2xl border border-slate-200 p-4 sm:p-5 space-y-3.5 shadow-sm hover:border-slate-300 hover:shadow-md transition group">
            
            <!-- Vehicle Image with Status Badge Overlay -->
            <div class="relative h-44 w-full overflow-hidden rounded-xl bg-slate-100">
                <img src="{{ $v->foto_url }}" alt="{{ $v->merk_model }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500" loading="lazy">
                <div class="absolute inset-0 bg-gradient-to-t from-slate-950/75 via-slate-950/20 to-transparent"></div>
                
                <!-- Bottom Chips Overlay -->
                <div class="absolute bottom-3 left-3 right-3 flex items-center justify-between gap-2">
                    <span class="text-xs font-bold text-white bg-slate-900/80 backdrop-blur-md px-2.5 py-1 rounded-lg border border-white/10 shrink-0">
                        {{ $v->nomor_lambung }} &bull; {{ $v->nomor_polisi }}
                    </span>

                    @if($v->status_kendaraan === 'tersedia')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shrink-0 whitespace-nowrap bg-emerald-500/90 text-white backdrop-blur-md shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-white shrink-0"></span>
                            <span>Tersedia</span>
                        </span>
                    @elseif($v->status_kendaraan === 'digunakan')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shrink-0 whitespace-nowrap bg-blue-500/90 text-white backdrop-blur-md shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-white shrink-0"></span>
                            <span>Digunakan</span>
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold shrink-0 whitespace-nowrap bg-rose-500/90 text-white backdrop-blur-md shadow-sm">
                            <span class="w-2 h-2 rounded-full bg-white shrink-0"></span>
                            <span>Perbaikan</span>
                        </span>
                    @endif
                </div>
            </div>

            <!-- Title & Model -->
            <div>
                <h3 class="text-base font-bold text-slate-900 group-hover:text-emerald-700 transition">
                    {{ $v->merk_model }}
                </h3>
            </div>

            <!-- Tags -->
            <div class="flex flex-wrap gap-1.5 text-xs">
                <span class="inline-flex items-center space-x-1.5 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg font-medium">
                    <i data-lucide="{{ $v->jenis_kendaraan === 'angkutan_orang' ? 'users' : 'box' }}" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>{{ $v->jenis_kendaraan === 'angkutan_orang' ? 'Angkutan Orang' : 'Angkutan Barang' }}</span>
                </span>
                
                @if($v->kepemilikan === 'milik_perusahaan')
                    <span class="inline-flex items-center space-x-1.5 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg font-medium">
                        <i data-lucide="building-2" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Milik Sendiri</span>
                    </span>
                @else
                    <span class="inline-flex items-center space-x-1.5 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg font-medium" title="{{ $v->nama_vendor_sewa }}">
                        <i data-lucide="handshake" class="w-3.5 h-3.5 text-slate-500"></i>
                        <span>Sewa Rental</span>
                    </span>
                @endif

                <span class="inline-flex items-center space-x-1.5 bg-slate-100 text-slate-700 px-2.5 py-1 rounded-lg font-medium">
                    <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500"></i>
                    <span>{{ $v->lokasi->kode_lokasi }}</span>
                </span>
            </div>

            <!-- Technical Info -->
            <div class="bg-slate-50 p-3 rounded-xl space-y-1.5 text-xs border border-slate-100">
                <div class="flex justify-between">
                    <span class="text-slate-500">Penempatan:</span>
                    <span class="font-medium text-slate-800">{{ $v->lokasi->nama_lokasi }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Odometer Terkini:</span>
                    <span class="font-bold text-slate-900">{{ number_format($v->odometer_km) }} KM</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Kapasitas Tangki:</span>
                    <span class="font-medium text-slate-800">{{ $v->kapasitas_tangki }} Liter</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-slate-500">Rasio Konsumsi BBM:</span>
                    <span class="font-medium text-slate-800">&plusmn; {{ $v->konsumsi_bbm_per_liter }} KM / L</span>
                </div>
            </div>

            <!-- Service Status -->
            @php
                $today = \Carbon\Carbon::today();
                $nextService = $v->tanggal_servis_berikutnya;
                $isOverdue = $nextService && $nextService->isPast();
                $isNear = $nextService && !$isOverdue && $nextService->diffInDays($today) <= 14;
            @endphp
            <div class="p-2.5 rounded-xl text-xs flex items-center justify-between border
                {{ $isOverdue ? 'bg-rose-50 text-rose-800 border-rose-200' : 
                   ($isNear ? 'bg-amber-50 text-amber-800 border-amber-200' : 'bg-slate-50 text-slate-700 border-slate-200') }}">
                <div>
                    <div class="text-[10px] uppercase font-bold text-slate-500">Jadwal Servis Berkala</div>
                    <div class="font-semibold text-slate-800 mt-0.5">
                        {{ $nextService ? $nextService->format('d M Y') : 'Belum Dijadwalkan' }}
                    </div>
                </div>
                <div>
                    @if($isOverdue)
                        <span class="inline-flex items-center space-x-1 text-[11px] font-bold bg-rose-200/80 text-rose-900 px-2 py-0.5 rounded">
                            <i data-lucide="alert-triangle" class="w-3.5 h-3.5"></i>
                            <span>Overdue</span>
                        </span>
                    @elseif($isNear)
                        <span class="inline-flex items-center space-x-1 text-[11px] font-bold bg-amber-200/80 text-amber-900 px-2 py-0.5 rounded">
                            <i data-lucide="wrench" class="w-3.5 h-3.5"></i>
                            <span>Segera Servis</span>
                        </span>
                    @else
                        <span class="text-[11px] font-medium bg-slate-200 text-slate-700 px-2 py-0.5 rounded">
                            Terjadwal
                        </span>
                    @endif
                </div>
            </div>

        </div>
        @empty
        <div class="col-span-3 bg-white p-8 rounded-2xl border border-slate-200 text-center text-slate-400 text-xs">
            Tidak ada kendaraan yang sesuai dengan filter pencarian.
        </div>
        @endforelse
    </div>

</div>
@endsection
