@extends('layouts.app')

@section('title', 'Buat Pemesanan Kendaraan — NikelFleet')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Top Navigation Back -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pemesanan.index') }}" class="inline-flex items-center space-x-1.5 text-xs font-semibold text-slate-500 hover:text-slate-900 transition">
            <i data-lucide="arrow-left" class="w-3.5 h-3.5"></i>
            <span>Kembali ke Daftar Pemesanan</span>
        </a>
        <span class="text-xs text-slate-400 font-medium">Form Pengajuan Resmi</span>
    </div>

    <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Formulir Pemesanan Kendaraan</h1>
        <p class="text-xs sm:text-sm text-slate-500 mt-0.5">Tentukan pegawai pemohon, unit armada yang tersedia, driver standby, serta atasan penyetuju berjenjang.</p>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-800 p-4 rounded-xl text-xs space-y-1">
        <div class="font-bold flex items-center space-x-1.5">
            <i data-lucide="alert-circle" class="w-4 h-4 text-rose-600"></i>
            <span>Terdapat data yang perlu diperbaiki:</span>
        </div>
        <ul class="list-disc list-inside space-y-0.5 text-rose-700 pl-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-7 shadow-sm">
        <form action="{{ route('admin.pemesanan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Data Pemohon -->
            <div class="space-y-4">
                <div class="inline-flex items-center space-x-2 text-xs font-bold text-slate-800 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-md">
                    <span>1. Data Pegawai Pemohon</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nama Pegawai Pemakai <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan') }}" required
                               placeholder="Contoh: Rahmat Subagyo"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm transition">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Divisi / Departemen <span class="text-rose-500">*</span></label>
                        <input type="text" name="divisi_pemesan" value="{{ old('divisi_pemesan') }}" required
                               placeholder="Contoh: Divisi Geologi & Eksplorasi"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm transition">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Keperluan / Agenda Perjalanan <span class="text-rose-500">*</span></label>
                    <textarea name="keperluan" rows="2" required
                              placeholder="Deskripsikan agenda kegiatan operasional tambang..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm transition">{{ old('keperluan') }}</textarea>
                </div>
            </div>

            <!-- Section 2: Rute & Jadwal -->
            <div class="space-y-4 pt-5 border-t border-slate-100">
                <div class="inline-flex items-center space-x-2 text-xs font-bold text-slate-800 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-md">
                    <span>2. Lokasi Tujuan & Waktu Pemakaian</span>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lokasi Tujuan (Pusat / Cabang / Site Tambang) <span class="text-rose-500">*</span></label>
                    <select name="lokasi_tujuan_id" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm bg-white">
                        <option value="">-- Pilih Lokasi Tujuan --</option>
                        @foreach($lokasi as $loc)
                            <option value="{{ $loc->id }}" {{ old('lokasi_tujuan_id') == $loc->id ? 'selected' : '' }}>
                                [{{ $loc->kode_lokasi }}] {{ $loc->nama_lokasi }} ({{ ucfirst(str_replace('_', ' ', $loc->tipe_lokasi)) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Berangkat <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d\TH:i')) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm font-mono">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Estimasi Waktu Kembali <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d\TH:i', strtotime('+1 day'))) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm font-mono">
                    </div>
                </div>
            </div>

            <!-- Section 3: Unit Armada & Driver -->
            <div class="space-y-4 pt-5 border-t border-slate-100">
                <div class="inline-flex items-center space-x-2 text-xs font-bold text-slate-800 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-md">
                    <span>3. Alokasi Armada & Pengemudi</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Unit Kendaraan (Tersedia) <span class="text-rose-500">*</span></label>
                        <select name="kendaraan_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm bg-white font-mono">
                            <option value="">-- Pilih Kendaraan Tersedia --</option>
                            @foreach($kendaraan as $v)
                                <option value="{{ $v->id }}" {{ old('kendaraan_id') == $v->id ? 'selected' : '' }}>
                                    {{ $v->nomor_lambung }} — {{ $v->merk_model }} ({{ $v->nomor_polisi }})
                                </option>
                            @endforeach
                        </select>
                        <div class="text-[11px] text-slate-400 mt-1">Hanya menampilkan kendaraan yang berstatus Tersedia di pool.</div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pengemudi (Driver Standby) <span class="text-rose-500">*</span></label>
                        <select name="driver_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm bg-white">
                            <option value="">-- Pilih Driver Standby --</option>
                            @foreach($drivers as $d)
                                <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_driver }} ({{ $d->jenis_sim }}) — {{ $d->nomor_telepon }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-[11px] text-slate-400 mt-1">Hanya menampilkan driver yang berstatus Standby.</div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Penunjukan Pihak Penyetuju -->
            <div class="space-y-4 pt-5 border-t border-slate-100">
                <div class="inline-flex items-center space-x-2 text-xs font-bold text-slate-800 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-md">
                    <span>4. Penunjukan Pihak Penyetuju (Berjenjang 2 Level)</span>
                </div>
                <p class="text-xs text-slate-500">
                    Permohonan akan diproses berurutan: disetujui oleh Penyetuju Level 1 terlebih dahulu, baru kemudian diteruskan ke Penyetuju Level 2.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                        <label class="block text-xs font-bold text-slate-900 mb-1.5">
                            Penyetuju Level 1 (Atasan Langsung / Spv) <span class="text-rose-500">*</span>
                        </label>
                        <select name="penyetuju_level_1_id" required
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm bg-white">
                            <option value="">-- Pilih Atasan Level 1 --</option>
                            @foreach($approvers as $app)
                                <option value="{{ $app->id }}" {{ old('penyetuju_level_1_id', 2) == $app->id ? 'selected' : '' }}>
                                    {{ $app->nama }} ({{ $app->jabatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                        <label class="block text-xs font-bold text-slate-900 mb-1.5">
                            Penyetuju Level 2 (Kepala Wilayah / GM) <span class="text-rose-500">*</span>
                        </label>
                        <select name="penyetuju_level_2_id" required
                                class="w-full px-3 py-2 rounded-lg border border-slate-300 focus:border-slate-800 focus:ring-1 focus:ring-slate-800 outline-none text-xs sm:text-sm bg-white">
                            <option value="">-- Pilih Atasan Level 2 --</option>
                            @foreach($approvers as $app)
                                <option value="{{ $app->id }}" {{ old('penyetuju_level_2_id', 3) == $app->id ? 'selected' : '' }}>
                                    {{ $app->nama }} ({{ $app->jabatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="pt-5 border-t border-slate-100 flex items-center justify-end space-x-3">
                <a href="{{ route('admin.pemesanan.index') }}" 
                   class="px-4 py-2.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-900 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-5 py-2.5 bg-slate-900 hover:bg-slate-800 text-white font-bold rounded-xl text-xs sm:text-sm shadow-sm transition active:scale-95 flex items-center space-x-2">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Simpan & Ajukan Permohonan</span>
                </button>
            </div>

        </form>
    </div>

</div>
@endsection
