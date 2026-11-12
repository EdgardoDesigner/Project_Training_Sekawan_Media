<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lokasi;
use App\Models\Driver;
use App\Models\Kendaraan;
use App\Models\LogAktivitas;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Seed Pengguna (Admin & Approvers)
        $admin = User::create([
            'nama' => 'Budi Santoso (Admin Pool)',
            'email' => 'admin@nikelfleet.com',
            'peran' => 'admin',
            'jabatan' => 'Koordinator Pool Kendaraan',
            'nomor_telepon' => '081234567890',
            'password' => Hash::make('password123'),
        ]);

        $approver1 = User::create([
            'nama' => 'Hendra Gunawan (Approver L1)',
            'email' => 'spv.tambang@nikelfleet.com',
            'peran' => 'penyetuju',
            'jabatan' => 'Supervisor Operasional Tambang',
            'nomor_telepon' => '081234567891',
            'password' => Hash::make('password123'),
        ]);

        $approver2 = User::create([
            'nama' => 'Ir. Bambang Hartono (Approver L2)',
            'email' => 'gm.operasional@nikelfleet.com',
            'peran' => 'penyetuju',
            'jabatan' => 'General Manager Operasional',
            'nomor_telepon' => '081234567892',
            'password' => Hash::make('password123'),
        ]);

        // 2. Seed Lokasi (1 Kantor Pusat, 1 Kantor Cabang, 6 Site Tambang)
        $lokasiList = [
            ['kode_lokasi' => 'HO-JKT', 'nama_lokasi' => 'Kantor Pusat Jakarta', 'tipe_lokasi' => 'kantor_pusat', 'alamat' => 'Gedung Menara Nikel Lt. 18, Jakarta Selatan'],
            ['kode_lokasi' => 'BO-KND', 'nama_lokasi' => 'Kantor Cabang Kendari', 'tipe_lokasi' => 'kantor_cabang', 'alamat' => 'Jl. Poros Bandara No. 45, Kendari, Sulawesi Tenggara'],
            ['kode_lokasi' => 'SITE-PML', 'nama_lokasi' => 'Site Tambang Pomalaa', 'tipe_lokasi' => 'tambang', 'alamat' => 'Area Blok 1 Pomalaa, Kolaka, Sulawesi Tenggara'],
            ['kode_lokasi' => 'SITE-MRW', 'nama_lokasi' => 'Site Tambang Morowali', 'tipe_lokasi' => 'tambang', 'alamat' => 'Kawasan Industri Tambang Morowali, Sulawesi Tengah'],
            ['kode_lokasi' => 'SITE-HLM', 'nama_lokasi' => 'Site Tambang Halmahera', 'tipe_lokasi' => 'tambang', 'alamat' => 'Teluk Weda, Halmahera Tengah, Maluku Utara'],
            ['kode_lokasi' => 'SITE-KNW', 'nama_lokasi' => 'Site Tambang Konawe', 'tipe_lokasi' => 'tambang', 'alamat' => 'Kawasan Pertambangan Konawe Utara, Sulawesi Tenggara'],
            ['kode_lokasi' => 'SITE-SRW', 'nama_lokasi' => 'Site Tambang Sorowako', 'tipe_lokasi' => 'tambang', 'alamat' => 'Kawasan Danau Matano, Sorowako, Luwu Timur'],
            ['kode_lokasi' => 'SITE-KLK', 'nama_lokasi' => 'Site Tambang Kolaka', 'tipe_lokasi' => 'tambang', 'alamat' => 'Kecamatan Baula, Kolaka, Sulawesi Tenggara'],
        ];

        $lokasiObjects = [];
        foreach ($lokasiList as $item) {
            $lokasiObjects[$item['kode_lokasi']] = Lokasi::create($item);
        }

        // 3. Seed Pengemudi (Drivers)
        $drivers = [
            ['nama_driver' => 'Ahmad Fauzi', 'nomor_telepon' => '082199887701', 'nomor_sim' => 'SIM-B2-00912', 'jenis_sim' => 'SIM B2 Umum', 'status_driver' => 'standby'],
            ['nama_driver' => 'Dedi Kusuma', 'nomor_telepon' => '082199887702', 'nomor_sim' => 'SIM-B1-00913', 'jenis_sim' => 'SIM B1', 'status_driver' => 'standby'],
            ['nama_driver' => 'Rian Hidayat', 'nomor_telepon' => '082199887703', 'nomor_sim' => 'SIM-A-00914', 'jenis_sim' => 'SIM A', 'status_driver' => 'standby'],
            ['nama_driver' => 'Agus Setiawan', 'nomor_telepon' => '082199887704', 'nomor_sim' => 'SIM-B2-00915', 'jenis_sim' => 'SIM B2 Umum', 'status_driver' => 'standby'],
        ];

        foreach ($drivers as $d) {
            Driver::create($d);
        }

        // 4. Seed Kendaraan (Orang & Barang, Milik & Sewa)
        $vehicles = [
            [
                'nomor_polisi' => 'DT 8122 XX',
                'nomor_lambung' => 'LV-01',
                'merk_model' => 'Toyota Hilux 4x4 Double Cabin 2.8',
                'jenis_kendaraan' => 'angkutan_orang',
                'kepemilikan' => 'milik_perusahaan',
                'nama_vendor_sewa' => null,
                'biaya_sewa_bulanan' => null,
                'lokasi_id' => $lokasiObjects['SITE-PML']->id,
                'odometer_km' => 42150,
                'kapasitas_tangki' => 80,
                'konsumsi_bbm_per_liter' => 9.50,
                'tanggal_servis_terakhir' => Carbon::now()->subMonths(2),
                'tanggal_servis_berikutnya' => Carbon::now()->addMonths(1),
                'status_kendaraan' => 'tersedia',
            ],
            [
                'nomor_polisi' => 'DT 8123 XX',
                'nomor_lambung' => 'LV-02',
                'merk_model' => 'Mitsubishi Pajero Sport 4x4 Dakar',
                'jenis_kendaraan' => 'angkutan_orang',
                'kepemilikan' => 'milik_perusahaan',
                'nama_vendor_sewa' => null,
                'biaya_sewa_bulanan' => null,
                'lokasi_id' => $lokasiObjects['BO-KND']->id,
                'odometer_km' => 31000,
                'kapasitas_tangki' => 68,
                'konsumsi_bbm_per_liter' => 11.00,
                'tanggal_servis_terakhir' => Carbon::now()->subMonths(1),
                'tanggal_servis_berikutnya' => Carbon::now()->addMonths(2),
                'status_kendaraan' => 'tersedia',
            ],
            [
                'nomor_polisi' => 'DT 8991 XX',
                'nomor_lambung' => 'LV-03',
                'merk_model' => 'Toyota Fortuner 4x4 VRZ (Sewa Operasional)',
                'jenis_kendaraan' => 'angkutan_orang',
                'kepemilikan' => 'sewa',
                'nama_vendor_sewa' => 'PT Trans Nusantara Rental',
                'biaya_sewa_bulanan' => 18500000.00,
                'lokasi_id' => $lokasiObjects['SITE-MRW']->id,
                'odometer_km' => 58200,
                'kapasitas_tangki' => 80,
                'konsumsi_bbm_per_liter' => 10.20,
                'tanggal_servis_terakhir' => Carbon::now()->subMonths(3),
                'tanggal_servis_berikutnya' => Carbon::now()->subDays(5), // Overdue service!
                'status_kendaraan' => 'tersedia',
            ],
            [
                'nomor_polisi' => 'DT 9101 XX',
                'nomor_lambung' => 'DT-01',
                'merk_model' => 'Hino Dutro 130 HD Cargo Box',
                'jenis_kendaraan' => 'angkutan_barang',
                'kepemilikan' => 'milik_perusahaan',
                'nama_vendor_sewa' => null,
                'biaya_sewa_bulanan' => null,
                'lokasi_id' => $lokasiObjects['SITE-PML']->id,
                'odometer_km' => 89400,
                'kapasitas_tangki' => 100,
                'konsumsi_bbm_per_liter' => 6.50,
                'tanggal_servis_terakhir' => Carbon::now()->subMonths(2),
                'tanggal_servis_berikutnya' => Carbon::now()->addDays(12),
                'status_kendaraan' => 'tersedia',
            ],
            [
                'nomor_polisi' => 'DT 9102 XX',
                'nomor_lambung' => 'DT-02',
                'merk_model' => 'Mitsubishi Fuso Canter Dump Truck',
                'jenis_kendaraan' => 'angkutan_barang',
                'kepemilikan' => 'milik_perusahaan',
                'nama_vendor_sewa' => null,
                'biaya_sewa_bulanan' => null,
                'lokasi_id' => $lokasiObjects['SITE-KNW']->id,
                'odometer_km' => 74300,
                'kapasitas_tangki' => 100,
                'konsumsi_bbm_per_liter' => 6.00,
                'tanggal_servis_terakhir' => Carbon::now()->subMonths(1),
                'tanggal_servis_berikutnya' => Carbon::now()->addMonths(3),
                'status_kendaraan' => 'tersedia',
            ],
            [
                'nomor_polisi' => 'DT 9999 XX',
                'nomor_lambung' => 'HT-01',
                'merk_model' => 'Scania P360 Heavy Mining Dump Truck',
                'jenis_kendaraan' => 'angkutan_barang',
                'kepemilikan' => 'sewa',
                'nama_vendor_sewa' => 'PT Rental Alat Tambang Perkasa',
                'biaya_sewa_bulanan' => 45000000.00,
                'lokasi_id' => $lokasiObjects['SITE-SRW']->id,
                'odometer_km' => 112000,
                'kapasitas_tangki' => 300,
                'konsumsi_bbm_per_liter' => 3.20,
                'tanggal_servis_terakhir' => Carbon::now()->subMonths(2),
                'tanggal_servis_berikutnya' => Carbon::now()->addDays(20),
                'status_kendaraan' => 'tersedia',
            ],
        ];

        foreach ($vehicles as $v) {
            Kendaraan::create($v);
        }

        // 5. Seed Log Aktivitas Awal
        LogAktivitas::create([
            'pengguna_id' => $admin->id,
            'aksi' => 'INISIALISASI_SISTEM',
            'deskripsi' => 'Inisialisasi sistem NikelFleet dan seeding data master perusahaan tambang',
            'alamat_ip' => '127.0.0.1',
            'perangkat' => 'System Seeder',
        ]);
    }
}
