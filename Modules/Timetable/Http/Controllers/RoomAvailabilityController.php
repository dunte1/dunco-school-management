<?php

namespace Modules\Timetable\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Schema;
use App\Models\Modules\Timetable\Models\RoomAvailability;
use Modules\Timetable\Models\Room;
use Illuminate\Support\Facades\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class RoomAvailabilityController extends Controller
{
    public function index()
    {
        try {
            if (Schema::hasTable('room_availabilities')) {
                $availabilities = RoomAvailability::with('room')->paginate(15);
            } else {
                $availabilities = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            }
        } catch (\Exception $e) {
            $availabilities = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
        }
        
        return view('timetable::room_availabilities.index', compact('availabilities'));
    }

    public function create()
    {
        try {
            if (Schema::hasTable('rooms')) {
                $rooms = Room::all();
            } else {
                $rooms = collect();
            }
        } catch (\Exception $e) {
            $rooms = collect();
        }
        
        return view('timetable::room_availabilities.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        try {
            if (!Schema::hasTable('room_availabilities')) {
                return redirect()->route('room_availabilities.index')->with('error', 'Room availabilities table does not exist.');
            }
            
            $data = $request->validate([
                'room_id' => 'required|integer|exists:rooms,id',
                'day_of_week' => 'required|string',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            // Check for overlapping availabilities
            $overlap = RoomAvailability::where('room_id', $data['room_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where(function($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function($q2) use ($data) {
                          $q2->where('start_time', '<=', $data['start_time'])
                             ->where('end_time', '>=', $data['end_time']);
                      });
                })->exists();

            if ($overlap) {
                return back()->withInput()->withErrors(['room_id' => 'This room already has availability for ' . $data['day_of_week'] . ' at this time.']);
            }

            RoomAvailability::create($data);
            return redirect()->route('room_availabilities.index')->with('success', 'Room availability created successfully.');
        } catch (\Exception $e) {
            return redirect()->route('room_availabilities.index')->with('error', 'Failed to create room availability: ' . $e->getMessage());
        }
    }

    public function show($id)
    {
        try {
            if (Schema::hasTable('room_availabilities')) {
                $availability = RoomAvailability::with('room')->findOrFail($id);
            } else {
                return redirect()->route('room_availabilities.index')->with('error', 'Room availabilities table does not exist.');
            }
        } catch (\Exception $e) {
            return redirect()->route('room_availabilities.index')->with('error', 'Room availability not found.');
        }
        
        return view('timetable::room_availabilities.show', compact('availability'));
    }

    public function edit($id)
    {
        try {
            if (Schema::hasTable('room_availabilities')) {
                $availability = RoomAvailability::findOrFail($id);
            } else {
                return redirect()->route('room_availabilities.index')->with('error', 'Room availabilities table does not exist.');
            }

            if (Schema::hasTable('rooms')) {
                $rooms = Room::all();
            } else {
                $rooms = collect();
            }
        } catch (\Exception $e) {
            return redirect()->route('room_availabilities.index')->with('error', 'Room availability not found.');
        }
        
        return view('timetable::room_availabilities.edit', compact('availability', 'rooms'));
    }

    public function update(Request $request, $id)
    {
        try {
            if (!Schema::hasTable('room_availabilities')) {
                return redirect()->route('room_availabilities.index')->with('error', 'Room availabilities table does not exist.');
            }
            
            $availability = RoomAvailability::findOrFail($id);
            $data = $request->validate([
                'room_id' => 'required|integer|exists:rooms,id',
                'day_of_week' => 'required|string',
                'start_time' => 'required',
                'end_time' => 'required',
            ]);

            // Check for overlapping availabilities (excluding current record)
            $overlap = RoomAvailability::where('room_id', $data['room_id'])
                ->where('day_of_week', $data['day_of_week'])
                ->where('id', '!=', $id)
                ->where(function($q) use ($data) {
                    $q->whereBetween('start_time', [$data['start_time'], $data['end_time']])
                      ->orWhereBetween('end_time', [$data['start_time'], $data['end_time']])
                      ->orWhere(function($q2) use ($data) {
                          $q2->where('start_time', '<=', $data['start_time'])
                             ->where('end_time', '>=', $data['end_time']);
                      });
                })->exists();

            if ($overlap) {
                return back()->withInput()->withErrors(['room_id' => 'This room already has availability for ' . $data['day_of_week'] . ' at this time.']);
            }

            $availability->update($data);
            return redirect()->route('room_availabilities.index')->with('success', 'Room availability updated successfully.');
        } catch (\Exception $e) {
            return redirect()->route('room_availabilities.index')->with('error', 'Failed to update room availability: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            if (!Schema::hasTable('room_availabilities')) {
                return redirect()->route('room_availabilities.index')->with('error', 'Room availabilities table does not exist.');
            }
            
            $availability = RoomAvailability::findOrFail($id);
            $availability->delete();
            return redirect()->route('room_availabilities.index')->with('success', 'Room availability deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('room_availabilities.index')->with('error', 'Failed to delete room availability: ' . $e->getMessage());
        }
    }

    public function exportCsv()
    {
        try {
            if (Schema::hasTable('room_availabilities')) {
                $availabilities = RoomAvailability::with('room')->get();
            } else {
                $availabilities = collect();
            }
        } catch (\Exception $e) {
            $availabilities = collect();
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="room_availabilities.csv"',
        ];
        $columns = ['id', 'room_name', 'day_of_week', 'start_time', 'end_time'];
        $callback = function() use ($availabilities, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($availabilities as $availability) {
                fputcsv($file, [
                    $availability->id,
                    $availability->room->name ?? $availability->room_id,
                    $availability->day_of_week,
                    $availability->start_time,
                    $availability->end_time,
                ]);
            }
            fclose($file);
        };
        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf()
    {
        try {
            if (Schema::hasTable('room_availabilities')) {
                $availabilities = RoomAvailability::with('room')->get();
            } else {
                $availabilities = collect();
            }
        } catch (\Exception $e) {
            $availabilities = collect();
        }

        $pdf = Pdf::loadView('timetable::room_availabilities_pdf', compact('availabilities'));
        return $pdf->download('room_availabilities.pdf');
    }
}
