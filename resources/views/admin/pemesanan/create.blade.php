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
        <form action="{{ route('admin.pemesanan.store') }}" method="POST" class="space-y-6" onsubmit="return validateBookingForm(this)">
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

                @php
                    $selectedLokasi = $lokasi->firstWhere('id', old('lokasi_tujuan_id'));
                    $lokasiLabel = $selectedLokasi 
                        ? '[' . $selectedLokasi->kode_lokasi . '] ' . $selectedLokasi->nama_lokasi . ' (' . ucfirst(str_replace('_', ' ', $selectedLokasi->tipe_lokasi)) . ')' 
                        : '-- Pilih Lokasi Tujuan --';
                @endphp
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Lokasi Tujuan (Pusat / Cabang / Site Tambang) <span class="text-rose-500">*</span></label>
                    <div class="relative" x-data="{ open: false, label: '{{ addslashes($lokasiLabel) }}', value: '{{ old('lokasi_tujuan_id', '') }}' }">
                        <input type="hidden" name="lokasi_tujuan_id" :value="value">
                        <button type="button" @click="open = !open" 
                                class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white text-slate-800 transition outline-none">
                            <span x-text="label" :class="value === '' ? 'text-slate-400' : 'text-slate-800'" class="truncate pr-2"></span>
                            <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                        </button>

                        <!-- Dropdown Menu -->
                        <div x-show="open" @click.away="open = false" x-cloak
                             x-transition:enter="transition ease-out duration-100"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-60 overflow-y-auto">
                            <button type="button" @click="value = ''; label = '-- Pilih Lokasi Tujuan --'; open = false"
                                    class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-500 font-medium flex items-center justify-between">
                                <span>-- Pilih Lokasi Tujuan --</span>
                                <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                            </button>
                            @foreach($lokasi as $loc)
                            @php
                                $locText = '[' . $loc->kode_lokasi . '] ' . $loc->nama_lokasi . ' (' . ucfirst(str_replace('_', ' ', $loc->tipe_lokasi)) . ')';
                            @endphp
                            <button type="button" @click="value = '{{ $loc->id }}'; label = '{{ addslashes($locText) }}'; open = false"
                                    class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                                <span class="truncate">{{ $locText }}</span>
                                <i x-show="value == '{{ $loc->id }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700 shrink-0 ml-2"></i>
                            </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Waktu Berangkat <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="datetime-local" name="tanggal_mulai" value="{{ old('tanggal_mulai', date('Y-m-d\TH:i')) }}" required
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none text-xs sm:text-sm font-medium bg-slate-50 hover:bg-white text-slate-800 transition">
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Estimasi Waktu Kembali <span class="text-rose-500">*</span></label>
                        <div class="relative">
                            <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3.5 text-slate-400">
                                <i data-lucide="calendar" class="w-4 h-4"></i>
                            </div>
                            <input type="datetime-local" name="tanggal_selesai" value="{{ old('tanggal_selesai', date('Y-m-d\TH:i', strtotime('+1 day'))) }}" required
                                   class="w-full pl-10 pr-3.5 py-2.5 rounded-xl border border-slate-200 focus:border-brand-500 focus:ring-2 focus:ring-brand-500/20 outline-none text-xs sm:text-sm font-medium bg-slate-50 hover:bg-white text-slate-800 transition">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section 3: Unit Armada & Driver -->
            <div class="space-y-4 pt-5 border-t border-slate-100">
                <div class="inline-flex items-center space-x-2 text-xs font-bold text-slate-800 uppercase tracking-wider bg-slate-100 px-3 py-1 rounded-md">
                    <span>3. Alokasi Armada & Pengemudi</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @php
                        $selectedKendaraan = $kendaraan->firstWhere('id', old('kendaraan_id'));
                        $kendaraanLabel = $selectedKendaraan 
                            ? $selectedKendaraan->nomor_lambung . ' — ' . $selectedKendaraan->merk_model . ' (' . $selectedKendaraan->nomor_polisi . ')' 
                            : '-- Pilih Kendaraan Tersedia --';
                    @endphp
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Unit Kendaraan (Tersedia) <span class="text-rose-500">*</span></label>
                        <div class="relative" x-data="{ open: false, label: '{{ addslashes($kendaraanLabel) }}', value: '{{ old('kendaraan_id', '') }}' }">
                            <input type="hidden" name="kendaraan_id" :value="value">
                            <button type="button" @click="open = !open" 
                                    class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white text-slate-800 transition outline-none">
                                <span x-text="label" :class="value === '' ? 'text-slate-400' : 'text-slate-800'" class="truncate pr-2"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-60 overflow-y-auto">
                                <button type="button" @click="value = ''; label = '-- Pilih Kendaraan Tersedia --'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-500 font-medium flex items-center justify-between">
                                    <span>-- Pilih Kendaraan Tersedia --</span>
                                    <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                                </button>
                                @foreach($kendaraan as $v)
                                @php
                                    $vText = $v->nomor_lambung . ' — ' . $v->merk_model . ' (' . $v->nomor_polisi . ')';
                                @endphp
                                <button type="button" @click="value = '{{ $v->id }}'; label = '{{ addslashes($vText) }}'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                                    <span class="truncate">{{ $vText }}</span>
                                    <i x-show="value == '{{ $v->id }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700 shrink-0 ml-2"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>
                        <div class="text-[11px] text-slate-400 mt-1">Hanya menampilkan kendaraan yang berstatus Tersedia di pool.</div>
                    </div>

                    @php
                        $selectedDriver = $drivers->firstWhere('id', old('driver_id'));
                        $driverLabel = $selectedDriver 
                            ? $selectedDriver->nama_driver . ' (' . $selectedDriver->jenis_sim . ') — ' . $selectedDriver->nomor_telepon 
                            : '-- Pilih Driver Standby --';
                    @endphp
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Pengemudi (Driver Standby) <span class="text-rose-500">*</span></label>
                        <div class="relative" x-data="{ open: false, label: '{{ addslashes($driverLabel) }}', value: '{{ old('driver_id', '') }}' }">
                            <input type="hidden" name="driver_id" :value="value">
                            <button type="button" @click="open = !open" 
                                    class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-slate-50 hover:bg-white text-slate-800 transition outline-none">
                                <span x-text="label" :class="value === '' ? 'text-slate-400' : 'text-slate-800'" class="truncate pr-2"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-60 overflow-y-auto">
                                <button type="button" @click="value = ''; label = '-- Pilih Driver Standby --'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-500 font-medium flex items-center justify-between">
                                    <span>-- Pilih Driver Standby --</span>
                                    <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                                </button>
                                @foreach($drivers as $d)
                                @php
                                    $dText = $d->nama_driver . ' (' . $d->jenis_sim . ') — ' . $d->nomor_telepon;
                                @endphp
                                <button type="button" @click="value = '{{ $d->id }}'; label = '{{ addslashes($dText) }}'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                                    <span class="truncate">{{ $dText }}</span>
                                    <i x-show="value == '{{ $d->id }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700 shrink-0 ml-2"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>
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
                    @php
                        $initialL1Id = old('penyetuju_level_1_id', 2);
                        $selectedL1 = $approvers->firstWhere('id', $initialL1Id);
                        $l1Label = $selectedL1 ? $selectedL1->nama . ' (' . $selectedL1->jabatan . ')' : '-- Pilih Atasan Level 1 --';
                    @endphp
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                        <label class="block text-xs font-bold text-slate-900 mb-1.5">
                            Penyetuju Level 1 (Atasan Langsung / Spv) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" x-data="{ open: false, label: '{{ addslashes($l1Label) }}', value: '{{ $initialL1Id }}' }">
                            <input type="hidden" name="penyetuju_level_1_id" :value="value">
                            <button type="button" @click="open = !open" 
                                    class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-white text-slate-800 transition outline-none">
                                <span x-text="label" :class="value === '' ? 'text-slate-400' : 'text-slate-800'" class="truncate pr-2"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-60 overflow-y-auto">
                                <button type="button" @click="value = ''; label = '-- Pilih Atasan Level 1 --'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-500 font-medium flex items-center justify-between">
                                    <span>-- Pilih Atasan Level 1 --</span>
                                    <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                                </button>
                                @foreach($approvers as $app)
                                @php $aText = $app->nama . ' (' . $app->jabatan . ')'; @endphp
                                <button type="button" @click="value = '{{ $app->id }}'; label = '{{ addslashes($aText) }}'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                                    <span class="truncate">{{ $aText }}</span>
                                    <i x-show="value == '{{ $app->id }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700 shrink-0 ml-2"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    @php
                        $initialL2Id = old('penyetuju_level_2_id', 3);
                        $selectedL2 = $approvers->firstWhere('id', $initialL2Id);
                        $l2Label = $selectedL2 ? $selectedL2->nama . ' (' . $selectedL2->jabatan . ')' : '-- Pilih Atasan Level 2 --';
                    @endphp
                    <div class="p-4 rounded-xl border border-slate-200 bg-slate-50">
                        <label class="block text-xs font-bold text-slate-900 mb-1.5">
                            Penyetuju Level 2 (Kepala Wilayah / GM) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative" x-data="{ open: false, label: '{{ addslashes($l2Label) }}', value: '{{ $initialL2Id }}' }">
                            <input type="hidden" name="penyetuju_level_2_id" :value="value">
                            <button type="button" @click="open = !open" 
                                    class="w-full flex items-center justify-between py-2.5 px-3.5 text-xs sm:text-sm font-medium rounded-xl border border-slate-200 hover:border-slate-300 bg-white text-slate-800 transition outline-none">
                                <span x-text="label" :class="value === '' ? 'text-slate-400' : 'text-slate-800'" class="truncate pr-2"></span>
                                <i data-lucide="chevron-down" class="w-4 h-4 text-slate-400 transition-transform shrink-0" :class="open ? 'rotate-180' : ''"></i>
                            </button>

                            <!-- Dropdown Menu -->
                            <div x-show="open" @click.away="open = false" x-cloak
                                 x-transition:enter="transition ease-out duration-100"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100"
                                 class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-xl shadow-xl z-50 p-1 space-y-0.5 text-xs max-h-60 overflow-y-auto">
                                <button type="button" @click="value = ''; label = '-- Pilih Atasan Level 2 --'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-500 font-medium flex items-center justify-between">
                                    <span>-- Pilih Atasan Level 2 --</span>
                                    <i x-show="value === ''" data-lucide="check" class="w-3.5 h-3.5 text-slate-700"></i>
                                </button>
                                @foreach($approvers as $app)
                                @php $a2Text = $app->nama . ' (' . $app->jabatan . ')'; @endphp
                                <button type="button" @click="value = '{{ $app->id }}'; label = '{{ addslashes($a2Text) }}'; open = false"
                                        class="w-full text-left px-3 py-2 rounded-lg hover:bg-slate-100 text-slate-700 font-medium flex items-center justify-between">
                                    <span class="truncate">{{ $a2Text }}</span>
                                    <i x-show="value == '{{ $app->id }}'" data-lucide="check" class="w-3.5 h-3.5 text-slate-700 shrink-0 ml-2"></i>
                                </button>
                                @endforeach
                            </div>
                        </div>
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

@push('scripts')
<script>
    function validateBookingForm(form) {
        if (!form.lokasi_tujuan_id.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih Lokasi Tujuan terlebih dahulu.',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
            return false;
        }
        if (!form.kendaraan_id.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih Unit Kendaraan yang tersedia.',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
            return false;
        }
        if (!form.driver_id.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan pilih Pengemudi (Driver Standby).',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
            return false;
        }
        if (!form.penyetuju_level_1_id.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan tentukan Penyetuju Level 1.',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
            return false;
        }
        if (!form.penyetuju_level_2_id.value) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan tentukan Penyetuju Level 2.',
                confirmButtonColor: '#059669',
                customClass: { popup: 'rounded-2xl' }
            });
            return false;
        }
        return true;
    }
</script>
@endpush
