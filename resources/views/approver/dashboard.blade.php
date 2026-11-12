@extends('layouts.app')

@section('title', 'Portal Persetujuan - NikelFleet')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ activeTab: 'antrean' }">

    <!-- Hero / Profile Card -->
    <div class="bg-gradient-to-br from-slate-900 via-mining-900 to-slate-950 rounded-3xl p-6 sm:p-7 text-white shadow-xl border border-slate-800 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-1.5 bg-emerald-500/20 text-emerald-400 text-[11px] font-bold px-3 py-1 rounded-full border border-emerald-500/30">
                <i data-lucide="shield-check" class="w-3.5 h-3.5"></i>
                <span>Pihak Penyetuju Resmi</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-white">
                Portal Persetujuan: {{ Auth::user()->nama }}
            </h1>
            <p class="text-xs text-slate-300">
                Jabatan: <strong class="text-emerald-400">{{ Auth::user()->jabatan }}</strong> &bull; Peran: Approver Berjenjang
            </p>
        </div>

        <div class="flex-shrink-0 bg-white/10 backdrop-blur-md px-4 py-3 rounded-2xl border border-white/10 text-center sm:text-right">
            <div class="text-[10px] uppercase font-bold text-slate-300">Menunggu Tindakan Anda</div>
            <div class="text-2xl font-black text-emerald-400">{{ $antreanApproval->count() }} Permohonan</div>
        </div>
    </div>

    <!-- Navigation Tabs: Antrean vs Riwayat -->
    <div class="flex border-b border-slate-200 space-x-2">
        <button @click="activeTab = 'antrean'" 
                :class="activeTab === 'antrean' ? 'border-emerald-600 text-emerald-600 font-black' : 'border-transparent text-slate-500 font-semibold hover:text-slate-700'"
                class="py-3 px-4 border-b-2 text-xs sm:text-sm flex items-center space-x-2 transition">
            <i data-lucide="clock" class="w-4 h-4"></i>
            <span>Antrean Persetujuan ({{ $antreanApproval->count() }})</span>
        </button>

        <button @click="activeTab = 'riwayat'" 
                :class="activeTab === 'riwayat' ? 'border-emerald-600 text-emerald-600 font-black' : 'border-transparent text-slate-500 font-semibold hover:text-slate-700'"
                class="py-3 px-4 border-b-2 text-xs sm:text-sm flex items-center space-x-2 transition">
            <i data-lucide="history" class="w-4 h-4"></i>
            <span>Riwayat Tindakan ({{ $riwayatApproval->total() }})</span>
        </button>
    </div>

    <!-- TAB 1: Antrean Persetujuan (Mobile-First Cards) -->
    <div x-show="activeTab === 'antrean'" class="space-y-4">
        @forelse($antreanApproval as $item)
        @php $b = $item->pemesanan; @endphp
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm p-5 sm:p-6 space-y-4 hover:shadow-md transition relative overflow-hidden">
            
            <!-- Top Chip: Level Info & Code -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-1 rounded-lg border border-slate-200">
                        {{ $b->kode_pemesanan }}
                    </span>
                    <span class="text-[11px] font-extrabold px-3 py-1 rounded-full
                        {{ $item->level_persetujuan === 1 ? 'bg-amber-100 text-amber-800' : 'bg-blue-100 text-blue-800' }}">
                        ⚡ Tahap Persetujuan Level {{ $item->level_persetujuan }}
                    </span>
                </div>
                <div class="text-[11px] text-slate-400">
                    Diajukan: {{ $b->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Borrower Info -->
            <div class="space-y-1">
                <div class="text-base font-extrabold text-slate-900">
                    {{ $b->nama_pemesan }}
                </div>
                <div class="text-xs text-slate-500 flex items-center space-x-1">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>{{ $b->divisi_pemesan }}</span>
                </div>
                <div class="text-xs text-slate-700 bg-slate-50 p-3 rounded-2xl border border-slate-100 mt-2 leading-relaxed">
                    <strong class="text-slate-800">Keperluan:</strong> {{ $b->keperluan }}
                </div>
            </div>

            <!-- Details Grid: Vehicle, Driver, Route, Time -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                <div class="bg-slate-50 p-3 rounded-2xl flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="car" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Unit Kendaraan</div>
                        <div class="font-bold text-slate-800">{{ $b->kendaraan->nomor_lambung }} - {{ $b->kendaraan->merk_model }}</div>
                        <div class="text-[11px] text-slate-500">{{ $b->kendaraan->nomor_polisi }} ({{ $b->kendaraan->jenis_kendaraan === 'angkutan_orang' ? 'Orang' : 'Barang' }})</div>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-2xl flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user-check" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Pengemudi (Driver)</div>
                        <div class="font-bold text-slate-800">{{ $b->driver->nama_driver }}</div>
                        <div class="text-[11px] text-slate-500">{{ $b->driver->jenis_sim }} &bull; {{ $b->driver->nomor_telepon }}</div>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-2xl flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Tujuan Lokasi</div>
                        <div class="font-bold text-slate-800">{{ $b->lokasiTujuan->nama_lokasi }}</div>
                        <div class="text-[11px] text-slate-500">{{ $b->lokasiTujuan->kode_lokasi }}</div>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-2xl flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-bold uppercase">Waktu Berangkat - Kembali</div>
                        <div class="font-bold text-slate-800">{{ $b->tanggal_mulai->format('d M H:i') }}</div>
                        <div class="text-[11px] text-slate-500">s/d {{ $b->tanggal_selesai->format('d M H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Approval Multi-level Stepper Status -->
            <div class="pt-2">
                <div class="text-[10px] font-bold uppercase tracking-wider text-slate-400 mb-1.5">
                    Progres Persetujuan 2 Level:
                </div>
                <div class="flex items-center space-x-2 text-xs">
                    @foreach($b->persetujuan as $apr)
                    <div class="flex-1 p-2 rounded-xl border text-[11px]
                        {{ $apr->status_persetujuan === 'disetujui' ? 'border-emerald-300 bg-emerald-50 text-emerald-800 font-bold' : 
                           ($apr->status_persetujuan === 'ditolak' ? 'border-rose-300 bg-rose-50 text-rose-800 font-bold' : 'border-amber-300 bg-amber-50 text-amber-800 font-semibold') }}">
                        <div class="flex items-center justify-between">
                            <span>Level {{ $apr->level_persetujuan }}: {{ $apr->penyetuju->nama }}</span>
                            <span>{{ $apr->status_persetujuan === 'disetujui' ? '✓' : ($apr->status_persetujuan === 'ditolak' ? '✕' : '⏳') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons: Setujui vs Tolak (Big Finger-friendly Touch Targets) -->
            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-3">
                
                <!-- Reject Button (Opens Modal) -->
                <button type="button" 
                        onclick="showRejectDialog({{ $item->id }}, '{{ $b->kode_pemesanan }}')"
                        class="w-full sm:w-auto px-5 py-3 rounded-2xl border-2 border-rose-300 text-rose-700 hover:bg-rose-50 font-bold text-xs sm:text-sm flex items-center justify-center space-x-2 transition active:scale-95">
                    <i data-lucide="x-circle" class="w-4 h-4"></i>
                    <span>Tolak Permohonan</span>
                </button>

                <!-- Approve Button (Opens SweetAlert with Confetti) -->
                <button type="button" 
                        onclick="showApproveDialog({{ $item->id }}, '{{ $b->kode_pemesanan }}', {{ $item->level_persetujuan }})"
                        class="w-full sm:w-auto px-6 py-3 rounded-2xl bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-500 hover:to-emerald-400 text-white font-black text-xs sm:text-sm flex items-center justify-center space-x-2 shadow-lg shadow-emerald-500/25 transition active:scale-95">
                    <i data-lucide="check-circle" class="w-4 h-4 stroke-[2.5]"></i>
                    <span>Setujui Permohonan (Level {{ $item->level_persetujuan }})</span>
                </button>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-3xl border border-slate-200/90 p-12 text-center space-y-3">
            <div class="w-16 h-16 rounded-full bg-emerald-50 text-emerald-600 mx-auto flex items-center justify-center">
                <i data-lucide="check-circle-2" class="w-8 h-8"></i>
            </div>
            <h3 class="text-base font-bold text-slate-800">Semua Beres! Tidak Ada Antrean</h3>
            <p class="text-xs text-slate-400 max-w-sm mx-auto">
                Saat ini belum ada permohonan pemesanan kendaraan yang membutuhkan persetujuan dari Anda.
            </p>
        </div>
        @endforelse
    </div>

    <!-- TAB 2: Riwayat Tindakan Persetujuan -->
    <div x-show="activeTab === 'riwayat'" class="space-y-4" x-cloak>
        <div class="bg-white rounded-3xl border border-slate-200/90 shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($riwayatApproval as $hist)
                @php $b = $hist->pemesanan; @endphp
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50/70 transition">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded-lg border border-slate-200">
                                {{ $b->kode_pemesanan }}
                            </span>
                            <span class="text-[11px] font-bold px-2.5 py-0.5 rounded-full
                                {{ $hist->status_persetujuan === 'disetujui' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                {{ $hist->status_persetujuan === 'disetujui' ? '✓ Anda Setujui' : '✕ Anda Tolak' }} (L{{ $hist->level_persetujuan }})
                            </span>
                        </div>
                        <div class="text-sm font-bold text-slate-900">
                            {{ $b->nama_pemesan }} <span class="text-xs font-normal text-slate-500">({{ $b->divisi_pemesan }})</span>
                        </div>
                        <div class="text-xs text-slate-500">
                            🚗 {{ $b->kendaraan->nomor_lambung }} &bull; 📍 {{ $b->lokasiTujuan->nama_lokasi }} &bull; 📅 {{ $hist->waktu_aksi ? $hist->waktu_aksi->format('d M Y H:i') : '-' }}
                        </div>
                        @if($hist->catatan)
                        <div class="text-xs text-slate-600 italic bg-slate-50 p-2 rounded-xl mt-1">
                            "{{ $hist->catatan }}"
                        </div>
                        @endif
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400 text-xs">
                    Belum ada riwayat persetujuan yang Anda proses.
                </div>
                @endforelse
            </div>

            @if($riwayatApproval->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $riwayatApproval->links() }}
            </div>
            @endif
        </div>
    </div>

</div>

<!-- Hidden Action Forms for POST Submissions -->
<form id="approve-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="catatan" id="approve-notes">
</form>

<form id="reject-form" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="alasan_penolakan" id="reject-reason">
</form>
@endsection

@push('scripts')
<script>
    // Dialog Persetujuan (Approve) dengan Konfeti
    function showApproveDialog(persetujuanId, kodeBooking, level) {
        Swal.fire({
            title: `Setujui Permohonan?`,
            html: `Apakah Anda yakin menyetujui pemesanan <strong>${kodeBooking}</strong> pada <strong>Level ${level}</strong>?`,
            input: 'text',
            inputPlaceholder: 'Tuliskan catatan persetujuan (opsional)...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#059669',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Ya, Setujui Sekarang!',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-2xl text-xs font-bold px-5 py-3',
                cancelButton: 'rounded-2xl text-xs font-bold px-5 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                // Trigger Confetti!
                confetti({
                    particleCount: 80,
                    spread: 60,
                    origin: { y: 0.6 }
                });

                const form = document.getElementById('approve-form');
                form.action = `/approver/persetujuan/${persetujuanId}/approve`;
                document.getElementById('approve-notes').value = result.value || '';
                
                setTimeout(() => {
                    form.submit();
                }, 400);
            }
        });
    }

    // Dialog Penolakan (Reject) dengan Alasan Wajib
    function showRejectDialog(persetujuanId, kodeBooking) {
        Swal.fire({
            title: `Tolak Permohonan`,
            html: `Masukkan alasan penolakan untuk pemesanan <strong>${kodeBooking}</strong>:`,
            input: 'textarea',
            inputPlaceholder: 'Wajib: Jelaskan mengapa permohonan ini ditolak...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#94a3b8',
            confirmButtonText: 'Tolak Permohonan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value || value.trim().length < 5) {
                    return 'Alasan penolakan wajib diisi minimal 5 karakter!';
                }
            },
            customClass: {
                popup: 'rounded-3xl',
                confirmButton: 'rounded-2xl text-xs font-bold px-5 py-3',
                cancelButton: 'rounded-2xl text-xs font-bold px-5 py-3'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('reject-form');
                form.action = `/approver/persetujuan/${persetujuanId}/reject`;
                document.getElementById('reject-reason').value = result.value;
                form.submit();
            }
        });
    }
</script>
@endpush
