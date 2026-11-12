<?php

namespace App\Http\Controllers;

use App\Models\LogAktivitas;
use Illuminate\Http\Request;

class AdminLogController extends Controller
{
    public function index(Request $request)
    {
        $query = LogAktivitas::with('pengguna');

        if ($request->filled('aksi')) {
            $query->where('aksi', $request->aksi);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('deskripsi', 'like', "%{$search}%");
        }

        $logs = $query->latest()->paginate(15)->withQueryString();

        return view('admin.logs.index', compact('logs'));
    }
}

