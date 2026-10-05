<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Hostel\Models\Hostel;
use Modules\Hostel\Models\Room;

class HostelController extends Controller
{
	public function list(Request $request)
	{
		$user = $request->user();
		$hostels = Hostel::where('school_id', $user->school_id)
			->orderBy('name')
			->get()
			->map(function ($hostel) {
				return [
					'id' => $hostel->id,
					'name' => $hostel->name,
					'code' => $hostel->code,
					'address' => $hostel->address,
					'type' => $hostel->type,
					'capacity' => $hostel->capacity,
					'warden' => $hostel->warden_name ?? null,
				];
			});

		return response()->json(['hostels' => $hostels]);
	}

	public function details(Request $request)
	{
		$request->validate([
			'id' => 'required|integer',
		]);
		$user = $request->user();
		$hostel = Hostel::where('school_id', $user->school_id)->find($request->id);
		if (!$hostel) {
			return response()->json(['hostel' => null, 'rooms' => []]);
		}

		$rooms = Room::where('hostel_id', $hostel->id)
			->orderBy('name')
			->get()
			->map(function ($room) {
				return [
					'id' => $room->id,
					'name' => $room->name,
					'capacity' => $room->capacity,
					'floor' => $room->floor,
					'occupied' => (int) $room->occupied_beds,
				];
			});

		return response()->json([
			'hostel' => [
				'id' => $hostel->id,
				'name' => $hostel->name,
				'code' => $hostel->code,
				'address' => $hostel->address,
				'type' => $hostel->type,
				'capacity' => $hostel->capacity,
				'warden' => $hostel->warden_name ?? null,
			],
			'rooms' => $rooms,
		]);
	}
}


