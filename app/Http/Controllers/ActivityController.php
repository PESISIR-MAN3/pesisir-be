<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Location;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="API Pesisir Project",
 *     version="1.0.0",
 *     description="API Documentation for Pesisir"
 * )
 * 
 * @OA\Schema(
 *     schema="Activity",
 *     type="object",
 *     title="Activity",
 *     description="Activity model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="activity_name", type="string", example="Beach Cleanup"),
 *     @OA\Property(property="activity_desc", type="string", example="A volunteer activity to clean up the beach."),
 *     @OA\Property(property="activity_date", type="string", format="date", example="2025-10-10"),
 *     @OA\Property(property="activity_time", type="string", example="14:00"),
 *     @OA\Property(property="activity_status", type="string", example="open"),
 *     @OA\Property(property="activity_fee", type="number", format="float", example=50000),
 *     @OA\Property(property="location_id", type="integer", example=3),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-23T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-23T12:00:00Z")
 * )
 *
 * @OA\Tag(
 *     name="Activities",
 *     description="API Endpoints for managing activities"
 * )
 */
class ActivityController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/activities",
     *     tags={"Activities"},
     *     summary="Get list of activities",
     *     @OA\Response(
     *         response=200,
     *         description="List of activities with locations and volunteers"
     *     )
     * )
     */
    public function index()
    {
        return response()->json(Activity::with('locations', 'volunteers')->get());
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
     *     path="/api/activities",
     *     tags={"Activities"},
     *     summary="Create a new activity",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","date","time","status","fee","loc_name","loc_address","lat","long"},
     *             @OA\Property(property="name", type="string", example="Beach Cleanup"),
     *             @OA\Property(property="desc", type="string", example="Cleaning the beach area"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-10-01"),
     *             @OA\Property(property="time", type="string", format="time", example="08:00"),
     *             @OA\Property(property="status", type="string", enum={"ongoing","done","upcoming"}, example="upcoming"),
     *             @OA\Property(property="fee", type="integer", example=20000),
     *             @OA\Property(property="loc_name", type="string", example="Pantai Parangtritis"),
     *             @OA\Property(property="loc_address", type="string", example="Jl. Pantai Selatan, Bantul"),
     *             @OA\Property(property="lat", type="number", format="float", example=-7.9778),
     *             @OA\Property(property="long", type="number", format="float", example=110.3695)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Activity created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name'       => 'required|string|unique:activities,activity_name',
            'desc'       => 'nullable|string',
            'date'       => 'required|date',
            'time'       => 'required|date_format:H:i',
            // 'image'  => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'fee'        => 'required|integer|min:10000',
            'loc_name'   => 'required|string',
            'loc_address'=> 'required|string',
            'lat'        => 'required|numeric|between:-90,90',
            'long'       => 'required|numeric|between:-180,180',
        ]);

        // Cek lokasi, buat baru kalau belum ada
        $location = Location::where('location_name', $data['loc_name'])
            ->where('location_address', $data['loc_address'])
            ->first();

        if (!$location) {
            $location = Location::create([
                'location_name'    => $data['loc_name'],
                'location_address' => $data['loc_address'],
                'latitude'         => $data['lat'],
                'longitude'        => $data['long']
            ]);
        }

        // Upload image
        // $path = $request->file('image')->store('activities', 'public');

        // Tentukan status otomatis berdasarkan tanggal
        $today = now()->toDateString();

        $status = match (true) {
            $data['date'] < $today => 'done',
            $data['date'] == $today => 'ongoing',
            default => 'upcoming'
        };

        // Buat activity
        $activity = Activity::create([
            'activity_name'   => $data['name'],
            'activity_desc'   => $data['desc'],
            'activity_date'   => $data['date'],
            'activity_time'   => $data['time'],
            'activity_status' => $status,
            // 'image_path'     => $path,
            'activity_fee'    => $data['fee'],
            'location_id'     => $location->id
        ]);

        return response()->json($activity->load(['location']), 201);
    }


    /**
     * @OA\Get(
     *     path="/api/activities/{id}",
     *     tags={"Activities"},
     *     summary="Get a single activity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Activity ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Activity details"),
     *     @OA\Response(response=404, description="Activity not found")
     * )
     */
    public function show(string $id)
    {
        $activity = Activity::with(['location', 'volunteers'])->findOrFail($id);
        return response()->json($activity);
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
     *     path="/api/activities/{id}",
     *     tags={"Activities"},
     *     summary="Update an activity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Activity ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="Updated Activity"),
     *             @OA\Property(property="desc", type="string", example="Updated description"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-10-10"),
     *             @OA\Property(property="time", type="string", format="time", example="10:00"),
     *             @OA\Property(property="status", type="string", enum={"ongoing","done","upcoming"}, example="ongoing"),
     *             @OA\Property(property="fee", type="integer", example=50000),
     *             @OA\Property(property="loc_name", type="string", example="New Location"),
     *             @OA\Property(property="loc_address", type="string", example="Jl. Malioboro, Yogyakarta"),
     *             @OA\Property(property="lat", type="number", format="float", example=-7.8014),
     *             @OA\Property(property="long", type="number", format="float", example=110.3647)
     *         )
     *     ),
     *     @OA\Response(response=200, description="Activity updated"),
     *     @OA\Response(response=404, description="Activity not found"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::findOrFail($id);

        $data = $request->validate([
            'name'   => 'sometimes|required|string|unique:activities,name,' . $activity->id,
            'desc'   => 'sometimes|nullable|string',
            'date'   => 'sometimes|required|date',
            'time'   => 'sometimes|required|date_format:H:i',
            // 'image'  => 'sometimes|nullable|file|mimes:jpg,jpeg,png|max:10240',
            'fee'    => 'sometimes|required|integer|min:0',
            'loc_name' => 'sometimes|required|string',
            'loc_address' => 'sometimes|required|string',
            'lat' => 'sometimes|required|numeric|between:-90,90',
            'long' => 'sometimes|required|numeric|between:-180,180',
        ]);

        // Cek lokasi, buat baru kalau belum ada
        $location = null;
        if (isset($data['loc_name'], $data['loc_address'])) {
            $location = Location::where('location_name', $data['loc_name'])
                ->where('location_address', $data['loc_address'])
                ->first();

            if (!$location) {
                $location = Location::create([
                    'location_name'    => $data['loc_name'],
                    'location_address' => $data['loc_address'],
                    'latitude'         => $data['lat'] ?? 0,
                    'longitude'        => $data['long'] ?? 0
                ]);
            }
        }

        // Tentukan status otomatis jika ada perubahan tanggal
        $status = $activity->activity_status;
        if (isset($data['date'])) {
            $today = now()->toDateString();

            $status = match (true) {
                $data['date'] < $today => 'done',
                $data['date'] == $today => 'ongoing',
                default => 'upcoming'
            };
        }

        $activity->update([
            'activity_name'   => $data['name']   ?? $activity->activity_name,
            'activity_desc'   => $data['desc']   ?? $activity->activity_desc,
            'activity_date'   => $data['date']   ?? $activity->activity_date,
            'activity_time'   => $data['time']   ?? $activity->activity_time,
            'activity_status' => $status,
            'activity_fee'    => $data['fee']    ?? $activity->activity_fee,
            'location_id'     => $location->id   ?? $activity->location_id,
            // 'image_path'      => $request->hasFile('image')
            //                         ? $request->file('image')->store('activities', 'public')
            //                         : $activity->image_path,
        ]);

        return response()->json($activity->load(['locations', 'volunteers']));
    }

 /**
     * @OA\Delete(
     *     path="/api/activities/{id}",
     *     tags={"Activities"},
     *     summary="Delete an activity",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Activity ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Activity deleted"),
     *     @OA\Response(response=404, description="Activity not found")
     * )
     */
    public function destroy(string $id)
    {
        $activity = Activity::find($id);

        if (!$activity) {
            return response()->json([
                'message' => 'Activity not found'
            ], 404);
        }

        // Hapus file image_path kalau ada
        // if ($activity->image_path && \Storage::disk('public')->exists($activity->image_path)) {
        //     \Storage::disk('public')->delete($activity->image_path);
        // }

        $activity->delete();

        return response()->json([
            'message' => 'Activity deleted successfully'
        ], 200);
    }
}
