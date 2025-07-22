<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Timetable\Models\Room;
use Modules\Timetable\Http\Requests\RoomRequest;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomController extends Controller
{
    // Blade CRUD methods
    public function index()
    {
        $rooms = Room::paginate(15);
        return view('timetable::rooms.index', compact('rooms'));
    }

    public function create()
    {
        return view('Timetable::rooms.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'availability_time' => 'nullable|string',
        ]);
        Room::create($data);
        return redirect()->route('rooms.index')->with('success', 'Room created successfully.');
    }

    public function show($id)
    {
        $room = Room::findOrFail($id);
        return view('Timetable::rooms.show', compact('room'));
    }

    public function edit($id)
    {
        $room = Room::findOrFail($id);
        return view('Timetable::rooms.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $room = Room::findOrFail($id);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'equipment' => 'nullable|string|max:255',
            'availability_time' => 'nullable|string',
        ]);
        $room->update($data);
        return redirect()->route('rooms.index')->with('success', 'Room updated successfully.');
    }

    public function destroy($id)
    {
        $room = Room::findOrFail($id);
        $room->delete();
        return redirect()->route('rooms.index')->with('success', 'Room deleted successfully.');
    }

    public function exportCsv()
    {
        $rooms = Room::all();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="rooms.csv"',
        ];
        $columns = ['id', 'name', 'capacity', 'location'];
        $callback = function() use ($rooms, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($rooms as $room) {
                fputcsv($file, [
                    $room->id,
                    $room->name,
                    $room->capacity,
                    $room->location,
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        $rooms = Room::all();
        $pdf = Pdf::loadView('Timetable::rooms_pdf', compact('rooms'));
        return $pdf->download('rooms.pdf');
    }
} 