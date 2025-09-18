<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Location::with('activities', 'reports')->get());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|unique:locations,location_name',
            'address' => 'required|string|unique:locations,location_address',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
        ]);

        // Simpan data ke Locations
        $location = Location::create([
            'location_name' => $data['name'],
            'location_address' => $data['address'],
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
        ]);

        return response()->json($location, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $location = Location::with(['reports', 'activities'])->findOrFail($id);

        if (!$location) {
            return response()->json([
                'message' => 'Location not found'
            ], 404);
        }

        return response()->json($location, 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $location = Location::findOrFail($id);

        $data = $request->validate([
            'name'      => 'sometimes|required|string|unique:locations,location_name,' . $id,
            'address'   => 'sometimes|required|string|unique:locations,location_address,' . $id,
            'latitude'  => 'sometimes|required|numeric|between:-90,90',
            'longitude' => 'sometimes|required|numeric|between:-180,180',
        ]);

        $location->update([
            'location_name'    => $data['name']      ?? $location->location_name,
            'location_address' => $data['address']   ?? $location->location_address,
            'latitude'         => $data['latitude']  ?? $location->latitude,
            'longitude'        => $data['longitude'] ?? $location->longitude,
        ]);

        return response()->json($location->refresh(), 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $location = Location::find($id);

        if (!$location) {
            return response()->json([
                'message' => 'Location not found'
            ], 404);
        }

        $location->delete();

        return response()->json([
            'message' => 'Location deleted successfully'
        ], 200);
    }
}
