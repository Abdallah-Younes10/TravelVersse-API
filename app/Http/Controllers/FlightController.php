<?php

namespace App\Http\Controllers;

use App\Models\Flight;
use Illuminate\Http\Request;

class FlightController extends Controller
{
    public function index(Request $request)
{
    $query = Flight::query();

    if ($request->has('search')) {
        $search = $request->search;
        $query->where('from_location', 'like', "%$search%")
              ->orWhere('to_location', 'like', "%$search%");
    }

    $flights = $query->paginate(8);

    return response()->json([
        'current_page' => $flights->currentPage(),
        'last_page' => $flights->lastPage(),
        'total' => $flights->total(),
        'per_page' => $flights->perPage(),
        'data' => $flights->items(),
    ]);
}


    public function store(Request $request)
    {
        $data = $request->validate([
            'style' => 'nullable|string',
            'from_location' => 'required|string',
            'departure_time' => 'required|date',
            'to_location' => 'required|string',
            'arrival_time' => 'required|date',
            'return_from' => 'nullable|string',
            'return_departure_time' => 'nullable|date',
            'return_to' => 'nullable|string',
            'return_arrival_time' => 'nullable|date',
            'stops_count' => 'nullable|integer|min:0',
            'stop_locations' => 'nullable|array',
            'duration' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'rate' => 'nullable|numeric|between:0,5',
            'booking_link' => 'nullable|url',
        ]);

        if (isset($data['stop_locations'])) {
            $data['stop_locations'] = json_encode($data['stop_locations']);
        }

        $flight = Flight::create($data);
        return response()->json($flight, 201);
    }

    public function show($id)
    {
        return Flight::findOrFail($id);
    }

    public function update(Request $request, $id)
    {
        $flight = Flight::findOrFail($id);

        $data = $request->validate([
            'style' => 'nullable|string',
            'from_location' => 'sometimes|required|string',
            'departure_time' => 'sometimes|required|date',
            'to_location' => 'sometimes|required|string',
            'arrival_time' => 'sometimes|required|date',
            'return_from' => 'nullable|string',
            'return_departure_time' => 'nullable|date',
            'return_to' => 'nullable|string',
            'return_arrival_time' => 'nullable|date',
            'stops_count' => 'nullable|integer|min:0',
            'stop_locations' => 'nullable|array',
            'duration' => 'nullable|numeric|min:0',
            'price' => 'nullable|numeric|min:0',
            'rate' => 'nullable|numeric|between:0,5',
            'booking_link' => 'nullable|url',
        ]);

        if (isset($data['stop_locations'])) {
            $data['stop_locations'] = json_encode($data['stop_locations']);
        }

        $flight->update($data);

        return response()->json($flight);
    }

    public function destroy($id)
    {
        Flight::findOrFail($id)->delete();
        return response()->json(['message' => 'Flight deleted']);
    }
}
