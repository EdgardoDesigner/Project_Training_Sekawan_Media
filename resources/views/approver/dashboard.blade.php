@extends('layouts.app')

@section('title', 'Portal Persetujuan — NikelFleet')

@section('content')
<div class="max-w-4xl mx-auto space-y-6" x-data="{ activeTab: 'antrean' }">

    <!-- Hero / Profile Card (Minimalist Dark Slate) -->
    <div class="bg-slate-900 rounded-2xl p-6 text-white border border-slate-800 shadow-sm flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div class="space-y-1">
            <div class="inline-flex items-center space-x-1.5 px-2.5 py-0.5 rounded-md text-[10px] font-semibold bg-slate-800 text-slate-300 border border-slate-700">
                <i data-lucide="shield-check" class="w-3.5 h-3.5 text-brand-500"></i>
                <span>Wewenang Penyetuju Berjenjang</span>
            </div>
            <h1 class="text-xl sm:text-2xl font-black text-white tracking-tight">
                Portal Persetujuan: {{ Auth::user()->nama }}
            </h1>
            <p class="text-xs text-slate-400">
                Jabatan: <strong class="text-slate-200">{{ Auth::user()->jabatan }}</strong> &bull; Peran: Penyetuju Operasional
            </p>
        </div>

        <div class="flex-shrink-0 bg-slate-800/80 px-4 py-3 rounded-xl border border-slate-700 text-center sm:text-right">
            <div class="text-[10px] uppercase font-bold text-slate-400">Menunggu Tindakan Anda</div>
            <div class="text-2xl font-bold text-white tracking-tight">{{ $antreanApproval->count() }} Permohonan</div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="flex border-b border-slate-200 space-x-2">
        <button @click="activeTab = 'antrean'" 
                :class="activeTab === 'antrean' ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="py-3 px-4 border-b-2 text-xs sm:text-sm flex items-center space-x-2 transition">
            <i data-lucide="clock" class="w-4 h-4"></i>
            <span>Antrean Persetujuan ({{ $antreanApproval->count() }})</span>
        </button>

        <button @click="activeTab = 'riwayat'" 
                :class="activeTab === 'riwayat' ? 'border-slate-900 text-slate-900 font-bold' : 'border-transparent text-slate-500 hover:text-slate-800 font-medium'"
                class="py-3 px-4 border-b-2 text-xs sm:text-sm flex items-center space-x-2 transition">
            <i data-lucide="history" class="w-4 h-4"></i>
            <span>Riwayat Tindakan ({{ $riwayatApproval->total() }})</span>
        </button>
    </div>

    <!-- TAB 1: Antrean Persetujuan (Mobile-First Cards) -->
    <div x-show="activeTab === 'antrean'" class="space-y-4">
        @forelse($antreanApproval as $item)
        @php $b = $item->pemesanan; @endphp
        <div class="bg-white rounded-2xl border border-slate-200 p-5 sm:p-6 space-y-4 shadow-sm hover:border-slate-300 transition">
            
            <!-- Top Chip: Code & Level Info -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="text-xs font-bold text-slate-800 bg-slate-100 px-2.5 py-0.5 rounded border border-slate-200 tracking-tight">
                        {{ $b->kode_pemesanan }}
                    </span>
                    <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                        {{ $item->level_persetujuan === 1 ? 'bg-amber-50 text-amber-800 border border-amber-200' : 'bg-blue-50 text-blue-800 border border-blue-200' }}">
                        <span class="w-1.5 h-1.5 rounded-full {{ $item->level_persetujuan === 1 ? 'bg-amber-500' : 'bg-blue-500' }}"></span>
                        <span>Tahap Persetujuan Level {{ $item->level_persetujuan }}</span>
                    </span>
                </div>
                <div class="text-[11px] text-slate-400">
                    Diajukan {{ $b->created_at->diffForHumans() }}
                </div>
            </div>

            <!-- Borrower Info -->
            <div class="space-y-1">
                <div class="text-base font-bold text-slate-900">
                    {{ $b->nama_pemesan }}
                </div>
                <div class="text-xs text-slate-500 flex items-center space-x-1.5">
                    <i data-lucide="briefcase" class="w-3.5 h-3.5 text-slate-400"></i>
                    <span>{{ $b->divisi_pemesan }}</span>
                </div>
                <div class="text-xs text-slate-700 bg-slate-50 p-3 rounded-xl border border-slate-100 mt-2 leading-relaxed">
                    <strong class="text-slate-900 font-semibold">Keperluan:</strong> {{ $b->keperluan }}
                </div>
            </div>

            <!-- Technical Details Grid -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5 text-xs">
                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-200/80 text-slate-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="truck" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold uppercase">Unit Kendaraan</div>
                        <div class="font-bold text-slate-900">{{ $b->kendaraan->nomor_lambung }} — {{ $b->kendaraan->merk_model }}</div>
                        <div class="text-[11px] text-slate-500 font-medium">{{ $b->kendaraan->nomor_polisi }}</div>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-200/80 text-slate-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="user" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold uppercase">Pengemudi (Driver)</div>
                        <div class="font-bold text-slate-900">{{ $b->driver->nama_driver }}</div>
                        <div class="text-[11px] text-slate-500 font-medium">{{ $b->driver->nomor_telepon }}</div>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-200/80 text-slate-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="map-pin" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold uppercase">Lokasi Tujuan</div>
                        <div class="font-bold text-slate-900">{{ $b->lokasiTujuan->nama_lokasi }}</div>
                        <div class="text-[11px] text-slate-500">Kode: {{ $b->lokasiTujuan->kode_lokasi }}</div>
                    </div>
                </div>

                <div class="bg-slate-50 p-3 rounded-xl border border-slate-100 flex items-center space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-slate-200/80 text-slate-700 flex items-center justify-center flex-shrink-0">
                        <i data-lucide="calendar" class="w-4 h-4"></i>
                    </div>
                    <div>
                        <div class="text-[10px] text-slate-400 font-semibold uppercase">Waktu Berangkat — Kembali</div>
                        <div class="font-bold text-slate-900">{{ $b->tanggal_mulai->format('d M H:i') }}</div>
                        <div class="text-[11px] text-slate-500 font-medium">s/d {{ $b->tanggal_selesai->format('d M H:i') }}</div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons: Setujui & Tolak -->
            <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-center justify-end gap-2.5">
                
                <!-- Reject Button -->
                <button type="button" 
                        onclick="showRejectDialog({{ $item->id }}, '{{ $b->kode_pemesanan }}')"
                        class="w-full sm:w-auto px-4 py-2.5 rounded-xl border border-rose-300 text-rose-700 hover:bg-rose-50 font-bold text-xs sm:text-sm flex items-center justify-center space-x-1.5 transition active:scale-95">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    <span>Tolak Permohonan</span>
                </button>

                <!-- Approve Button -->
                <button type="button" 
                        onclick="showApproveDialog({{ $item->id }}, '{{ $b->kode_pemesanan }}', {{ $item->level_persetujuan }})"
                        class="w-full sm:w-auto px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs sm:text-sm flex items-center justify-center space-x-1.5 shadow-sm transition active:scale-95">
                    <i data-lucide="check" class="w-4 h-4"></i>
                    <span>Setujui Permohonan (Level {{ $item->level_persetujuan }})</span>
                </button>
            </div>

        </div>
        @empty
        <div class="bg-white rounded-2xl border border-slate-200 p-12 text-center space-y-2">
            <div class="w-12 h-12 rounded-full bg-slate-100 text-slate-500 mx-auto flex items-center justify-center">
                <i data-lucide="check" class="w-6 h-6"></i>
            </div>
            <h3 class="text-sm font-bold text-slate-900">Semua Permohonan Telah Diproses</h3>
            <p class="text-xs text-slate-500 max-w-sm mx-auto">
                Saat ini belum ada permohonan pemesanan kendaraan yang membutuhkan tindakan persetujuan dari Anda.
            </p>
        </div>
        @endforelse
    </div>

    <!-- TAB 2: Riwayat Tindakan -->
    <div x-show="activeTab === 'riwayat'" class="space-y-4" x-cloak>
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="divide-y divide-slate-100">
                @forelse($riwayatApproval as $hist)
                @php $b = $hist->pemesanan; @endphp
                <div class="p-4 sm:p-5 flex flex-col sm:flex-row sm:items-center justify-between gap-3 hover:bg-slate-50 transition">
                    <div class="space-y-1">
                        <div class="flex items-center space-x-2">
                            <span class="text-xs font-bold text-slate-800 bg-slate-100 px-2 py-0.5 rounded border border-slate-200 tracking-tight">
                                {{ $b->kode_pemesanan }}
                            </span>
                            <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full text-[11px] font-semibold
                                {{ $hist->status_persetujuan === 'disetujui' ? 'bg-emerald-50 text-emerald-800 border border-emerald-200' : 'bg-rose-50 text-rose-800 border border-rose-200' }}">
                                <i data-lucide="{{ $hist->status_persetujuan === 'disetujui' ? 'check' : 'x' }}" class="w-3 h-3"></i>
                                <span>{{ $hist->status_persetujuan === 'disetujui' ? 'Disetujui' : 'Ditolak' }} (Level {{ $hist->level_persetujuan }})</span>
                            </span>
                        </div>
                        <div class="text-sm font-bold text-slate-900">
                            {{ $b->nama_pemesan }} <span class="text-xs font-normal text-slate-500">({{ $b->divisi_pemesan }})</span>
                        </div>
                        <div class="text-xs text-slate-500">
                            Unit: {{ $b->kendaraan->nomor_lambung }} &bull; Tujuan: {{ $b->lokasiTujuan->nama_lokasi }} &bull; Tanggal: {{ $hist->waktu_aksi ? $hist->waktu_aksi->format('d M Y H:i') : '-' }}
                        </div>
                        @if($hist->catatan)
                        <div class="text-xs text-slate-600 italic bg-slate-50 p-2 rounded-lg mt-1 border border-slate-100">
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

