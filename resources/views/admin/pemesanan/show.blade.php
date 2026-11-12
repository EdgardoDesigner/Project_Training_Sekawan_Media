@extends('layouts.app')

@section('title', 'Detail Pemesanan ' . $pemesanan->kode_pemesanan . ' — NikelFleet')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Back Button & Top Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <a href="{{ route('admin.pemesanan.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Daftar Pemesanan</span>
        </a>
        <div class="flex items-center space-x-2">
            <span class="text-xs text-slate-400">Kode Booking:</span>
            <span class="font-mono text-xs font-bold bg-slate-100 text-slate-800 px-2.5 py-1 rounded border border-slate-200">
                {{ $pemesanan->kode_pemesanan }}
            </span>
        </div>
    </div>

    <!-- Multi-Level Sequential Stepper Timeline -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 shadow-sm space-y-4">
        <h2 class="text-xs font-bold uppercase tracking-wider text-slate-400">
            Status Persetujuan Berjenjang (Sequential Multi-Level Approval)
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-3.5">
            
            <!-- Step 1: Pengajuan Admin -->
            <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50 space-y-1">
                <div class="flex items-center justify-between text-slate-500 text-[10px] font-bold uppercase tracking-wider">
                    <span>Tahap 1</span>
                    <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-600"></i>
                </div>
                <div class="text-xs font-bold text-slate-900">Diajukan Admin</div>
                <div class="text-[11px] text-slate-500">{{ $pemesanan->admin->nama }}</div>
                <div class="text-[10px] text-slate-400 font-mono">{{ $pemesanan->created_at->format('d/m/Y H:i') }}</div>
            </div>

            <!-- Step 2: Persetujuan Level 1 -->
            @php $l1 = $pemesanan->persetujuanLevel1; @endphp
            <div class="p-3.5 rounded-xl border space-y-1
                {{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 
                   ($l1 && $l1->status_persetujuan === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 'border-amber-200 bg-amber-50/50') }}">
                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider
                    {{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'text-emerald-800' : 
                       ($l1 && $l1->status_persetujuan === 'ditolak' ? 'text-rose-800' : 'text-amber-800') }}">
                    <span>Tahap 2: Level 1</span>
                    <i data-lucide="{{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'check-circle' : ($l1 && $l1->status_persetujuan === 'ditolak' ? 'x-circle' : 'clock') }}" class="w-3.5 h-3.5"></i>
                </div>
                <div class="text-xs font-bold text-slate-900">
                    {{ $l1->penyetuju->nama ?? 'Penyetuju L1' }}
                </div>
                <div class="text-[11px] font-semibold
                    {{ $l1 && $l1->status_persetujuan === 'disetujui' ? 'text-emerald-700' : 
                       ($l1 && $l1->status_persetujuan === 'ditolak' ? 'text-rose-700' : 'text-amber-700') }}">
                    Status: {{ ucfirst($l1->status_persetujuan ?? 'menunggu') }}
                </div>
                @if($l1 && $l1->waktu_aksi)
                    <div class="text-[10px] text-slate-400 font-mono">{{ $l1->waktu_aksi->format('d/m/Y H:i') }}</div>
                @endif
                @if($l1 && $l1->catatan)
                    <div class="text-[10px] text-slate-600 italic bg-white p-1 rounded mt-1 border border-slate-100">"{{ $l1->catatan }}"</div>
                @endif
            </div>

            <!-- Step 3: Persetujuan Level 2 -->
            @php $l2 = $pemesanan->persetujuanLevel2; @endphp
            <div class="p-3.5 rounded-xl border space-y-1
                {{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 
                   ($l2 && $l2->status_persetujuan === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 
                   ($pemesanan->status_pemesanan === 'menunggu_level_2' ? 'border-blue-200 bg-blue-50/50' : 'border-slate-200 bg-slate-50')) }}">
                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider
                    {{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'text-emerald-800' : 
                       ($l2 && $l2->status_persetujuan === 'ditolak' ? 'text-rose-800' : 'text-slate-500') }}">
                    <span>Tahap 3: Level 2</span>
                    <i data-lucide="{{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'check-circle' : ($l2 && $l2->status_persetujuan === 'ditolak' ? 'x-circle' : 'clock') }}" class="w-3.5 h-3.5"></i>
                </div>
                <div class="text-xs font-bold text-slate-900">
                    {{ $l2->penyetuju->nama ?? 'Penyetuju L2' }}
                </div>
                <div class="text-[11px] font-semibold
                    {{ $l2 && $l2->status_persetujuan === 'disetujui' ? 'text-emerald-700' : 
                       ($l2 && $l2->status_persetujuan === 'ditolak' ? 'text-rose-700' : 'text-slate-500') }}">
                    Status: {{ ucfirst($l2->status_persetujuan ?? 'menunggu') }}
                </div>
                @if($l2 && $l2->waktu_aksi)
                    <div class="text-[10px] text-slate-400 font-mono">{{ $l2->waktu_aksi->format('d/m/Y H:i') }}</div>
                @endif
                @if($l2 && $l2->catatan)
                    <div class="text-[10px] text-slate-600 italic bg-white p-1 rounded mt-1 border border-slate-100">"{{ $l2->catatan }}"</div>
                @endif
            </div>

            <!-- Step 4: Status Operasional -->
            <div class="p-3.5 rounded-xl border space-y-1
                {{ $pemesanan->status_pemesanan === 'selesai' ? 'border-slate-300 bg-slate-50' : 
                   ($pemesanan->status_pemesanan === 'disetujui' ? 'border-emerald-200 bg-emerald-50/50' : 
                   ($pemesanan->status_pemesanan === 'ditolak' ? 'border-rose-200 bg-rose-50/50' : 'border-slate-200 bg-slate-50')) }}">
                <div class="flex items-center justify-between text-[10px] font-bold uppercase tracking-wider text-slate-500">
                    <span>Hasil Akhir</span>
                    <i data-lucide="flag" class="w-3.5 h-3.5"></i>
                </div>
                <div class="text-xs font-bold text-slate-900">
                    @if($pemesanan->status_pemesanan === 'disetujui')
                        <span class="text-emerald-700">Siap Operasi (On Duty)</span>
                    @elseif($pemesanan->status_pemesanan === 'selesai')
                        <span class="text-slate-700">Perjalanan Selesai</span>
                    @elseif($pemesanan->status_pemesanan === 'ditolak')
                        <span class="text-rose-700">Permohonan Ditolak</span>
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

    <!-- 2 Column Details -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

        <!-- Detail Pemohon -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-3 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Informasi Pemesanan
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Pegawai Pemohon:</span>
                    <span class="font-bold text-slate-900">{{ $pemesanan->nama_pemesan }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Divisi:</span>
                    <span class="font-medium text-slate-800">{{ $pemesanan->divisi_pemesan }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Lokasi Tujuan:</span>
                    <span class="font-semibold text-slate-900">{{ $pemesanan->lokasiTujuan->nama_lokasi }} ({{ $pemesanan->lokasiTujuan->kode_lokasi }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Waktu Berangkat:</span>
                    <span class="font-medium text-slate-800 font-mono">{{ $pemesanan->tanggal_mulai->format('d M Y H:i') }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Estimasi Kembali:</span>
                    <span class="font-medium text-slate-800 font-mono">{{ $pemesanan->tanggal_selesai->format('d M Y H:i') }}</span>
                </div>
                <div class="pt-2">
                    <span class="text-slate-500 block mb-1">Keperluan Agenda:</span>
                    <p class="text-slate-700 bg-slate-50 p-2.5 rounded-xl border border-slate-100 leading-relaxed">
                        {{ $pemesanan->keperluan }}
                    </p>
                </div>
                @if($pemesanan->alasan_penolakan)
                <div class="pt-2">
                    <span class="text-rose-600 font-bold block mb-1">Alasan Penolakan:</span>
                    <p class="text-rose-800 bg-rose-50 p-2.5 rounded-xl border border-rose-200 leading-relaxed">
                        {{ $pemesanan->alasan_penolakan }}
                    </p>
                </div>
                @endif
            </div>
        </div>

        <!-- Detail Armada & Driver -->
        <div class="bg-white rounded-2xl border border-slate-200 p-5 space-y-3 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-400">
                Alokasi Unit Armada & Pengemudi
            </h3>

            <div class="space-y-2 text-xs">
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Nomor Lambung:</span>
                    <span class="font-bold text-slate-900 font-mono">{{ $pemesanan->kendaraan->nomor_lambung }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Merk & Model:</span>
                    <span class="font-semibold text-slate-800">{{ $pemesanan->kendaraan->merk_model }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Plat Nomor:</span>
                    <span class="font-mono text-slate-700">{{ $pemesanan->kendaraan->nomor_polisi }}</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Kategori / Status:</span>
                    <span class="font-medium text-slate-700">
                        {{ $pemesanan->kendaraan->jenis_kendaraan === 'angkutan_orang' ? 'Angkutan Orang' : 'Angkutan Barang' }} &bull;
                        {{ $pemesanan->kendaraan->kepemilikan === 'milik_perusahaan' ? 'Milik Sendiri' : 'Sewa' }}
                    </span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Odometer Terkini:</span>
                    <span class="font-semibold text-slate-800 font-mono">{{ number_format($pemesanan->kendaraan->odometer_km) }} KM</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Pengemudi (Driver):</span>
                    <span class="font-bold text-slate-900">{{ $pemesanan->driver->nama_driver }} ({{ $pemesanan->driver->jenis_sim }})</span>
                </div>
                <div class="flex justify-between py-1.5 border-b border-slate-100">
                    <span class="text-slate-500">Kontak Driver:</span>
                    <span class="font-mono text-slate-700">{{ $pemesanan->driver->nomor_telepon }}</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Selesaikan Perjalanan & Input BBM Form (Hanya jika disetujui) -->
    @if($pemesanan->status_pemesanan === 'disetujui')
    <div class="bg-white rounded-2xl border border-slate-300 p-5 sm:p-6 space-y-4 shadow-sm">
        <div>
            <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i data-lucide="check-square" class="w-4 h-4 text-slate-700"></i>
                <span>Penyelesaian Tugas Operasional & Pencatatan BBM</span>
            </h3>
            <p class="text-xs text-slate-500 mt-0.5">
                Unit saat ini berstatus digunakan. Setelah perjalanan selesai, masukkan odometer akhir dan pemakaian bahan bakar untuk mengembalikan unit ke status tersedia.
            </p>
        </div>

        <form action="{{ route('admin.pemesanan.selesai', $pemesanan->id) }}" method="POST" class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            @csrf
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Odometer Akhir (KM) <span class="text-rose-500">*</span>
                </label>
                <input type="number" name="odometer_akhir" min="{{ $pemesanan->kendaraan->odometer_km }}" required
                       value="{{ old('odometer_akhir', $pemesanan->kendaraan->odometer_km + 75) }}"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm font-mono">
                <span class="text-[10px] text-slate-400">Awal: {{ number_format($pemesanan->kendaraan->odometer_km) }} KM</span>
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Konsumsi BBM (Liter)
                </label>
                <input type="number" step="0.1" name="jumlah_liter_bbm" min="0" value="{{ old('jumlah_liter_bbm', 15.0) }}"
                       placeholder="Contoh: 15.0"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm font-mono">
            </div>

            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1">
                    Biaya Pengisian BBM (Rp)
                </label>
                <input type="number" name="biaya_bbm" min="0" value="{{ old('biaya_bbm', 225000) }}"
                       placeholder="Contoh: 225000"
                       class="w-full px-3 py-2 rounded-xl border border-slate-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm font-mono">
            </div>

            <div class="sm:col-span-3 text-right pt-2">
                <button type="submit" onclick="return confirm('Apakah Anda yakin perjalanan ini telah selesai?')"
                        class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs sm:text-sm shadow-sm transition active:scale-95">
                    Selesaikan Perjalanan & Catat BBM
                </button>
            </div>
        </form>
    </div>
    @endif

</div>
@endsection
