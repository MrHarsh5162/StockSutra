<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Stock Report - {{ $date }}</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #333;
            margin: 0;
            padding: 20px;
            font-size: 11px;
        }
        .header {
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            text-transform: uppercase;
        }
        .header p {
            margin: 2px 0;
            color: #666;
            font-size: 10px;
        }
        .report-info {
            float: right;
            text-align: right;
        }
        .report-info h2 {
            margin: 0;
            font-size: 16px;
        }
        .report-info p {
            margin: 2px 0;
            font-family: monospace;
        }
        .clear {
            clear: both;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #f2f2f2;
            text-align: left;
            padding: 8px;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }
        td {
            padding: 8px;
            border-bottom: 1px solid #eee;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .bold { font-weight: bold; }
        .footer {
            margin-top: 50px;
        }
        .signature-grid {
            width: 100%;
            margin-top: 40px;
        }
        .signature-box {
            width: 45%;
            float: left;
            border-top: 1px solid #000;
            text-align: center;
            padding-top: 5px;
            font-size: 9px;
            text-transform: uppercase;
        }
        .system-footer {
            margin-top: 100px;
            border-top: 1px solid #eee;
            padding-top: 10px;
            font-style: italic;
            color: #999;
            font-size: 8px;
        }
    </style>
</head>
<body>
    <div class="header">
        <div class="report-info">
            <h2>STOCK REPORT</h2>
            <p>{{ $date }}</p>
            <p>Category: {{ $category_name }}</p>
        </div>
        <h1>STOCK SUTRA</h1>
        <p>Smart Inventory Management System</p>
    </div>
    <div class="clear"></div>

    <table style="margin-top: 30px;">
        <thead>
            <tr>
                <th width="35%">ITEM NAME</th>
                <th class="text-center">OPENING</th>
                <th class="text-center">TOTAL</th>
                <th class="text-center">USED</th>
                <th class="text-center">CLOSING</th>
                <th class="text-right">UNIT</th>
            </tr>
        </thead>
        <tbody>
            @foreach($reportData as $row)
            <tr>
                <td class="bold">{{ $row->name }}</td>
                <td class="text-center">{{ (float)$row->starting }}</td>
                <td class="text-center" style="background-color: #fafafa;">{{ (float)$row->total }}</td>
                <td class="text-center">{{ (float)$row->consumed }}</td>
                <td class="text-center bold" style="color: #65a30d;">{{ (float)$row->closing }}</td>
                <td class="text-right">{{ $row->unit }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <div class="signature-grid">
            <div class="signature-box">Store Manager Signature</div>
            <div class="signature-box" style="float: right;">Authorized Signatory</div>
        </div>
        <div class="clear"></div>
        <div class="system-footer">
            Generated on: {{ date('d M Y, h:i A') }} | Ref: {{ time() }} | Stock Sutra ERP
        </div>
    </div>
</body>
</html>
