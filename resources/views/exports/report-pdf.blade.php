<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Helvetica', 'Arial', sans-serif; font-size: 11px; color: #1e293b; line-height: 1.5; }
        .header { padding: 20px 30px; border-bottom: 3px solid #7c3aed; margin-bottom: 20px; }
        .header h1 { font-size: 20px; color: #7c3aed; font-weight: 700; margin-bottom: 4px; }
        .header .meta { font-size: 10px; color: #64748b; }
        .header .meta span { margin-right: 20px; }
        .content { padding: 0 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        thead th { background: #f8fafc; border-bottom: 2px solid #e2e8f0; padding: 8px 10px; text-align: left; font-size: 9px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; color: #64748b; }
        tbody td { padding: 7px 10px; border-bottom: 1px solid #f1f5f9; font-size: 10px; }
        tbody tr:nth-child(even) { background: #fafafa; }
        tfoot td { padding: 8px 10px; border-top: 2px solid #e2e8f0; font-weight: 700; font-size: 10px; background: #f8fafc; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .footer { margin-top: 30px; padding: 15px 30px; border-top: 1px solid #e2e8f0; font-size: 9px; color: #94a3b8; text-align: center; }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="meta">
            <span>Period: {{ $startDate ?? 'N/A' }} — {{ $endDate ?? 'N/A' }}</span>
            <span>Generated: {{ $generatedAt }}</span>
        </div>
    </div>

    <div class="content">
        <table>
            <thead>
                <tr>
                    @foreach($headers as $header)
                        <th>{{ $header }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($rows as $row)
                    <tr>
                        @foreach($row as $cell)
                            <td>{{ $cell }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
            @if(!empty($totals))
            <tfoot>
                <tr>
                    @foreach($totals as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            </tfoot>
            @endif
        </table>
    </div>

    <div class="footer">
        POS System — Report generated automatically. All values in LKR.
    </div>
</body>
</html>
