<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\PersetujuanPemesanan;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ApproverController extends Controller
{
    /**
     * Dashboard & Antrean Persetujuan untuk Pihak Penyetuju
     */
    public function dashboard(Request $request)
    {
        $userId = Auth::id();

        // Cari permohonan persetujuan yang ditujukan kepada user ini dan berstatus 'menunggu'
        // Syarat:
        // - Jika dia Level 1: pemesanan harus berstatus 'menunggu_level_1'
        // - Jika dia Level 2: pemesanan harus berstatus 'menunggu_level_2'
        $antreanApproval = PersetujuanPemesanan::with([
            'pemesanan.kendaraan',
            'pemesanan.driver',
            'pemesanan.lokasiTujuan',
            'pemesanan.admin',
            'pemesanan.persetujuan.penyetuju'
        ])
        ->where('penyetuju_id', $userId)
        ->where('status_persetujuan', 'menunggu')
        ->whereHas('pemesanan', function ($q) {
            $q->where(function ($sub) {
                // Untuk Level 1
                $sub->where('level_persetujuan', 1)
                    ->where('status_pemesanan', 'menunggu_level_1');
            })->orWhere(function ($sub) {
                // Untuk Level 2
                $sub->where('level_persetujuan', 2)
                    ->where('status_pemesanan', 'menunggu_level_2');
            });
        })
        ->latest()
        ->get();

        // Riwayat yang sudah pernah diproses oleh penyetuju ini
        $riwayatApproval = PersetujuanPemesanan::with([
            'pemesanan.kendaraan',
            'pemesanan.driver',
            'pemesanan.lokasiTujuan',
        ])
        ->where('penyetuju_id', $userId)
        ->whereIn('status_persetujuan', ['disetujui', 'ditolak'])
        ->latest('waktu_aksi')
        ->paginate(10);

        return view('approver.dashboard', compact('antreanApproval', 'riwayatApproval'));
    }

    /**
     * Aksi Menyetujui (Approve)
     */
    public function approve(Request $request, $id)
    {
        $persetujuan = PersetujuanPemesanan::with(['pemesanan.kendaraan', 'pemesanan.driver'])
            ->where('penyetuju_id', Auth::id())
            ->findOrFail($id);

        $pemesanan = $persetujuan->pemesanan;

        // Validasi Alur Berjenjang (Anti-Bypass)
        if ($persetujuan->level_persetujuan === 1 && $pemesanan->status_pemesanan !== 'menunggu_level_1') {
            return back()->with('error', 'Permohonan ini tidak sedang dalam tahap persetujuan Level 1.');
        }

        if ($persetujuan->level_persetujuan === 2 && $pemesanan->status_pemesanan !== 'menunggu_level_2') {
            return back()->with('error', 'Permohonan ini belum disetujui di Level 1 atau sudah diproses.');
        }

        DB::beginTransaction();
        try {
            // Update baris persetujuan
            $persetujuan->update([
                'status_persetujuan' => 'disetujui',
                'catatan' => $request->catatan,
                'waktu_aksi' => Carbon::now(),
            ]);

            // Jika Level 1 yang menyetujui -> naikkan status pemesanan ke Level 2
            if ($persetujuan->level_persetujuan === 1) {
                $pemesanan->update([
                    'status_pemesanan' => 'menunggu_level_2',
                ]);

                LogAktivitas::catat(
                    'SETUJUI_LEVEL_1',
                    "Atasan Level 1 ({$persetujuan->penyetuju->nama}) MENYETUJUI pemesanan {$pemesanan->kode_pemesanan}. Diteruskan ke Level 2."
                );

                $pesanSukses = "Pemesanan {$pemesanan->kode_pemesanan} berhasil disetujui di Level 1 dan diteruskan ke Level 2.";
            } 
            // Jika Level 2 yang menyetujui -> pemesanan disetujui penuh & armada/driver On Duty
            else {
                $pemesanan->update([
                    'status_pemesanan' => 'disetujui',
                ]);

                // Update status kendaraan dan driver jadi bertugas
                $pemesanan->kendaraan->update(['status_kendaraan' => 'digunakan']);
                $pemesanan->driver->update(['status_driver' => 'bertugas']);

                LogAktivitas::catat(
                    'SETUJUI_LEVEL_2',
                    "Atasan Level 2 ({$persetujuan->penyetuju->nama}) MENYETUJUI pemesanan {$pemesanan->kode_pemesanan}. Status pemesanan kini DISETUJUI PENUH & armada jalan."
                );

                $pesanSukses = "Pemesanan {$pemesanan->kode_pemesanan} berhasil disetujui penuh! Kendaraan & driver resmi dijadwalkan bertugas.";
            }

            DB::commit();

            return back()->with('success', $pesanSukses);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses persetujuan: ' . $e->getMessage());
        }
    }

    /**
     * Aksi Menolak (Reject)
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'alasan_penolakan' => ['required', 'string', 'min:5'],
        ], [
            'alasan_penolakan.required' => 'Wajib mengisi alasan penolakan permohonan.',
            'alasan_penolakan.min' => 'Alasan penolakan minimal 5 karakter.',
        ]);

        $persetujuan = PersetujuanPemesanan::with(['pemesanan.kendaraan', 'pemesanan.driver'])
            ->where('penyetuju_id', Auth::id())
            ->findOrFail($id);

        $pemesanan = $persetujuan->pemesanan;

        DB::beginTransaction();
        try {
            // Update baris persetujuan
            $persetujuan->update([
                'status_persetujuan' => 'ditolak',
                'catatan' => $request->alasan_penolakan,
                'waktu_aksi' => Carbon::now(),
            ]);

            // Status pemesanan langsung DITOLAK
            $pemesanan->update([
                'status_pemesanan' => 'ditolak',
                'alasan_penolakan' => "[Level {$persetujuan->level_persetujuan} - {$persetujuan->penyetuju->nama}]: {$request->alasan_penolakan}",
            ]);

            // Pastikan kendaraan & driver tetap/kembali tersedia & standby
            $pemesanan->kendaraan->update(['status_kendaraan' => 'tersedia']);
            $pemesanan->driver->update(['status_driver' => 'standby']);

            LogAktivitas::catat(
                'TOLAK_PEMESANAN',
                "Penyetuju Level {$persetujuan->level_persetujuan} ({$persetujuan->penyetuju->nama}) MENOLAK pemesanan {$pemesanan->kode_pemesanan}. Alasan: {$request->alasan_penolakan}"
            );

            DB::commit();

            return back()->with('success', "Permohonan {$pemesanan->kode_pemesanan} telah berhasil ditolak.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menolak permohonan: ' . $e->getMessage());
        }
    }
}
