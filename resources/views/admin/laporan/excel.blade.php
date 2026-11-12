<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    @verbatim
    <!--[if gte mso 9]>
    <xml>
    <x:ExcelWorkbook>
    <x:ExcelWorksheets>
    <x:ExcelWorksheet>
    <x:Name>Rekap Pemesanan</x:Name>
    <x:WorksheetOptions>
    <x:DisplayGridlines/>
    <x:Print>
        <x:ValidPrinterInfo/>
        <x:PaperSizeIndex>9</x:PaperSizeIndex>
        <x:HorizontalResolution>600</x:HorizontalResolution>
        <x:VerticalResolution>600</x:VerticalResolution>
    </x:Print>
    </x:WorksheetOptions>
    </x:ExcelWorksheet>
    </x:ExcelWorksheets>
    </x:ExcelWorkbook>
    </xml>
    <![endif]-->
    @endverbatim
    <style>
        body { font-family: Calibri, 'Segoe UI', Arial, sans-serif; font-size: 10pt; color: #1e293b; }
        table { border-collapse: collapse; width: 100%; table-layout: auto; }
        .company-header { font-size: 14pt; font-weight: bold; color: #0f172a; text-align: left; }
        .report-title { font-size: 12pt; font-weight: bold; color: #334155; text-align: left; }
        .meta-info { font-size: 9pt; color: #64748b; margin-bottom: 12px; }
        
        th { 
            background-color: #0f172a; 
            color: #ffffff; 
            font-weight: bold; 
            font-size: 9.5pt;
            padding: 8px 10px; 
            border: 1pt solid #0f172a; 
            text-align: center;
            vertical-align: middle;
        }
        
        td { 
            padding: 6px 8px; 
            border: 0.5pt solid #cbd5e1; 
            font-size: 9pt;
            vertical-align: middle;
        }
        
        .row-even { background-color: #f8fafc; }
        .row-odd { background-color: #ffffff; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .font-mono { font-family: Consolas, 'Courier New', monospace; }
        
        /* Status Badges */
        .status-disetujui { background-color: #ecfdf5; color: #047857; font-weight: bold; text-align: center; }
        .status-selesai { background-color: #f0fdf4; color: #15803d; font-weight: bold; text-align: center; }
        .status-menunggu-1 { background-color: #fffbeb; color: #b45309; font-weight: bold; text-align: center; }
        .status-menunggu-2 { background-color: #eff6ff; color: #1d4ed8; font-weight: bold; text-align: center; }
        .status-ditolak { background-color: #fef2f2; color: #b91c1c; font-weight: bold; text-align: center; }
        
        /* Summary Footer */
        .summary-label { background-color: #e2e8f0; font-weight: bold; text-align: right; border-top: 1.5pt solid #0f172a; }
        .summary-val { background-color: #f1f5f9; font-weight: bold; text-align: center; border-top: 1.5pt solid #0f172a; }
    </style>
</head>
<body>
    <table>
        <!-- Header Perusahaan -->
        <tr>
            <td colspan="12" class="company-header" style="border: none; padding-bottom: 2px;">
                PT NIKEL FLEET NUSANTARA
            </td>
        </tr>
        <tr>
            <td colspan="12" class="report-title" style="border: none; padding-bottom: 4px;">
                REKAPITULASI PEMESANAN & MONITORING KENDARAAN OPERASIONAL TAMBANG
            </td>
        </tr>
        <tr>
            <td colspan="12" class="meta-info" style="border: none; padding-bottom: 12px;">
                Periode Laporan: <strong>{{ $startDate->format('d/m/Y') }}</strong> s/d <strong>{{ $endDate->format('d/m/Y') }}</strong> &nbsp;|&nbsp; 
                Total Data: <strong>{{ $data->count() }} Transaksi</strong> &nbsp;|&nbsp; 
                Tanggal Cetak: <strong>{{ now()->format('d/m/Y H:i') }} WITA</strong>
            </td>
        </tr>
        <tr>
            <!-- Spacer Row -->
            <td colspan="12" style="border: none; height: 6px;"></td>
        </tr>

        <!-- Table Column Headers -->
        <thead>
            <tr>
                <th style="width: 35px;">No</th>
                <th style="width: 110px;">Kode Booking</th>
                <th style="width: 140px;">Nama Pemohon</th>
                <th style="width: 130px;">Divisi</th>
                <th style="width: 160px;">Unit Kendaraan</th>
                <th style="width: 110px;">Pengemudi</th>
                <th style="width: 130px;">Tujuan Site</th>
                <th style="width: 110px;">Waktu Berangkat</th>
                <th style="width: 110px;">Waktu Kembali</th>
                <th style="width: 120px;">Penyetuju L1 (Spv)</th>
                <th style="width: 120px;">Penyetuju L2 (GM)</th>
                <th style="width: 110px;">Status Final</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $index => $row)
            @php
                $bgClass = ($index % 2 === 0) ? 'row-odd' : 'row-even';
                $appL1 = $row->persetujuan->firstWhere('level_persetujuan', 1);
                $appL2 = $row->persetujuan->firstWhere('level_persetujuan', 2);
            @endphp
            <tr class="{{ $bgClass }}">
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-mono text-center" style="mso-number-format:'\@';">{{ $row->kode_pemesanan }}</td>
                <td>{{ $row->nama_pemesan }}</td>
                <td>{{ $row->divisi_pemesan }}</td>
                <td>
                    <strong>[{{ $row->kendaraan->nomor_lambung }}]</strong> {{ $row->kendaraan->merk_model }}<br>
                    <span style="font-size: 8pt; color: #64748b;">{{ $row->kendaraan->nomor_polisi }} ({{ ucfirst(str_replace('_', ' ', $row->kendaraan->kepemilikan)) }})</span>
                </td>
                <td>{{ $row->driver->nama_driver }} ({{ $row->driver->jenis_sim }})</td>
                <td>{{ $row->lokasiTujuan->nama_lokasi }}</td>
                <td class="text-center font-mono">{{ $row->tanggal_mulai->format('d/m/Y H:i') }}</td>
                <td class="text-center font-mono">{{ $row->tanggal_selesai->format('d/m/Y H:i') }}</td>
                
                <!-- Penyetuju L1 -->
                <td>
                    @if($appL1)
                        {{ $appL1->penyetuju->nama }}<br>
                        <span style="font-size: 8pt; color: {{ $appL1->status_persetujuan === 'disetujui' ? '#047857' : ($appL1->status_persetujuan === 'ditolak' ? '#b91c1c' : '#b45309') }};">
                            [{{ strtoupper($appL1->status_persetujuan) }}]
                        </span>
                    @else
                        -
                    @endif
                </td>

                <!-- Penyetuju L2 -->
                <td>
                    @if($appL2)
                        {{ $appL2->penyetuju->nama }}<br>
                        <span style="font-size: 8pt; color: {{ $appL2->status_persetujuan === 'disetujui' ? '#047857' : ($appL2->status_persetujuan === 'ditolak' ? '#b91c1c' : '#b45309') }};">
                            [{{ strtoupper($appL2->status_persetujuan) }}]
                        </span>
                    @else
                        -
                    @endif
                </td>

                <!-- Status Badge -->
                <td class="
                    @if($row->status_pemesanan === 'disetujui') status-disetujui
                    @elseif($row->status_pemesanan === 'selesai') status-selesai
                    @elseif($row->status_pemesanan === 'menunggu_level_1') status-menunggu-1
                    @elseif($row->status_pemesanan === 'menunggu_level_2') status-menunggu-2
                    @elseif($row->status_pemesanan === 'ditolak') status-ditolak
                    @endif
                ">
                    {{ strtoupper(str_replace('_', ' ', $row->status_pemesanan)) }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="12" class="text-center" style="padding: 18px; color: #64748b;">
                    Tidak ada transaksi pemesanan pada rentang tanggal filter ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr>
                <td colspan="11" class="summary-label">TOTAL PERMOHONAN TRANSAKSI:</td>
                <td class="summary-val">{{ $data->count() }} Booking</td>
            </tr>
            <tr>
                <td colspan="11" class="summary-label">UNIT ARMADA BEROPERASI:</td>
                <td class="summary-val">{{ $data->unique('kendaraan_id')->count() }} Unit Berbeda</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>
