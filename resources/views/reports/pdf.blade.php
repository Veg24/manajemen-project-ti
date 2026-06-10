<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Keuangan SI-KLINIK</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            font-size: 11px;
            color: #333;
            line-height: 1.4;
        }
        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #4f46e5;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 20px;
            color: #4f46e5;
            text-transform: uppercase;
        }
        .header p {
            margin: 4px 0 0 0;
            color: #666;
            font-size: 12px;
        }
        .meta-info {
            margin-bottom: 20px;
            font-size: 11px;
        }
        .meta-info table {
            width: 100%;
        }
        .summary-box {
            background-color: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 20px;
        }
        .summary-box table {
            width: 100%;
        }
        .summary-box td {
            padding: 4px 8px;
        }
        .summary-title {
            font-weight: bold;
            color: #4f46e5;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            border: 1px solid #cbd5e1;
            padding: 8px;
            text-align: left;
            font-weight: bold;
        }
        table.data-table td {
            border: 1px solid #cbd5e1;
            padding: 7px 8px;
        }
        .text-right {
            text-align: right;
        }
        .font-bold {
            font-weight: bold;
        }
        .footer {
            margin-top: 40px;
            text-align: right;
        }
        .footer-signature {
            display: inline-block;
            text-align: center;
            width: 200px;
        }
        .footer-space {
            height: 60px;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>Sistem Informasi Manajemen Klinik (SI-KLINIK)</h1>
        <p>Laporan Statistik & Rekapitulasi Keuangan Pasien</p>
    </div>

    <div class="meta-info">
        <table>
            <tr>
                <td style="width: 50%;"><strong>Periode Laporan:</strong> {{ \Carbon\Carbon::parse($startDate)->format('d-m-Y') }} s/d {{ \Carbon\Carbon::parse($endDate)->format('d-m-Y') }}</td>
                <td style="width: 50%; text-align: right;"><strong>Tanggal Unduh:</strong> {{ $printDate }}</td>
            </tr>
        </table>
    </div>

    <div class="summary-box">
        <table>
            <tr>
                <td><span class="summary-title">Total Kunjungan Pasien:</span></td>
                <td><strong>{{ count($financialDetails) }} Kunjungan</strong></td>
                <td><span class="summary-title">Total Jasa Konsultasi:</span></td>
                <td>Rp{{ number_format($totalConsultations, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td><span class="summary-title">Total Pemasukan Obat:</span></td>
                <td>Rp{{ number_format($totalMedicinesCost, 0, ',', '.') }}</td>
                <td><span class="summary-title">Total Tindakan Medis:</span></td>
                <td>Rp{{ number_format($totalActions, 0, ',', '.') }}</td>
            </tr>
            <tr class="font-bold" style="font-size: 13px;">
                <td colspan="2"></td>
                <td style="color: #16a34a; border-top: 1px solid #cbd5e1; padding-top: 8px;">TOTAL REVENUE:</td>
                <td style="color: #16a34a; border-top: 1px solid #cbd5e1; padding-top: 8px;">Rp{{ number_format($grandTotalRevenue, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <h2 style="font-size: 13px; color: #4f46e5; border-bottom: 1px solid #cbd5e1; padding-bottom: 5px; margin-top: 25px;">Rincian Transaksi Keuangan Harian</h2>
    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 15%;">Tanggal</th>
                <th style="width: 18%;">Nama Pasien</th>
                <th style="width: 18%;">Dokter Pemeriksa</th>
                <th style="width: 12%; text-align: right;">Jasa Konsul</th>
                <th style="width: 12%; text-align: right;">Tindakan Medis</th>
                <th style="width: 12%; text-align: right;">Obat Apotek</th>
                <th style="width: 13%; text-align: right;">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @if(empty($financialDetails))
                <tr>
                    <td colspan="7" style="text-align: center; padding: 20px; color: #666;">Tidak ada transaksi keuangan pada periode ini.</td>
                </tr>
            @else
                @foreach($financialDetails as $detail)
                    <tr>
                        <td>{{ $detail['date'] }}</td>
                        <td><strong>{{ $detail['patient'] }}</strong></td>
                        <td>{{ $detail['doctor'] }}</td>
                        <td class="text-right">Rp{{ number_format($detail['consult_fee'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($detail['action_fee'], 0, ',', '.') }}</td>
                        <td class="text-right">Rp{{ number_format($detail['medicine_cost'], 0, ',', '.') }}</td>
                        <td class="text-right font-bold">Rp{{ number_format($detail['total'], 0, ',', '.') }}</td>
                    </tr>
                @endforeach
            @endif
        </tbody>
        <tfoot>
            <tr class="font-bold" style="background-color: #f8fafc;">
                <td colspan="3" style="text-align: right; border-top: 2px solid #4f46e5; padding: 8px;">Total:</td>
                <td class="text-right" style="border-top: 2px solid #4f46e5; padding: 8px;">Rp{{ number_format($totalConsultations, 0, ',', '.') }}</td>
                <td class="text-right" style="border-top: 2px solid #4f46e5; padding: 8px;">Rp{{ number_format($totalActions, 0, ',', '.') }}</td>
                <td class="text-right" style="border-top: 2px solid #4f46e5; padding: 8px;">Rp{{ number_format($totalMedicinesCost, 0, ',', '.') }}</td>
                <td class="text-right" style="border-top: 2px solid #4f46e5; padding: 8px; color: #16a34a; font-size: 12px;">Rp{{ number_format($grandTotalRevenue, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <div class="footer-signature">
            <p>Yogyakarta, {{ \Carbon\Carbon::now()->format('d F Y') }}</p>
            <p>Mengetahui,</p>
            <p class="font-bold">Manajer Operasional Klinik</p>
            <div class="footer-space"></div>
            <p style="text-decoration: underline; font-weight: bold;">( ______________________ )</p>
        </div>
    </div>

</body>
</html>
