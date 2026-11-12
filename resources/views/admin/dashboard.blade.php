@extends('layouts.app')

@section('title', 'Dashboard Monitoring — NikelFleet')

@section('content')
<div class="space-y-6">

    <!-- Top Hero Banner: Clean Minimalist Enterprise (High Contrast, No Emojis, No Neon) -->
    <div class="bg-white rounded-2xl border border-slate-200 p-6 shadow-sm flex flex-col md:flex-row md:items-center md:justify-between gap-5">
        <div class="space-y-1.5">
            <div class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-md text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200/80">
                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-500"></i>
                <span>Operasional 8 Lokasi Tambang Nikel</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-extrabold text-slate-900 tracking-tight">
                Pusat Monitoring & Pemesanan Kendaraan
            </h1>
            <p class="text-xs sm:text-sm text-slate-600 max-w-2xl leading-relaxed">
                Selamat bertugas, <strong class="text-slate-900">{{ Auth::user()->nama }}</strong>. Pantau jadwal pemakaian armada, jadwal servis berkala, dan status alur persetujuan berjenjang.
            </p>
        </div>

        <div class="flex-shrink-0 flex items-center space-x-2.5">
            <a href="{{ route('admin.pemesanan.create') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-semibold rounded-xl text-xs sm:text-sm shadow-sm transition active:scale-95">
                <i data-lucide="plus" class="w-4 h-4 stroke-[2.5]"></i>
                <span>Buat Pemesanan</span>
            </a>

            <a href="{{ route('admin.kendaraan.index') }}" 
               class="inline-flex items-center space-x-2 px-4 py-2.5 bg-white hover:bg-slate-50 text-slate-700 font-semibold rounded-xl border border-slate-300 text-xs sm:text-sm shadow-sm transition active:scale-95">
                <i data-lucide="truck" class="w-4 h-4 text-slate-500"></i>
                <span>Daftar Armada</span>
            </a>
        </div>
    </div>

    <!-- 4 Minimalist Stat Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Metric 1: Total Armada -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Total Armada</span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <i data-lucide="truck" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $totalKendaraan }}</div>
                <div class="text-xs text-slate-500 mt-1 flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                    <span>Tersedia & Siap Operasi</span>
                </div>
            </div>
        </div>

        <!-- Metric 2: Kendaraan Sedang Digunakan -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Sedang Bertugas</span>
                <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-700 flex items-center justify-center">
                    <i data-lucide="navigation" class="w-4 h-4 text-blue-600"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $kendaraanAktif }}</div>
                <div class="text-xs text-slate-500 mt-1 flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                    <span>Unit aktif di lapangan</span>
                </div>
            </div>
        </div>

        <!-- Metric 3: Menunggu Approval -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Butuh Approval</span>
                <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center">
                    <i data-lucide="clock" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $pesananMenunggu }}</div>
                <div class="text-xs text-amber-800 font-medium mt-1 flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                    <span>Antrean Level 1 & Level 2</span>
                </div>
            </div>
        </div>

        <!-- Metric 4: Jadwal Servis -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <span class="text-[11px] font-bold text-slate-500 uppercase tracking-wider">Jadwal Servis</span>
                <div class="w-8 h-8 rounded-lg bg-rose-50 text-rose-700 flex items-center justify-center">
                    <i data-lucide="wrench" class="w-4 h-4"></i>
                </div>
            </div>
            <div class="mt-3">
                <div class="text-2xl sm:text-3xl font-bold text-slate-900">{{ $kendaraanPerluServis }}</div>
                <div class="text-xs text-rose-800 font-medium mt-1 flex items-center space-x-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                    <span>Jatuh tempo &le; 14 hari</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Charts Section (Minimalist Corporate Palette: Slate, Charcoal, Subtle Steel) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1: Frekuensi Pemakaian (2 Cols) -->
        <div class="lg:col-span-2 bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                        <i data-lucide="trending-up" class="w-4 h-4 text-emerald-600"></i>
                        <span>Grafik Frekuensi Pemakaian Kendaraan</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Statistik tren pemesanan armada per periode bulanan</p>
                </div>
                <span class="inline-flex items-center space-x-1.5 px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-600"></span>
                    <span>Trip Operasional</span>
                </span>
            </div>

            <div class="relative h-64 w-full">
                <canvas id="usageTrendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Komposisi Armada (1 Col) -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-slate-700"></i>
                    <span>Komposisi Armada</span>
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">Kategori angkutan & jenis kepemilikan</p>
            </div>

            <div class="relative h-44 w-full flex items-center justify-center my-auto">
                <canvas id="fleetDistributionChart"></canvas>
            </div>

            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-xs">
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <div class="text-[10px] text-slate-500 uppercase font-semibold">Angkutan Orang</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $totalOrang }} Unit</div>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <div class="text-[10px] text-slate-500 uppercase font-semibold">Angkutan Barang</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $totalBarang }} Unit</div>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <div class="text-[10px] text-slate-500 uppercase font-semibold">Milik Sendiri</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $totalMilik }} Unit</div>
                </div>
                <div class="bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                    <div class="text-[10px] text-slate-500 uppercase font-semibold">Sewa Rental</div>
                    <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $totalSewa }} Unit</div>
                </div>
            </div>
        </div>

    </div>

    <!-- 2 Column Section: Pemesanan Terbaru & Audit Log -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Pemesanan Terbaru -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-slate-700"></i>
                        <span>Daftar Pemesanan Terbaru</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">5 transaksi pengajuan kendaraan terakhir</p>
                </div>
                <a href="{{ route('admin.pemesanan.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900 flex items-center space-x-1">
                    <span>Lihat Semua</span>
                    <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
                </a>
            </div>

            <!-- List Content -->
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($pemesananTerbaru as $p)
                <div class="p-4 sm:p-5 hover:bg-slate-50 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 tracking-tight">
                                {{ $p->kode_pemesanan }}
                            </span>

                            @if($p->status_pemesanan === 'menunggu_level_1')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-50 text-amber-800 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                    <span>Menunggu Level 1</span>
                                </span>
                            @elseif($p->status_pemesanan === 'menunggu_level_2')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-blue-50 text-blue-800 border border-blue-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>
                                    <span>Menunggu Level 2</span>
                                </span>
                            @elseif($p->status_pemesanan === 'disetujui')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-600"></span>
                                    <span>Disetujui (On Duty)</span>
                                </span>
                            @elseif($p->status_pemesanan === 'ditolak')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-50 text-rose-800 border border-rose-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Ditolak</span>
                                </span>
                            @elseif($p->status_pemesanan === 'selesai')
                                <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700 border border-slate-200">
                                    <i data-lucide="check" class="w-3 h-3 text-slate-600"></i>
                                    <span>Selesai</span>
                                </span>
                            @endif
                        </div>

                        <div class="text-sm font-bold text-slate-900">
                            {{ $p->nama_pemesan }} <span class="text-xs font-normal text-slate-500">({{ $p->divisi_pemesan }})</span>
                        </div>

                        <div class="text-xs text-slate-500 flex flex-wrap gap-x-4 gap-y-1">
                            <span class="flex items-center space-x-1">
                                <i data-lucide="truck" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $p->kendaraan->nomor_lambung }} - {{ $p->kendaraan->merk_model }}</span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $p->lokasiTujuan->nama_lokasi }}</span>
                            </span>
                            <span class="flex items-center space-x-1">
                                <i data-lucide="calendar" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $p->tanggal_mulai->format('d M H:i') }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.pemesanan.show', $p->id) }}" 
                           class="inline-flex items-center space-x-1 px-3 py-1.5 rounded-lg text-xs font-semibold bg-slate-100 hover:bg-slate-200 text-slate-700 transition">
                            <span>Detail</span>
                            <i data-lucide="arrow-right" class="w-3 h-3"></i>
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Belum ada pemesanan kendaraan yang dibuat.
                </div>
                @endforelse
            </div>
        </div>

        <!-- Column 3: Log Aktivitas Terbaru -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                        <i data-lucide="shield-check" class="w-4 h-4 text-slate-700"></i>
                        <span>Audit Log Sistem</span>
                    </h2>
                    <p class="text-xs text-slate-500 mt-0.5">Rekam jejak transaksi terkini</p>
                </div>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-semibold text-slate-700 hover:text-slate-900">
                    Semua &rarr;
                </a>
            </div>

            <div class="p-5 space-y-4 flex-1">
                @forelse($logTerbaru as $log)
                <div class="flex items-start space-x-3 text-xs">
                    <div class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center font-bold flex-shrink-0 mt-0.5 border border-slate-200">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-slate-500"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-slate-800">{{ $log->aksi }}</div>
                        <div class="text-slate-600 mt-0.5 leading-relaxed">{{ $log->deskripsi }}</div>
                        <div class="text-[10px] text-slate-400 mt-1 flex items-center space-x-1.5">
                            <span>{{ $log->pengguna->nama ?? 'Sistem' }}</span>
                            <span>&bull;</span>
                            <span>{{ $log->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-slate-400 text-xs py-6">
                    Belum ada log aktivitas.
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Chart 1: Frekuensi Pemakaian (Modern Smooth Emerald Gradient Area Chart)
        const canvasUsage = document.getElementById('usageTrendChart');
        if (canvasUsage) {
            const ctxUsage = canvasUsage.getContext('2d');
            const gradient = ctxUsage.createLinearGradient(0, 0, 0, 240);
            gradient.addColorStop(0, 'rgba(5, 150, 105, 0.28)');
            gradient.addColorStop(1, 'rgba(5, 150, 105, 0.0)');

            new Chart(ctxUsage, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartBulan) !!},
                    datasets: [
                        {
                            label: 'Trip Operasional',
                            data: {!! json_encode($chartTotalPemakaian) !!},
                            borderColor: '#059669',
                            borderWidth: 3,
                            backgroundColor: gradient,
                            fill: true,
                            tension: 0.38,
                            pointBackgroundColor: '#059669',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 2,
                            pointRadius: 4.5,
                            pointHoverRadius: 7,
                            pointHoverBackgroundColor: '#047857',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 2.5,
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif", weight: 'bold' },
                            bodyFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                            padding: 10,
                            cornerRadius: 10,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return context.parsed.y + ' Trip Pemesanan';
                                }
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: '#f1f5f9' },
                            ticks: {
                                color: '#64748b',
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }
                            }
                        },
                        x: {
                            grid: { display: false },
                            ticks: {
                                color: '#64748b',
                                font: { size: 11, family: "'Plus Jakarta Sans', sans-serif" }
                            }
                        }
                    }
                }
            });
        }

        // Chart 2: Komposisi Armada (Vibrant Emerald & Sky Palette)
        const canvasDist = document.getElementById('fleetDistributionChart');
        if (canvasDist) {
            const ctxDist = canvasDist.getContext('2d');
            new Chart(ctxDist, {
                type: 'doughnut',
                data: {
                    labels: ['Angkutan Orang', 'Angkutan Barang'],
                    datasets: [{
                        data: [{{ $totalOrang }}, {{ $totalBarang }}],
                        backgroundColor: ['#059669', '#0284c7'],
                        hoverBackgroundColor: ['#047857', '#0369a1'],
                        borderWidth: 2,
                        borderColor: '#ffffff',
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '72%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: '#0f172a',
                            titleFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                            bodyFont: { size: 12, family: "'Plus Jakarta Sans', sans-serif" },
                            padding: 10,
                            cornerRadius: 10
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
