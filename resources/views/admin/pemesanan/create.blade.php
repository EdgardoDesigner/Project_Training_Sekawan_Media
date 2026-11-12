@extends('layouts.app')

@section('title', 'Buat Pemesanan Kendaraan - NikelFleet')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">

    <!-- Header & Back Button -->
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pemesanan.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800 flex items-center space-x-1">
            <span>&larr;</span> <span>Kembali ke Daftar</span>
        </a>
        <div class="text-xs text-slate-400">Langkah 1 dari 1</div>
    </div>

    <div>
        <h1 class="text-xl sm:text-2xl font-black text-slate-800 tracking-tight">Formulir Pemesanan Kendaraan</h1>
        <p class="text-xs sm:text-sm text-slate-500">Tentukan pemakai, kendaraan yang tersedia, driver standby, serta atasan yang wajib menyetujui.</p>
    </div>

    @if($errors->any())
    <div class="bg-rose-50 border border-rose-200 text-rose-700 p-4 rounded-xl text-xs space-y-1">
        <div class="font-bold">Mohon periksa kesalahan input berikut:</div>
        <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Main Form Card -->
    <div class="bg-white rounded-2xl border border-slate-200/80 shadow-sm p-5 sm:p-7">
        <form action="{{ route('admin.pemesanan.store') }}" method="POST" class="space-y-6">
            @csrf

            <!-- Section 1: Informasi Pemohon / Pegawai -->
            <div class="space-y-4">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">
                    1. Data Pegawai Pemohon
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Nama Pegawai Pemakai <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_pemesan" value="{{ old('nama_pemesan') }}" required
                               placeholder="Contoh: Rahmat Subagyo"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Divisi / Departemen <span class="text-rose-500">*</span></label>
                        <input type="text" name="divisi_pemesan" value="{{ old('divisi_pemesan') }}" required
                               placeholder="Contoh: Divisi Geologi & Eksplorasi"
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Keperluan / Urusan Dinas <span class="text-rose-500">*</span></label>
                    <textarea name="keperluan" rows="2" required
                              placeholder="Deskripsikan agenda perjalanan dinas tambang..."
                              class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm">{{ old('keperluan') }}</textarea>
                </div>
            </div>

            <!-- Section 2: Jadwal & Lokasi Tujuan -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">
                    2. Rute & Waktu Pemakaian
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Lokasi Tujuan (Pusat / Cabang / Site Tambang) <span class="text-rose-500">*</span></label>
                    <select name="lokasi_tujuan_id" required
                            class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm bg-white">
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
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal & Waktu Berangkat <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d\TH:i')) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Tanggal & Waktu Kembali (Estimasi) <span class="text-rose-500">*</span></label>
                        <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d\TH:i', strtotime('+1 day'))) }}" required
                               class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm">
                    </div>
                </div>
            </div>

            <!-- Section 3: Alokasi Kendaraan & Driver -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="text-xs font-bold uppercase tracking-wider text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-lg inline-block">
                    3. Alokasi Unit Armada & Driver
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Kendaraan (Hanya Unit Tersedia) <span class="text-rose-500">*</span></label>
                        <select name="kendaraan_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm bg-white">
                            <option value="">-- Pilih Kendaraan Tersedia --</option>
                            @foreach($kendaraan as $v)
                                <option value="{{ $v->id }}" {{ old('kendaraan_id') == $v->id ? 'selected' : '' }}>
                                    {{ $v->nomor_lambung }} - {{ $v->merk_model }} ({{ $v->nomor_polisi }})
                                    [{{ $v->jenis_kendaraan === 'angkutan_orang' ? 'Orang' : 'Barang' }} - {{ $v->kepemilikan === 'milik_perusahaan' ? 'Milik' : 'Sewa' }}]
                                </option>
                            @endforeach
                        </select>
                        <div class="text-[11px] text-slate-400 mt-1">Hanya menampilkan kendaraan yang berstatus Tersedia di pool.</div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Pilih Driver (Pengemudi) <span class="text-rose-500">*</span></label>
                        <select name="driver_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-emerald-500 outline-none text-xs sm:text-sm bg-white">
                            <option value="">-- Pilih Driver Standby --</option>
                            @foreach($drivers as $d)
                                <option value="{{ $d->id }}" {{ old('driver_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->nama_driver }} ({{ $d->jenis_sim }}) - {{ $d->nomor_telepon }}
                                </option>
                            @endforeach
                        </select>
                        <div class="text-[11px] text-slate-400 mt-1">Hanya menampilkan driver yang berstatus Standby.</div>
                    </div>
                </div>
            </div>

            <!-- Section 4: Penunjukan 2 Level Penyetuju (Approval) -->
            <div class="space-y-4 pt-4 border-t border-slate-100">
                <div class="text-xs font-bold uppercase tracking-wider text-amber-700 bg-amber-50 px-3 py-1.5 rounded-lg inline-block">
                    4. Penunjukan Pihak Penyetuju (Berjenjang 2 Level)
                </div>
                <p class="text-xs text-slate-500">
                    Sesuai SOP tambang, pemesanan harus disetujui berjenjang oleh Atasan Level 1 terlebih dahulu, baru kemudian dilanjutkan ke Atasan Level 2.
                </p>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="bg-amber-50/50 p-4 rounded-xl border border-amber-200/60">
                        <label class="block text-xs font-bold text-amber-900 mb-1">
                            Penyetuju Level 1 (Atasan Langsung / Spv) <span class="text-rose-500">*</span>
                        </label>
                        <select name="penyetuju_level_1_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-amber-500 outline-none text-xs sm:text-sm bg-white">
                            <option value="">-- Pilih Atasan Level 1 --</option>
                            @foreach($approvers as $app)
                                <option value="{{ $app->id }}" {{ old('penyetuju_level_1_id', 2) == $app->id ? 'selected' : '' }}>
                                    {{ $app->nama }} ({{ $app->jabatan }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="bg-blue-50/50 p-4 rounded-xl border border-blue-200/60">
                        <label class="block text-xs font-bold text-blue-900 mb-1">
                            Penyetuju Level 2 (Kepala Wilayah / GM) <span class="text-rose-500">*</span>
                        </label>
                        <select name="penyetuju_level_2_id" required
                                class="w-full px-3.5 py-2.5 rounded-xl border border-slate-200 focus:ring-2 focus:ring-blue-500 outline-none text-xs sm:text-sm bg-white">
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

            <!-- Submit Button -->
            <div class="pt-4 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                <a href="{{ route('admin.pemesanan.index') }}" 
                   class="w-full sm:w-auto text-center px-5 py-2.5 text-xs sm:text-sm font-semibold text-slate-600 hover:text-slate-800 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="w-full sm:w-auto px-6 py-3 bg-emerald-600 hover:bg-emerald-700 text-white font-extrabold rounded-xl shadow-lg transition text-xs sm:text-sm">
                    Simpan & Ajukan Persetujuan &rarr;
                </button>
            </div>

        </form>
    </div>

</div>
@endsection