<!-- Hidden Action Forms -->
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
    function showApproveDialog(persetujuanId, kodeBooking, level) {
        Swal.fire({
            title: 'Setujui Permohonan?',
            html: `Konfirmasi persetujuan untuk pemesanan <strong>${kodeBooking}</strong> pada <strong>Level ${level}</strong>:`,
            input: 'text',
            inputPlaceholder: 'Tuliskan catatan persetujuan (opsional)...',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#0f172a',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Setujui Permohonan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5',
                cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5'
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const form = document.getElementById('approve-form');
                form.action = `/approver/persetujuan/${persetujuanId}/approve`;
                document.getElementById('approve-notes').value = result.value || '';
                form.submit();
            }
        });
    }

    function showRejectDialog(persetujuanId, kodeBooking) {
        Swal.fire({
            title: 'Tolak Permohonan',
            html: `Masukkan alasan penolakan untuk pemesanan <strong>${kodeBooking}</strong>:`,
            input: 'textarea',
            inputPlaceholder: 'Wajib: Jelaskan mengapa permohonan ini ditolak...',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e11d48',
            cancelButtonColor: '#64748b',
            confirmButtonText: 'Tolak Permohonan',
            cancelButtonText: 'Batal',
            reverseButtons: true,
            inputValidator: (value) => {
                if (!value || value.trim().length < 5) {
                    return 'Alasan penolakan wajib diisi minimal 5 karakter!';
                }
            },
            customClass: {
                popup: 'rounded-2xl',
                confirmButton: 'rounded-xl text-xs font-semibold px-4 py-2.5',
                cancelButton: 'rounded-xl text-xs font-semibold px-4 py-2.5'
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
