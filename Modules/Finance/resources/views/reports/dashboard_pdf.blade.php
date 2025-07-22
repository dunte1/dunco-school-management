<html>
<head>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .summary-table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .summary-table th, .summary-table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .summary-table th { background: #f0f0f0; }
        h2 { color: #2a5d9f; }
    </style>
</head>
<body>
    <h2>Finance Reports Dashboard</h2>
    <table class="summary-table">
        <tr>
            <th>Total Fees Collected</th>
            <td>KES {{ number_format($totalFeesCollected, 2) }}</td>
        </tr>
        <tr>
            <th>Outstanding Balances</th>
            <td>KES {{ number_format($outstandingBalances, 2) }}</td>
        </tr>
        <tr>
            <th>Income</th>
            <td>KES {{ number_format($income, 2) }}</td>
        </tr>
        <tr>
            <th>Expenses</th>
            <td>KES {{ number_format($expenses, 2) }}</td>
        </tr>
    </table>
    <h3>Fee Collection Trend (Last 6 Months)</h3>
    <table class="summary-table">
        <tr>
            <th>Month</th>
            <th>Amount</th>
        </tr>
        @foreach($feeTrend as $month => $total)
        <tr>
            <td>{{ $month }}</td>
            <td>KES {{ number_format($total, 2) }}</td>
        </tr>
        @endforeach
    </table>
    <h3>Outstanding Balances Breakdown</h3>
    <table class="summary-table">
        <tr>
            <th>Student ID</th>
            <th>Outstanding Amount</th>
        </tr>
        @foreach($outstandingByClass as $studentId => $total)
        <tr>
            <td>{{ $studentId }}</td>
            <td>KES {{ number_format($total, 2) }}</td>
        </tr>
        @endforeach
    </table>
    <p style="font-size: 12px; color: #888;">Generated on {{ now()->toDateTimeString() }}</p>
</body>
</html> 