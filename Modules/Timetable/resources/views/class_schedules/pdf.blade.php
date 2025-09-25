<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Class Schedules Report</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        .day-header {
            background-color: #e9ecef;
            font-weight: bold;
            text-align: center;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Class Schedules Report</h1>
        <p>Generated on: {{ now()->format('F d, Y \a\t g:i A') }}</p>
        <p>Total Schedules: {{ $schedules->count() }}</p>
    </div>

    @if($schedules->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Day</th>
                    <th>Time</th>
                    <th>Class</th>
                    <th>Subject</th>
                    <th>Teacher</th>
                    <th>Room</th>
                    <th>Duration</th>
                </tr>
            </thead>
            <tbody>
                @foreach($schedules as $schedule)
                    <tr>
                        <td>{{ ucfirst($schedule->day_of_week) }}</td>
                        <td>{{ \Carbon\Carbon::parse($schedule->start_time)->format('H:i') }} - {{ \Carbon\Carbon::parse($schedule->end_time)->format('H:i') }}</td>
                        <td>{{ $schedule->academicClass->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->subject ?? 'N/A' }}</td>
                        <td>{{ $schedule->teacher->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->room->name ?? 'N/A' }}</td>
                        <td>{{ $schedule->duration ?? 'N/A' }} minutes</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            <p>No class schedules found matching the selected criteria.</p>
        </div>
    @endif

    <div class="footer">
        <p>This report was generated automatically by the Dunco School Management System.</p>
        <p>For any questions, please contact the administration.</p>
    </div>
</body>
</html> 