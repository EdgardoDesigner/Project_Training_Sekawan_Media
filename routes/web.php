<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\AdminPemesananController;
use App\Http\Controllers\AdminKendaraanController;
use App\Http\Controllers\AdminLogController;
use App\Http\Controllers\ApproverController;

// Redirect Root to Login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Admin Panel Routes (Protected by Auth)
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Pemesanan Kendaraan
    Route::get('/pemesanan', [AdminPemesananController::class, 'index'])->name('pemesanan.index');
    Route::get('/pemesanan/create', [AdminPemesananController::class, 'create'])->name('pemesanan.create');
    Route::post('/pemesanan', [AdminPemesananController::class, 'store'])->name('pemesanan.store');
    Route::get('/pemesanan/{id}', [AdminPemesananController::class, 'show'])->name('pemesanan.show');
    Route::post('/pemesanan/{id}/selesai', [AdminPemesananController::class, 'tandaiSelesai'])->name('pemesanan.selesai');
    
    // Monitoring Armada & Jadwal Servis
    Route::get('/kendaraan', [AdminKendaraanController::class, 'index'])->name('kendaraan.index');

    // Laporan Periodik & Ekspor Excel
    Route::get('/laporan', [App\Http\Controllers\LaporanController::class, 'index'])->name('laporan.index');
    Route::get('/laporan/export-excel', [App\Http\Controllers\LaporanController::class, 'exportExcel'])->name('laporan.excel');

    // Audit Trail / Log Aktivitas
    Route::get('/logs', [AdminLogController::class, 'index'])->name('logs.index');
});

// Approver (Pihak Penyetuju) Routes (Protected by Auth)
Route::middleware(['auth'])->prefix('approver')->name('approver.')->group(function () {
    Route::get('/dashboard', [ApproverController::class, 'dashboard'])->name('dashboard');
    Route::post('/persetujuan/{id}/approve', [ApproverController::class, 'approve'])->name('approve');
    Route::post('/persetujuan/{id}/reject', [ApproverController::class, 'reject'])->name('reject');
});
