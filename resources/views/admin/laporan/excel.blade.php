<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @verbatim
    <!--[if gte mso 9]>
    <xml>
    <x:ExcelWorkbook>
    <x:ExcelWorksheets>
    <x:ExcelWorksheet>
    <x:Name>Laporan Pemesanan</x:Name>
    <x:WorksheetOptions>
    <x:DisplayGridlines/>
    </x:WorksheetOptions>
    </x:ExcelWorksheet>
    </x:ExcelWorksheets>
    </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    @endverbatim
    <style>
        table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; }
        th { background-color: #047857; color: #ffffff; font-weight: bold; padding: 10px; border: 1px solid #000000; text-align: center; }
        td { padding: 8px; border: 1px solid #d1d5db; }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .title { font-size: 16pt; font-weight: bold; text-align: center; color: #064e3b; }
        .subtitle { font-size: 11pt; text-align: center; color: #374151; margin-bottom: 15px; }
        .status-badge { font-weight: bold; }
    </style>
</head>
<body>
    <table>
        <tr>
            <td colspan="10" class="title">PT NIKEL MINING NUSANTARA</td>
        </tr>
        <tr>
            <td colspan="10" class="subtitle">LAPORAN PERIODIK PEMESANAN KENDARAAN OPERASIONAL TAMBANG</td>
        </tr>
        <tr>
            <td colspan="10"><strong>Periode:</strong> {{ $startDate->format('d F Y') }} s/d {{ $endDate->format('d F Y') }} | <strong>Dicetak:</strong> {{ now()->format('d/m/Y H:i:s') }}</td>
        </tr>
        <tr>
            <td colspan="10"></td>
        </tr>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode Booking</th>
                <th>Nama Pemesan</th>
                <th>Divisi</th>
                <th>Unit Kendaraan</th>
                <th>Pengemudi</th>
                <th>Tujuan Lokasi</th>
                <th>Waktu Berangkat</th>
                <th>Waktu Kembali</th>
                <th>Status Persetujuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td style="mso-number-format:'\@';">{{ $row->kode_pemesanan }}</td>
                <td>{{ $row->nama_pemesan }}</td>
                <td>{{ $row->divisi_pemesan }}</td>
                <td>{{ $row->kendaraan->nomor_lambung }} - {{ $row->kendaraan->merk_model }} ({{ $row->kendaraan->nomor_polisi }})</td>
                <td>{{ $row->driver->nama_driver }}</td>
                <td>{{ $row->lokasiTujuan->nama_lokasi }} ({{ $row->lokasiTujuan->kode_lokasi }})</td>
                <td class="text-center">{{ $row->tanggal_mulai->format('d/m/Y H:i') }}</td>
                <td class="text-center">{{ $row->tanggal_selesai->format('d/m/Y H:i') }}</td>
                <td class="text-center status-badge">
                    @if($row->status_pemesanan === 'disetujui')
                        DISETUJUI
                    @elseif($row->status_pemesanan === 'selesai')
                        SELESAI
                    @elseif($row->status_pemesanan === 'ditolak')
                        DITOLAK
                    @elseif($row->status_pemesanan === 'menunggu_level_1')
                        MENUNGGU LEVEL 1
                    @elseif($row->status_pemesanan === 'menunggu_level_2')
                        MENUNGGU LEVEL 2
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="10" class="text-center">Tidak ada data pemesanan pada rentang tanggal ini.</td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-right" style="font-weight: bold; background-color: #f3f4f6;">Total Transaksi Pemesanan:</td>
                <td class="text-center" style="font-weight: bold; background-color: #f3f4f6;">{{ $data->count() }} Permohonan</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
