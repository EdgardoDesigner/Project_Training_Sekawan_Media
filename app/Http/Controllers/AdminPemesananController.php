<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\PersetujuanPemesanan;
use App\Models\Kendaraan;
use App\Models\Driver;
use App\Models\Lokasi;
use App\Models\User;
use App\Models\LogAktivitas;
use App\Models\LogBbm;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AdminPemesananController extends Controller
{
    public function index(Request $request)
    {
        $query = Pemesanan::with(['kendaraan', 'driver', 'lokasiTujuan', 'admin', 'persetujuan.penyetuju']);

        if ($request->filled('status')) {
            $query->where('status_pemesanan', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('kode_pemesanan', 'like', "%{$search}%")
                  ->orWhere('nama_pemesan', 'like', "%{$search}%")
                  ->orWhere('divisi_pemesan', 'like', "%{$search}%");
            });
        }

        $pemesanan = $query->latest()->paginate(10)->withQueryString();

        return view('admin.pemesanan.index', compact('pemesanan'));
    }

    public function create()
    {
        $kendaraan = Kendaraan::where('status_kendaraan', 'tersedia')->get();
        $drivers = Driver::where('status_driver', 'standby')->get();
        $lokasi = Lokasi::all();
        $approvers = User::where('peran', 'penyetuju')->get();

        return view('admin.pemesanan.create', compact('kendaraan', 'drivers', 'lokasi', 'approvers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pemesan' => ['required', 'string', 'max:150'],
            'divisi_pemesan' => ['required', 'string', 'max:100'],
            'keperluan' => ['required', 'string'],
            'kendaraan_id' => ['required', 'exists:kendaraan,id'],
            'driver_id' => ['required', 'exists:drivers,id'],
            'lokasi_tujuan_id' => ['required', 'exists:lokasi,id'],
            'tanggal_mulai' => ['required', 'date'],
            'tanggal_selesai' => ['required', 'date', 'after_or_equal:tanggal_mulai'],
            'penyetuju_level_1_id' => ['required', 'exists:users,id'],
            'penyetuju_level_2_id' => ['required', 'exists:users,id', 'different:penyetuju_level_1_id'],
        ], [
            'penyetuju_level_2_id.different' => 'Pihak penyetuju Level 2 harus berbeda dari pihak penyetuju Level 1.',
            'tanggal_selesai.after_or_equal' => 'Tanggal kembali harus sama atau setelah tanggal berangkat.',
        ]);

        // Cek bentrok jadwal (Anti-Double Booking) untuk kendaraan
        $bentrokKendaraan = Pemesanan::where('kendaraan_id', $validated['kendaraan_id'])
            ->whereIn('status_pemesanan', ['menunggu_level_1', 'menunggu_level_2', 'disetujui'])
            ->where(function ($q) use ($validated) {
                $q->whereBetween('tanggal_mulai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                  ->orWhereBetween('tanggal_selesai', [$validated['tanggal_mulai'], $validated['tanggal_selesai']])
                  ->orWhere(function ($sub) use ($validated) {
                      $sub->where('tanggal_mulai', '<=', $validated['tanggal_mulai'])
                          ->where('tanggal_selesai', '>=', $validated['tanggal_selesai']);
                  });
            })->exists();

        if ($bentrokKendaraan) {
            return back()->withInput()->withErrors([
                'kendaraan_id' => 'Kendaraan yang dipilih sudah terjadwal atau dipesan pada rentang tanggal tersebut.',
            ]);
        }

        DB::beginTransaction();
        try {
            // Generate Kode Pemesanan Unik: BKG-YYYYMM-XXXX
            $prefix = 'BKG-' . Carbon::now()->format('Ym') . '-';
            $lastBooking = Pemesanan::where('kode_pemesanan', 'like', "{$prefix}%")
                ->latest('id')
                ->first();

            $nextNumber = 1;
            if ($lastBooking) {
                $lastNumber = (int) substr($lastBooking->kode_pemesanan, -4);
                $nextNumber = $lastNumber + 1;
            }
            $kodePemesanan = $prefix . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

            // Simpan Pemesanan
            $pemesanan = Pemesanan::create([
                'kode_pemesanan' => $kodePemesanan,
                'admin_id' => Auth::id(),
                'nama_pemesan' => $validated['nama_pemesan'],
                'divisi_pemesan' => $validated['divisi_pemesan'],
                'keperluan' => $validated['keperluan'],
                'kendaraan_id' => $validated['kendaraan_id'],
                'driver_id' => $validated['driver_id'],
                'lokasi_tujuan_id' => $validated['lokasi_tujuan_id'],
                'tanggal_mulai' => $validated['tanggal_mulai'],
                'tanggal_selesai' => $validated['tanggal_selesai'],
                'status_pemesanan' => 'menunggu_level_1',
            ]);

            // Buat 2 Baris Persetujuan Berjenjang
            PersetujuanPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'penyetuju_id' => $validated['penyetuju_level_1_id'],
                'level_persetujuan' => 1,
                'status_persetujuan' => 'menunggu',
            ]);

            PersetujuanPemesanan::create([
                'pemesanan_id' => $pemesanan->id,
                'penyetuju_id' => $validated['penyetuju_level_2_id'],
                'level_persetujuan' => 2,
                'status_persetujuan' => 'menunggu',
            ]);

            LogAktivitas::catat(
                'BUAT_PEMESANAN',
                "Admin membuat pemesanan baru {$kodePemesanan} untuk pemakai '{$validated['nama_pemesan']}' ({$validated['divisi_pemesan']})"
            );

            DB::commit();

            return redirect()->route('admin.pemesanan.show', $pemesanan->id)
                ->with('success', "Pemesanan {$kodePemesanan} berhasil dibuat dan saat ini Menunggu Persetujuan Level 1.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Terjadi kesalahan saat menyimpan pemesanan: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        $pemesanan = Pemesanan::with([
            'kendaraan.lokasi',
            'driver',
            'lokasiTujuan',
            'admin',
            'persetujuan.penyetuju',
            'logBbm'
        ])->findOrFail($id);

        return view('admin.pemesanan.show', compact('pemesanan'));
    }

    public function tandaiSelesai(Request $request, $id)
    {
        $pemesanan = Pemesanan::with(['kendaraan', 'driver'])->findOrFail($id);

        if ($pemesanan->status_pemesanan !== 'disetujui') {
            return back()->with('error', 'Hanya pemesanan yang telah disetujui yang dapat diselesaikan.');
        }

        $validated = $request->validate([
            'odometer_akhir' => ['required', 'integer', "gte:{$pemesanan->kendaraan->odometer_km}"],
            'jumlah_liter_bbm' => ['nullable', 'numeric', 'min:0'],
            'biaya_bbm' => ['nullable', 'numeric', 'min:0'],
        ], [
            'odometer_akhir.gte' => 'Odometer akhir tidak boleh lebih kecil dari odometer awal kendaraan (' . number_format($pemesanan->kendaraan->odometer_km) . ' KM).',
        ]);

        DB::beginTransaction();
        try {
            // Update Odometer Kendaraan & status kembali 'tersedia'
            $odometerAwal = $pemesanan->kendaraan->odometer_km;
            $pemesanan->kendaraan->update([
                'odometer_km' => $validated['odometer_akhir'],
                'status_kendaraan' => 'tersedia',
            ]);

            // Update status Driver kembali 'standby'
            $pemesanan->driver->update([
                'status_driver' => 'standby',
            ]);

            // Jika ada input BBM, simpan ke LogBbm
            if (!empty($validated['jumlah_liter_bbm']) && $validated['jumlah_liter_bbm'] > 0) {
                LogBbm::create([
                    'pemesanan_id' => $pemesanan->id,
                    'kendaraan_id' => $pemesanan->kendaraan_id,
                    'jumlah_liter' => $validated['jumlah_liter_bbm'],
                    'total_biaya' => $validated['biaya_bbm'] ?? 0,
                    'odometer_awal' => $odometerAwal,
                    'odometer_akhir' => $validated['odometer_akhir'],
                    'tanggal_isi' => Carbon::now()->toDateString(),
                ]);
            }

            // Update status pemesanan menjadi selesai
            $pemesanan->update([
                'status_pemesanan' => 'selesai',
            ]);

            LogAktivitas::catat(
                'SELESAIKAN_PEMESANAN',
                "Pemesanan {$pemesanan->kode_pemesanan} selesai. Unit {$pemesanan->kendaraan->nomor_lambung} & driver kembali standby."
            );

            DB::commit();

            return back()->with('success', "Pemesanan {$pemesanan->kode_pemesanan} berhasil diselesaikan. Unit dan Driver kini kembali berstatus Tersedia.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal menyelesaikan pemesanan: ' . $e->getMessage());
        }
    }
}

