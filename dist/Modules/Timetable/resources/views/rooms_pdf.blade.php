<html>
<head>
    <title>Rooms</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 8px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h1>Rooms</h1>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Capacity</th>
                <th>Location</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rooms as $room)
                <tr>
                    <td>{{ $room->id }}</td>
                    <td>{{ $room->name }}</td>
                    <td>{{ $room->capacity }}</td>
                    <td>{{ $room->location }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 