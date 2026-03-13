<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Dealer Summary - {{ now()->format('d M Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12pt; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: right; }
        th { background-color: #4f46e5; color: white; text-align: center; }
        .title { text-align: center; font-size: 18pt; font-weight: bold; margin-bottom: 10px; }
        .subtitle { text-align: center; color: #666; margin-bottom: 20px; }
        .total { font-weight: bold; background-color: #f3f4f6; }
    </style>
</head>
<body>
    <div class="title">Dealer Summary Report</div>
    <div class="subtitle">Generated on: {{ now()->format('d M Y H:i') }}</div>

    <table>
        <thead>
            <tr>
                <th>Dealer Name</th>
                <th>Invoices</th>
                <th>Total Invoiced (LKR)</th>
                <th>Total Paid (LKR)</th>
                <th>Outstanding (LKR)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($summaries as $summary)
                <tr>
                    <td style="text-align: left;">{{ $summary->name }}</td>
                    <td>{{ $summary->invoice_count }}</td>
                    <td>Rs. {{ number_format($summary->total_invoiced, 2) }}</td>
                    <td>Rs. {{ number_format($summary->total_paid, 2) }}</td>
                    <td style="color: {{ $summary->total_outstanding > 0 ? 'red' : 'green' }}; font-weight: bold;">
                        Rs. {{ number_format($summary->total_outstanding, 2) }}
                    </td>
                </tr>
            @empty
                <tr><td colspan="5" style="text-align:center;">No data available</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>