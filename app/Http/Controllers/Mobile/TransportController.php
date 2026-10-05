<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Transport\Models\Route as TransportRoute;
use Modules\Transport\Models\Vehicle;

class TransportController extends Controller
{
	public function routes(Request $request)
	{
		$user = $request->user();
		$routes = TransportRoute::with(['stops' => function ($q) {
			$q->orderBy('order');
		}])
			->where('school_id', $user->school_id)
			->orderBy('name')
			->get()
			->map(function ($route) {
				return [
					'id' => $route->id,
					'name' => $route->name,
					'code' => $route->code,
					'description' => $route->description,
					'stops' => $route->stops->map(function ($stop) {
						return [
							'id' => $stop->id,
							'name' => $stop->name,
							'order' => $stop->order,
							'pickup_time' => $stop->pickup_time,
							'drop_time' => $stop->drop_time,
							'latitude' => $stop->latitude,
							'longitude' => $stop->longitude,
						];
					}),
				];
			});

		return response()->json(['routes' => $routes]);
	}

	public function vehicles(Request $request)
	{
		$user = $request->user();
		$vehicles = Vehicle::with(['driver'])
			->where('school_id', $user->school_id)
			->orderBy('registration_number')
			->get()
			->map(function ($vehicle) {
				return [
					'id' => $vehicle->id,
					'registration_number' => $vehicle->registration_number,
					'model' => $vehicle->model,
					'capacity' => $vehicle->capacity,
					'fuel_type' => $vehicle->fuel_type,
					'year' => $vehicle->year,
					'fitness_valid_till' => $vehicle->fitness_valid_till,
					'insurance_valid_till' => $vehicle->insurance_valid_till,
					'driver' => $vehicle->driver ? [
						'id' => $vehicle->driver->id,
						'name' => $vehicle->driver->name,
						'phone' => $vehicle->driver->phone,
					] : null,
				];
			});

		return response()->json(['vehicles' => $vehicles]);
	}
}
