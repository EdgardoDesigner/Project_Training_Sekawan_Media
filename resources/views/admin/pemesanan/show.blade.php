@extends('layouts.app')

@section('title', 'Detail Pemesanan ' . $pemesanan->kode_pemesanan . ' - NikelFleet')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button & Status Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('admin.pemesanan.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
            <span>&larr;</span> <span>Kembali ke Daftar Pemesanan</span>
        </a>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-slate-400">Kode Booking:</span>
            <span class="font-mono text-xs font-black bg-slate-200 text-slate-800 px-2 py-0.5 rounded">
                {{ $pemesanan->kode_pemesanan }}
            </span>
        </div>
    </div>

    <!-- Stepper Timeline: Alur Persetujuan 2 Level -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-6">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">
            Alur Persetujuan Berjenjang (Multi-Level Approval Status)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 relative">
            
            <!-- Step 1: Input Admin -->
            <div class="p-3.5 rounded-xl border border-emerald-200 bg-emerald-50/50 space-y-1">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold text-emerald-800 uppercase tracking-wider">Tahap 1</span>
                    <span class="text-xs">✅</span>
                </div>
                <div class="text-xs font-bold text-slate-800">Diajukan Admin</div>
                <div class="text-[11px] text-slate-500">{{ $pemesanan->admin->nama }}</div>
                <div class="text-[10px] text-slate-400">{{ $pemesanan->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <!-- Step 2: Persetujuan Level 1 -->
            @php $l1 = $pemesanan->persetujuanLevel1; @endphp
            <div class="p-3.5 rounded-xl border space-y-1
                {{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 
                   ($l1 && $l1->status_persetujuan === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 'border-amber-200 bg-amber-50/50') }}">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider
                        {{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'text-emerald-800' : 
                           ($l1 && $l1->status_persetujuan === 'ditolak' ? 'text-rose-800' : 'text-amber-800') }}">
                        Tahap 2: Level 1
                    </span>
                    <span class="text-xs">
                        {{ $l1 && $l1->status_persetujuan === 'disetujui' ? '✅' : ($l1 && $l1->status_persetujuan === 'ditolak' ? '❌' : '⏳') }}
                    </span>
                </div>
                <div class="text-xs font-bold text-slate-800">
                    {{ $l1->penyetuju->nama ?? 'Approver L1' }}
                </div>
                <div class="text-[11px] font-semibold
                    {{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'text-emerald-700' : 
                       ($l1 && $l1->status_persetujuan === 'ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                    Status: {{ ucfirst($l1->status_persetujuan ?? 'menunggu') }}
                </div>
                @if($l1 && $l1->waktu_aksi)
                    <div class="text-[10px] text-slate-400">{{ $l1->waktu_aksi->format('d/m/Y H:i') }}</div>
                @endif
                @if($l1 && $l1->catatan)
                    <div class="text-[10px] text-slate-600 italic bg-white/80 p-1 rounded mt-1">"{{ $l1->catatan }}"</div>
                @endif
            </div>

            <!-- Step 3: Persetujuan Level 2 -->
            @php $l2 = $pemesanan->persetujuanLevel2; @endphp
            <div class="p-3.5 rounded-xl border space-y-1
                {{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 
                   ($l2 && $l2->status_persetujuan === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 
                   ($pemesanan->status_pemesanan === 'menunggu_level_2' ? 'border-blue-200 bg-blue-50/50' : 'border-slate-200 bg-slate-50')) }}">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider
                        {{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'text-emerald-800' : 
                           ($l2 && $l2->status_persetujuan === 'ditolak' ? 'text-rose-800' : 'text-slate-600') }}">
                        Tahap 3: Level 2
                    </span>
                    <span class="text-xs">
                        {{ $l2 && $l2->status_persetujuan === 'disetujui' ? '✅' : ($l2 && $l2->status_persetujuan === 'ditolak' ? '❌' : '⏳') }}
                    </span>
                </div>
                <div class="text-xs font-bold text-slate-800">
                    {{ $l2->penyetuju->nama ?? 'Approver L2' }}
                </div>
                <div class="text-[11px] font-semibold
                    {{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'text-emerald-700' : 
                       ($l2 && $l2->status_persetujuan === 'ditolak' ? 'text-rose-700' : 'text-slate-500') }}">
                    Status: {{ ucfirst($l2->status_persetujuan ?? 'menunggu') }}
                </div>
                @if($l2 && $l2->waktu_aksi)
                    <div class="text-[10px] text-slate-400">{{ $l2->waktu_aksi->format('d/m/Y H:i') }}</div>
                @endif
                @if($l2 && $l2->catatan)
                    <div class="text-[10px] text-slate-600 italic bg-white/80 p-1 rounded mt-1">"{{ $l2->catatan }}"</div>
                @endif
            </div>

            <!-- Step 4: Status Akhir Operasional -->
            <div class="p-3.5 rounded-xl border space-y-1
                {{ $pemesanan->status_pemesanan === 'selesai' ? 'border-emerald-200 bg-emerald-50/50' : 
                   ($pemesanan->status_pemesanan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/30' : 
                   ($pemesanan->status_pemesanan === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 'border-slate-200 bg-slate-50')) }}">
                <div class="flex items-center justify-between">
                    <span class="text-[10px] font-bold uppercase tracking-wider text-slate-600">
                        Hasil Akhir
                    </span>
                    <span class="text-xs">🏁</span>
                </div>
                <div class="text-xs font-bold text-slate-800">
                    @if($pemesanan->status_pemesanan === 'disetujui')
                        <span class="text-emerald-600">Siap Operasional (On Duty)</span>
                    @elseif($pemesanan->status_pemesanan === 'selesai')
                        <span class="text-emerald-700">Perjalanan Selesai</span>
                    @elseif($pemesanan->status_pemesanan === 'ditolak')
                        <span class="text-rose-600">Permohonan Ditolak</span>
                    @else
                        <span class="text-slate-500">Menunggu Approval</span>
                    @endif
                </div>
                <div class="text-[10px] text-slate-500">
                    {{ $pemesanan->kendaraan->nomor_lambung }} &bull; {{ $pemesanan->driver->nama_driver }}
                </div>
            </div>

        </div>
    </div>

    <!-- 2 Column Details: Booking Info & Vehicle/Driver Info -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Column 1: Info Pemesan & Jadwal -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Informasi Pemesanan
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Pegawai Pemohon:</span>
                    <span class="font-bold text-slate-800">{{ $pemesanan->nama_pemesan }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Divisi:</span>
                    <span class="font-medium text-slate-700">{{ $pemesanan->divisi_pemesan }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Lokasi Tujuan:</span>
                    <span class="font-bold text-emerald-700">{{ $pemesanan->lokasiTujuan->nama_lokasi }} ({{ $pemesanan->lokasiTujuan->kode_lokasi }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Waktu Berangkat:</span>
                    <span class="font-semibold text-slate-800">{{ $pemesanan->tanggal_mulai->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Estimasi Kembali:</span>
                    <span class="font-semibold text-slate-800">{{ $pemesanan->tanggal_selesai->format('d M Y H:i') }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-slate-400 block mb-1">Keperluan Dinas:</span>
                    <p class="text-slate-700 bg-slate-50 p-2.5 rounded-xl leading-relaxed">
                        {{ $pemesanan->keperluan }}
                    </p>
                </div>
                @if($pemesanan->alasan_penolakan)
                <div class="pt-2">
                    <span class="text-rose-500 font-bold block mb-1">Alasan Penolakan:</span>
                    <p class="text-rose-700 bg-rose-50 p-2.5 rounded-xl border border-rose-200 leading-relaxed">
                        {{ $pemesanan->alasan_penolakan }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Column 2: Info Unit Kendaraan & Driver -->
        <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Alokasi Armada & Driver
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Nomor Lambung:</span>
                    <span class="font-bold text-slate-800 font-mono">{{ $pemesanan->kendaraan->nomor_lambung }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Merk & Model:</span>
                    <span class="font-semibold text-slate-700">{{ $pemesanan->kendaraan->merk_model }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Plat Nomor:</span>
                    <span class="font-semibold text-slate-700">{{ $pemesanan->kendaraan->nomor_polisi }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Kategori / Kepemilikan:</span>
                    <span class="font-medium text-slate-700">
                        {{ $pemesanan->kendaraan->jenis_kendaraan === 'angkutan_orang' ? 'Angkutan Orang' : 'Angkutan Barang' }} &bull;
                        {{ $pemesanan->kendaraan->kepemilikan === 'milik_perusahaan' ? 'Milik Sendiri' : 'Sewa (' . $pemesanan->kendaraan->nama_vendor_sewa . ')' }}
                    </span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Odometer Saat Ini:</span>
                    <span class="font-semibold text-slate-800">{{ number_format($pemesanan->kendaraan->odometer_km) }} KM</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Nama Pengemudi:</span>
                    <span class="font-bold text-slate-800">{{ $pemesanan->driver->nama_driver }} ({{ $pemesanan->driver->jenis_sim }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-400">Kontak Driver:</span>
                    <span class="font-medium text-slate-700">{{ $pemesanan->driver->nomor_telepon }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Action Section for Admin: Selesaikan Perjalanan & Input BBM (Jika Disetujui) -->
    @if($pemesanan->status_pemesanan === 'disetujui')
    <div class="bg-gradient-to-r from-emerald-50 to-teal-50 rounded-2xl border border-emerald-200 p-5 sm:p-6 space-y-4">
        <div>
            <h3 class="text-sm font-bold text-emerald-900">Penyelesaian Tugas Kendaraan & Pencatatan BBM</h3>
            <p class="text-xs text-emerald-700">
                Unit sedang beroperasi. Jika tugas telah selesai, silakan input odometer akhir dan pemakaian BBM untuk mengembalikan unit & driver ke status standby.
            </p>
        </div>

        <form action="{{ route('admin.pemesanan.selesai', $pemesanan->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Odometer Akhir (KM) <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="odometer_akhir" min="{{ $pemesanan->kendaraan->odometer_km }}" required
                       value="{{ old('odometer_akhir', $pemesanan->kendaraan->odometer_km + 85) }}"
                       class="w-full px-3 py-2 rounded-xl border border-emerald-300 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm bg-white">
                <span class="text-[10px] text-slate-500">Odo awal: {{ number_format($pemesanan->kendaraan->odometer_km) }} KM</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Pemakaian BBM (Liter)
                </label>
                <input type="number" step="0.1" name="jumlah_liter_bbm" min="0" value="{{ old('jumlah_liter_bbm', 15.0) }}"
                       placeholder="Contoh: 15.5"
                       class="w-full px-3 py-2 rounded-xl border border-emerald-300 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm bg-white">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Biaya BBM (Rp)
                </label>
                <input type="number" name="biaya_bbm" min="0" value="{{ old('biaya_bbm', 225000) }}"
                       placeholder="Contoh: 200000"
                       class="w-full px-3 py-2 rounded-xl border border-emerald-300 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm bg-white">
            </div>

            <div class="sm:col-span-3 text-right pt-2">
                <button type="submit" onclick="return confirm('Apakah Anda yakin perjalanan ini telah selesai? Unit dan Driver akan dikembalikan ke status Tersedia.')"
                        class="px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl shadow transition text-xs sm:text-sm">
                    ✓ Selesaikan Perjalanan & Catat BBM
                </button>
            </div>
        </form>
    </div>
    @endif

    <!-- Catatan Log BBM jika sudah ada -->
    @if($pemesanan->logBbm->isNotEmpty())
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 space-y-3">
        <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
            Riwayat Konsumsi BBM Pada Pemesanan Ini
        </h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-slate-600">
                    <tr>
                        <th class="p-2.5">Tanggal</th>
                        <th class="p-2.5">Liter BBM</th>
                        <th class="p-2.5">Total Biaya</th>
                        <th class="p-2.5">Odometer Awal & Akhir</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($pemesanan->logBbm as $bbm)
                    <tr>
                        <td class="p-2.5">{{ $bbm->tanggal_isi->format('d M Y') }}</td>
                        <td class="p-2.5 font-bold text-slate-800">{{ $bbm->jumlah_liter }} Liter</td>
                        <td class="p-2.5 text-slate-700">Rp {{ number_format($bbm->total_biaya, 0, ',', '.') }}</td>
                        <td class="p-2.5 text-slate-600">{{ number_format($bbm->odometer_awal) }} KM &rarr; {{ number_format($bbm->odometer_akhir) }} KM</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

</div>
@endsection

