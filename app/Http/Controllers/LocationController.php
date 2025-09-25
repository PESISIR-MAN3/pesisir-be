<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Locations",
 *     description="API Endpoints for managing locations"
 * )
 * @OA\Schema(
 *     schema="Location",
 *     type="object",
 *     title="Location",
 *     description="Location model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="location_name", type="string", example="Panti Asuhan Sejahtera"),
 *     @OA\Property(property="location_address", type="string", example="Jl. Merpati No. 10, Jakarta"),
 *     @OA\Property(property="latitude", type="number", format="float", example="-6.200000"),
 *     @OA\Property(property="longitude", type="number", format="float", example="106.816666"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class LocationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/locations",
     *     summary="Get all locations",
     *     tags={"Locations"},
     *     @OA\Response(
     *         response=200,
     *         description="List of locations",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Location"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Location::all());
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/api/locations",
     *     summary="Create a new location",
     *     tags={"Locations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","address","latitude","longitude"},
     *             @OA\Property(property="name", type="string", example="Panti Asuhan Sejahtera"),
     *             @OA\Property(property="address", type="string", example="Jl. Merpati No. 10, Jakarta"),
     *             @OA\Property(property="latitude", type="number", format="float", example="-6.200000"),
     *             @OA\Property(property="longitude", type="number", format="float", example="106.816666")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Location created", @OA\JsonContent(ref="#/components/schemas/Location")),
     *     @OA\Response(response=422, description="Validation error")
     * )
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
     * @OA\Get(
     *     path="/api/locations/{id}",
     *     summary="Get location by ID",
     *     tags={"Locations"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Location ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Location detail", @OA\JsonContent(ref="#/components/schemas/Location")),
     *     @OA\Response(response=404, description="Location not found")
     * )
     */
    public function show(string $id)
    {
        $location = Location::findOrFail($id);
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
     * @OA\Put(
     *     path="/api/locations/{id}",
     *     summary="Update location",
     *     tags={"Locations"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Location ID", @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Panti Asuhan Damai"),
     *             @OA\Property(property="address", type="string", example="Jl. Anggrek No. 5, Bandung"),
     *             @OA\Property(property="latitude", type="number", format="float", example="-6.914744"),
     *             @OA\Property(property="longitude", type="number", format="float", example="107.609810")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Location updated", @OA\JsonContent(ref="#/components/schemas/Location")),
     *     @OA\Response(response=404, description="Location not found")
     * )
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
     * @OA\Delete(
     *     path="/api/locations/{id}",
     *     summary="Delete location",
     *     tags={"Locations"},
     *     @OA\Parameter(name="id", in="path", required=true, description="Location ID", @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Location deleted"),
     *     @OA\Response(response=404, description="Location not found")
     * )
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
