<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Result Slip</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #999; padding: 6px; }
        th { background: #eee; }
    </style>
    </head>
<body>
    <div class="header">
        <h2>Result Slip</h2>
        <div>{{ $result['exam']['name'] }} - {{ $result['exam']['term'] }} {{ $result['exam']['year'] }}</div>
        <div>Student: {{ $result['student']['name'] }} ({{ $result['student']['adm'] }})</div>
    </div>
    <table>
        <thead>
            <tr>
                <th>Subject</th>
                <th>Score</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            @foreach($result['subjects'] as $row)
            <tr>
                <td>{{ $row['name'] }}</td>
                <td style="text-align:right">{{ $row['score'] }}</td>
                <td style="text-align:center">{{ $row['grade'] }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th>Total</th>
                <th style="text-align:right">{{ $result['total'] }}</th>
                <th></th>
            </tr>
            <tr>
                <th>Average</th>
                <th style="text-align:right">{{ $result['average'] }}%</th>
                <th>Pos: {{ $result['position'] }}</th>
            </tr>
        </tfoot>
    </table>
</body>
</html>


