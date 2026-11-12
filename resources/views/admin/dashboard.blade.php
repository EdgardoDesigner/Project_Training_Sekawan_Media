@extends('layouts.app')

@section('title', 'Admin Dashboard - NikelFleet Monitoring')

@section('content')
<div class="space-y-6">

    <!-- Welcome & Quick Action Hero Banner -->
    <div class="relative overflow-hidden bg-gradient-to-br from-mining-950 via-slate-900 to-emerald-950 rounded-3xl p-6 sm:p-8 text-white shadow-2xl border border-slate-800">
        <!-- Background Ambient Glow -->
        <div class="absolute -top-24 -right-24 w-72 h-72 bg-emerald-500/10 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -left-24 w-72 h-72 bg-blue-500/10 rounded-full blur-3xl pointer-events-none"></div>

        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div class="space-y-2">
                <div class="inline-flex items-center space-x-2 bg-emerald-500/15 text-emerald-400 text-xs font-bold px-3 py-1 rounded-full border border-emerald-500/30">
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span>
                    <span>Monitoring Operasional Tambang Nikel</span>
                </div>
                <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                    Halo, {{ Auth::user()->nama }}! 👋
                </h1>
                <p class="text-xs sm:text-sm text-slate-300 max-w-xl leading-relaxed">
                    Kelola pergerakan armada tambang, pantau konsumsi BBM, cek jadwal servis berkala, dan kontrol alur persetujuan berjenjang.
                </p>
            </div>

            <div class="flex-shrink-0 flex flex-wrap gap-3">
                <a href="{{ route('admin.pemesanan.create') }}" 
                   class="inline-flex items-center space-x-2 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-400 hover:to-emerald-500 text-mining-950 font-black px-5 py-3.5 rounded-2xl shadow-xl shadow-emerald-500/25 transition transform active:scale-95 text-xs sm:text-sm">
                    <i data-lucide="calendar-plus" class="w-4 h-4 stroke-[2.5]"></i>
                    <span>+ Buat Pemesanan Kendaraan</span>
                </a>

                <a href="{{ route('admin.kendaraan.index') }}" 
                   class="inline-flex items-center space-x-2 bg-slate-800/80 hover:bg-slate-700/80 text-slate-200 font-bold px-4 py-3.5 rounded-2xl border border-slate-700 transition text-xs sm:text-sm">
                    <i data-lucide="truck" class="w-4 h-4 text-emerald-400"></i>
                    <span>Cek Armada</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Stat Metrics (4 Cards with Preline / Flowbite Styling) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3.5 sm:gap-5">
        
        <!-- Card 1: Total Armada -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Total Kendaraan</span>
                <div class="w-10 h-10 rounded-2xl bg-blue-50 text-blue-600 flex items-center justify-center group-hover:scale-110 transition transform">
                    <i data-lucide="car" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900">{{ $totalKendaraan }}</div>
            <div class="text-[11px] text-emerald-600 font-semibold flex items-center space-x-1 mt-1">
                <i data-lucide="check-circle-2" class="w-3.5 h-3.5"></i>
                <span>Tersedia & Siap Kerja</span>
            </div>
        </div>

        <!-- Card 2: Sedang Digunakan (On Duty) -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Sedang Digunakan</span>
                <div class="w-10 h-10 rounded-2xl bg-emerald-50 text-emerald-600 flex items-center justify-center group-hover:scale-110 transition transform">
                    <i data-lucide="activity" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-emerald-600">{{ $kendaraanAktif }}</div>
            <div class="text-[11px] text-slate-500 font-medium mt-1">
                Unit beroperasi di lapangan
            </div>
        </div>

        <!-- Card 3: Menunggu Approval -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Butuh Approval</span>
                <div class="w-10 h-10 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center group-hover:scale-110 transition transform">
                    <i data-lucide="clock" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-amber-600">{{ $pesananMenunggu }}</div>
            <div class="text-[11px] text-amber-700 font-semibold flex items-center space-x-1 mt-1">
                <i data-lucide="git-pull-request" class="w-3.5 h-3.5"></i>
                <span>Level 1 & Level 2</span>
            </div>
        </div>

        <!-- Card 4: Jadwal Servis Mendekat -->
        <div class="bg-white p-4 sm:p-5 rounded-2xl sm:rounded-3xl border border-slate-200/80 shadow-sm hover:shadow-md transition group">
            <div class="flex items-center justify-between mb-3">
                <span class="text-[11px] font-extrabold uppercase tracking-wider text-slate-400">Jadwal Servis</span>
                <div class="w-10 h-10 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center group-hover:scale-110 transition transform">
                    <i data-lucide="wrench" class="w-5 h-5"></i>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-rose-600">{{ $kendaraanPerluServis }}</div>
            <div class="text-[11px] text-rose-700 font-medium mt-1">
                Jatuh tempo &le; 14 hari
            </div>
        </div>

    </div>

    <!-- Interactive Charts Section (Fulfills Soal 5: Dashboard Grafik Pemakaian Kendaraan) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Chart 1: Tren Pemakaian Kendaraan (Bar Chart with Line Overlay - 2 Cols) -->
        <div class="lg:col-span-2 bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-800 flex items-center space-x-2">
                        <i data-lucide="bar-chart-3" class="w-4 h-4 text-emerald-600"></i>
                        <span>Grafik Frekuensi Pemakaian Kendaraan</span>
                    </h2>
                    <p class="text-xs text-slate-400">Tren intensitas pemesanan armada per periode bulanan</p>
                </div>
                <div class="flex items-center space-x-2 text-[11px] font-bold">
                    <span class="inline-flex items-center space-x-1 bg-emerald-50 text-emerald-700 px-2.5 py-1 rounded-lg border border-emerald-200">
                        <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                        <span>Trip Operasional</span>
                    </span>
                </div>
            </div>

            <!-- Canvas Container -->
            <div class="relative h-64 sm:h-72 w-full">
                <canvas id="usageTrendChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Distribusi Armada & Kepemilikan (Doughnut Chart - 1 Col) -->
        <div class="bg-white p-5 sm:p-6 rounded-3xl border border-slate-200/80 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <h2 class="text-sm sm:text-base font-extrabold text-slate-800 flex items-center space-x-2">
                    <i data-lucide="pie-chart" class="w-4 h-4 text-emerald-600"></i>
                    <span>Kategori & Kepemilikan</span>
                </h2>
                <p class="text-xs text-slate-400">Rasio unit orang vs barang & milik vs sewa</p>
            </div>

            <div class="relative h-48 w-full flex items-center justify-center my-auto">
                <canvas id="fleetDistributionChart"></canvas>
            </div>

            <!-- Legend Info List -->
            <div class="grid grid-cols-2 gap-2 pt-3 border-t border-slate-100 text-[11px]">
                <div class="bg-slate-50 p-2 rounded-xl text-center">
                    <div class="text-slate-400">Angkutan Orang</div>
                    <div class="font-bold text-slate-800 mt-0.5">{{ $totalOrang }} Unit</div>
                </div>
                <div class="bg-slate-50 p-2 rounded-xl text-center">
                    <div class="text-slate-400">Angkutan Barang</div>
                    <div class="font-bold text-slate-800 mt-0.5">{{ $totalBarang }} Unit</div>
                </div>
                <div class="bg-emerald-50 text-emerald-800 p-2 rounded-xl text-center border border-emerald-100">
                    <div class="text-emerald-600">Milik Sendiri</div>
                    <div class="font-bold mt-0.5">{{ $totalMilik }} Unit</div>
                </div>
                <div class="bg-purple-50 text-purple-800 p-2 rounded-xl text-center border border-purple-100">
                    <div class="text-purple-600">Sewa Vendor</div>
                    <div class="font-bold mt-0.5">{{ $totalSewa }} Unit</div>
                </div>
            </div>
        </div>

    </div>

    <!-- 2 Column Layout: Pemesanan Terbaru & Log Aktivitas -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Column 1 & 2: Pemesanan Terbaru (2 cols) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-800 flex items-center space-x-2">
                        <i data-lucide="clipboard-list" class="w-4 h-4 text-emerald-600"></i>
                        <span>Pemesanan Terbaru</span>
                    </h2>
                    <p class="text-xs text-slate-400">Daftar transaksi pengajuan terkini</p>
                </div>
                <a href="{{ route('admin.pemesanan.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700 flex items-center space-x-1 group">
                    <span>Lihat Semua</span>
                    <i data-lucide="arrow-right" class="w-3.5 h-3.5 group-hover:translate-x-0.5 transition transform"></i>
                </a>
            </div>

            <!-- List Cards / Table -->
            <div class="divide-y divide-slate-100 flex-1">
                @forelse($pemesananTerbaru as $p)
                <div class="p-4 sm:p-5 hover:bg-slate-50/70 transition flex flex-col sm:flex-row sm:items-center justify-between gap-3">
                    <div class="space-y-1.5">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200">
                                {{ $p->kode_pemesanan }}
                            </span>

                            @if($p->status_pemesanan === 'menunggu_level_1')
                                <span class="text-[11px] font-bold bg-amber-50 text-amber-700 border border-amber-200 px-2.5 py-0.5 rounded-full inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    <span>Menunggu L1</span>
                                </span>
                            @elseif($p->status_pemesanan === 'menunggu_level_2')
                                <span class="text-[11px] font-bold bg-blue-50 text-blue-700 border border-blue-200 px-2.5 py-0.5 rounded-full inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    <span>Menunggu L2</span>
                                </span>
                            @elseif($p->status_pemesanan === 'disetujui')
                                <span class="text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-0.5 rounded-full inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    <span>Disetujui (Siap Jalan)</span>
                                </span>
                            @elseif($p->status_pemesanan === 'ditolak')
                                <span class="text-[11px] font-bold bg-rose-50 text-rose-700 border border-rose-200 px-2.5 py-0.5 rounded-full inline-flex items-center space-x-1">
                                    <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span>
                                    <span>Ditolak</span>
                                </span>
                            @elseif($p->status_pemesanan === 'selesai')
                                <span class="text-[11px] font-bold bg-slate-100 text-slate-700 px-2.5 py-0.5 rounded-full inline-flex items-center space-x-1">
                                    <span>✓ Selesai</span>
                                </span>
                            @endif
                        </div>

                        <div class="text-sm font-bold text-slate-900">
                            {{ $p->nama_pemesan }} <span class="text-xs font-normal text-slate-500">({{ $p->divisi_pemesan }})</span>
                        </div>

                        <div class="text-xs text-slate-500 flex flex-wrap gap-x-3 gap-y-1">
                            <span class="inline-flex items-center space-x-1">
                                <i data-lucide="truck" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $p->kendaraan->nomor_lambung }} - {{ $p->kendaraan->merk_model }}</span>
                            </span>
                            <span class="inline-flex items-center space-x-1">
                                <i data-lucide="map-pin" class="w-3.5 h-3.5 text-emerald-500"></i>
                                <span>{{ $p->lokasiTujuan->nama_lokasi }}</span>
                            </span>
                            <span class="inline-flex items-center space-x-1">
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-slate-400"></i>
                                <span>{{ $p->tanggal_mulai->format('d M H:i') }}</span>
                            </span>
                        </div>
                    </div>

                    <div class="flex-shrink-0">
                        <a href="{{ route('admin.pemesanan.show', $p->id) }}" 
                           class="w-full sm:w-auto text-center inline-flex items-center justify-center space-x-1.5 text-xs font-bold px-3.5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-800 rounded-xl transition">
                            <span>Detail</span>
                            <i data-lucide="chevron-right" class="w-3.5 h-3.5"></i>
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

        <!-- Column 3: Log Aktivitas Terbaru (1 col) -->
        <div class="bg-white rounded-3xl border border-slate-200/80 shadow-sm overflow-hidden flex flex-col">
            <div class="p-5 sm:p-6 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h2 class="text-sm sm:text-base font-extrabold text-slate-800 flex items-center space-x-2">
                        <i data-lucide="history" class="w-4 h-4 text-emerald-600"></i>
                        <span>Audit Log Sistem</span>
                    </h2>
                    <p class="text-xs text-slate-400">Rekam jejak sistem terkini</p>
                </div>
                <a href="{{ route('admin.logs.index') }}" class="text-xs font-bold text-emerald-600 hover:text-emerald-700">
                    Semua &rarr;
                </a>
            </div>

            <div class="p-5 space-y-4 flex-1">
                @forelse($logTerbaru as $log)
                <div class="flex items-start space-x-3 text-xs">
                    <div class="w-7 h-7 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold flex-shrink-0 mt-0.5">
                        <i data-lucide="activity" class="w-3.5 h-3.5 text-emerald-600"></i>
                    </div>
                    <div class="flex-1">
                        <div class="font-bold text-slate-900">{{ $log->aksi }}</div>
                        <div class="text-slate-600 mt-0.5 leading-relaxed">{{ $log->deskripsi }}</div>
                        <div class="text-[10px] text-slate-400 mt-1 flex items-center space-x-2">
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
        // 1. Inisialisasi Chart 1: Tren Pemakaian Kendaraan
        const ctxUsage = document.getElementById('usageTrendChart').getContext('2d');
        const gradient = ctxUsage.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(16, 185, 129, 0.4)');
        gradient.addColorStop(1, 'rgba(16, 185, 129, 0.0)');

        new Chart(ctxUsage, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartBulan) !!},
                datasets: [
                    {
                        label: 'Frekuensi Pemakaian',
                        data: {!! json_encode($chartTotalPemakaian) !!},
                        backgroundColor: '#10b981',
                        borderRadius: 8,
                        barPercentage: 0.5,
                    },
                    {
                        label: 'Tren Rata-rata',
                        data: {!! json_encode($chartTotalPemakaian) !!},
                        type: 'line',
                        borderColor: '#0f172a',
                        borderWidth: 2,
                        pointBackgroundColor: '#10b981',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        tension: 0.3,
                        fill: false
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        titleFont: { size: 12, weight: 'bold' },
                        bodyFont: { size: 12 },
                        padding: 10,
                        cornerRadius: 10
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: '#f1f5f9' },
                        ticks: { font: { size: 11 } }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { size: 11 } }
                    }
                }
            }
        });

        // 2. Inisialisasi Chart 2: Distribusi Armada
        const ctxDist = document.getElementById('fleetDistributionChart').getContext('2d');
        new Chart(ctxDist, {
            type: 'doughnut',
            data: {
                labels: ['Angkutan Orang', 'Angkutan Barang'],
                datasets: [{
                    data: [{{ $totalOrang }}, {{ $totalBarang }}],
                    backgroundColor: ['#10b981', '#3b82f6'],
                    borderWidth: 3,
                    borderColor: '#ffffff',
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                cutout: '70%',
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 10,
                        cornerRadius: 10
                    }
                }
            }
        });
    });
</script>
@endpush
