<?php

namespace App\Http\Controllers;

use App\Models\Kendaraan;
use App\Models\Lokasi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AdminKendaraanController extends Controller
{
    public function index(Request $request)
    {
        $query = Kendaraan::with('lokasi');

        if ($request->filled('jenis')) {
            $query->where('jenis_kendaraan', $request->jenis);
        }

        if ($request->filled('kepemilikan')) {
            $query->where('kepemilikan', $request->kepemilikan);
        }

        if ($request->filled('status')) {
            $query->where('status_kendaraan', $request->status);
        }

        $kendaraan = $query->orderBy('nomor_lambung')->get();
        $lokasiList = Lokasi::all();

        return view('admin.kendaraan.index', compact('kendaraan', 'lokasiList'));
    }
}

