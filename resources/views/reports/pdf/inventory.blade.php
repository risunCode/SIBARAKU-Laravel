<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1.4;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        .header h1 {
            font-size: 16pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .header p {
            font-size: 10pt;
            color: #666;
        }
        .meta {
            margin-bottom: 15px;
            font-size: 9pt;
        }
        .meta table {
            width: 100%;
        }
        .meta td {
            padding: 2px 0;
        }
        table.data {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table.data th,
        table.data td {
            border: 1px solid #333;
            padding: 6px 8px;
            text-align: left;
        }
        table.data th {
            background-color: #f0f0f0;
            font-weight: bold;
            font-size: 9pt;
        }
        table.data td {
            font-size: 9pt;
        }
        table.data tr:nth-child(even) {
            background-color: #fafafa;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
        .footer {
            margin-top: 30px;
            font-size: 9pt;
        }
        .signature-area {
            margin-top: 50px;
        }
        .signature-box {
            display: inline-block;
            width: 200px;
            text-align: center;
        }
        .signature-line {
            border-bottom: 1px solid #333;
            margin-top: 60px;
            margin-bottom: 5px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 8pt;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-danger {
            background-color: #f8d7da;
            color: #721c24;
        }
        .page-break {
            page-break-after: always;
        }
        .summary {
            margin-top: 20px;
            padding: 10px;
            background-color: #f5f5f5;
            border: 1px solid #ddd;
        }
        .summary table {
            width: 100%;
        }
        .summary td {
            padding: 3px 0;
        }
        .summary .label {
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ config('app.name') }}</h1>
        <h1>{{ $title }}</h1>
        <p>Dicetak pada: {{ $date }}</p>
    </div>

    <div class="meta">
        <table>
            <tr>
                <td width="100"><strong>Total Barang</strong></td>
                <td>: {{ number_format($commodities->count()) }} item</td>
            </tr>
            <tr>
                <td><strong>Total Nilai</strong></td>
                <td>: Rp {{ number_format($commodities->sum('purchase_price'), 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>

    <table class="data">
        <thead>
            <tr>
                <th width="30" class="text-center">No</th>
                <th width="80">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="100">Kategori</th>
                <th width="100">Lokasi</th>
                <th width="60" class="text-center">Kondisi</th>
                <th width="50" class="text-center">Qty</th>
                <th width="90" class="text-right">Nilai (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @foreach($commodities as $index => $commodity)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $commodity->item_code }}</td>
                <td>{{ $commodity->name }}</td>
                <td>{{ $commodity->category->name ?? '-' }}</td>
                <td>{{ $commodity->location->name ?? '-' }}</td>
                <td class="text-center">
                    @if($commodity->condition === 'baik')
                        <span class="badge badge-success">Baik</span>
                    @elseif($commodity->condition === 'rusak_ringan')
                        <span class="badge badge-warning">R.Ringan</span>
                    @else
                        <span class="badge badge-danger">R.Berat</span>
                    @endif
                </td>
                <td class="text-center">{{ $commodity->quantity }}</td>
                <td class="text-right">{{ number_format($commodity->purchase_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="6" class="text-right">Total</th>
                <th class="text-center">{{ $commodities->sum('quantity') }}</th>
                <th class="text-right">{{ number_format($commodities->sum('purchase_price'), 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <div class="summary">
        <table>
            <tr>
                <td class="label" width="150">Kondisi Baik</td>
                <td>: {{ $commodities->where('condition', 'baik')->count() }} item</td>
            </tr>
            <tr>
                <td class="label">Rusak Ringan</td>
                <td>: {{ $commodities->where('condition', 'rusak_ringan')->count() }} item</td>
            </tr>
            <tr>
                <td class="label">Rusak Berat</td>
                <td>: {{ $commodities->where('condition', 'rusak_berat')->count() }} item</td>
            </tr>
        </table>
    </div>

    <div class="signature-area">
        <table width="100%">
            <tr>
                <td width="50%"></td>
                <td width="50%" class="text-center">
                    <p>{{ $date }}</p>
                    <p><strong>Penanggung Jawab</strong></p>
                    <div class="signature-line"></div>
                    <p>(_______________________)</p>
                    <p>NIP. ___________________</p>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
