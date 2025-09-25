<html>
<head>
    <title>Teacher Availabilities</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Teacher Availabilities</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Teacher</th>
                <th>Day</th>
                <th>Start</th>
                <th>End</th>
            </tr>
        </thead>
        <tbody>
            @foreach($availabilities as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->teacher_id }}</td>
                    <td>{{ $a->day_of_week }}</td>
                    <td>{{ $a->start_time }}</td>
                    <td>{{ $a->end_time }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 