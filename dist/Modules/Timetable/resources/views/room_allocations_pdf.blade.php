<html>
<head>
    <title>Room Allocations</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Room Allocations</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Room</th>
                <th>Class Schedule</th>
                <th>Allocation Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($allocations as $a)
                <tr>
                    <td>{{ $a->id }}</td>
                    <td>{{ $a->room_id }}</td>
                    <td>{{ $a->class_schedule_id }}</td>
                    <td>{{ $a->allocation_date }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 