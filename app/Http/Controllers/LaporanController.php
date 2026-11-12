<?php

namespace App\Http\Controllers;

use App\Models\Pemesanan;
use App\Models\Lokasi;
use App\Models\LogAktivitas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    public function index(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subMonths(1)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = Pemesanan::with(['kendaraan', 'driver', 'lokasiTujuan', 'admin', 'persetujuan.penyetuju', 'logBbm'])
            ->whereBetween('tanggal_mulai', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status_pemesanan', $request->status);
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_tujuan_id', $request->lokasi_id);
        }

        $pemesanan = $query->latest('tanggal_mulai')->paginate(15)->withQueryString();
        $lokasiList = Lokasi::all();

        return view('admin.laporan.index', compact('pemesanan', 'lokasiList', 'startDate', 'endDate'));
    }

    public function exportExcel(Request $request)
    {
        $startDate = $request->filled('start_date') ? Carbon::parse($request->start_date)->startOfDay() : Carbon::now()->subMonths(1)->startOfDay();
        $endDate = $request->filled('end_date') ? Carbon::parse($request->end_date)->endOfDay() : Carbon::now()->endOfDay();

        $query = Pemesanan::with(['kendaraan', 'driver', 'lokasiTujuan', 'admin', 'persetujuan.penyetuju', 'logBbm'])
            ->whereBetween('tanggal_mulai', [$startDate, $endDate]);

        if ($request->filled('status')) {
            $query->where('status_pemesanan', $request->status);
        }

        if ($request->filled('lokasi_id')) {
            $query->where('lokasi_tujuan_id', $request->lokasi_id);
        }

        $data = $query->latest('tanggal_mulai')->get();

        LogAktivitas::catat(
            'EXPORT_EXCEL',
            "Admin mengunduh laporan pemesanan kendaraan periode {$startDate->format('d/m/Y')} s/d {$endDate->format('d/m/Y')}"
        );

        $filename = 'Laporan_Pemesanan_Kendaraan_' . Carbon::now()->format('Ymd_His') . '.xls';

        // Format HTML Spreadsheet (.xls) yang kompatibel 100% dengan Microsoft Excel, LibreOffice, dan Google Sheets
        $output = view('admin.laporan.excel', compact('data', 'startDate', 'endDate'))->render();

        return response($output, 200, [
            'Content-Type' => 'application/vnd.ms-excel; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
