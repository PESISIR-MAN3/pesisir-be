<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Activity::with('location', 'volunteers')->get());
    }

    public function volunteer($id)
    {
        $activity = Activity::with('volunteers')->findOrFail($id);
        return response()->json($activity->volunteers);
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
            'name'   => 'required|string|unique:activities,activity_name',
            'desc'   => 'nullable|string',
            'date'   => 'required|date',
            'time'   => 'required|date_format:H:i',
            'status' => 'required|string|in:ongoing,done,upcoming',
            'image'  => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'fee'    => 'required|integer|min:0',
            'loc_id' => 'required|exists:locations,id',
        ]);

        // Upload image
        $path = $request->file('image')->store('activities', 'public');

        $activity = Activity::create([
            'activity_name' => $data['name'],
            'activity_desc' => $data['desc'],
            'activity_date' => $data['date'],
            'activity_time' => $data['time'],
            'activity_status' => $data['status'],
            'image_path' => $path,
            'activity_fee' => $data['fee'],
            'location_id' => $data['loc_id']
        ]);
        return response()->json($activity->load(['location']), 201);
    }

    /**
     * Display the specified resource.
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $activity = Activity::findOrFail($id);

        $data = $request->validate([
            'name'   => 'sometimes|required|string|unique:activities,name,' . $activity->id,
            'desc'   => 'sometimes|nullable|string',
            'date'   => 'sometimes|required|date',
            'time'   => 'sometimes|required|date_format:H:i',
            'status' => 'sometimes|required|string|in:ongoing,done,upcoming',
            'image'  => 'sometimes|nullable|file|mimes:jpg,jpeg,png|max:10240',
            'fee'    => 'sometimes|required|integer|min:0',
            'loc_id' => 'sometimes|required|exists:locations,id',
        ]);

         $activity->update([
            'activity_name'   => $data['name']   ?? $activity->activity_name,
            'activity_desc'   => $data['desc']   ?? $activity->activity_desc,
            'activity_date'   => $data['date']   ?? $activity->activity_date,
            'activity_time'   => $data['time']   ?? $activity->activity_time,
            'activity_status' => $data['status'] ?? $activity->activity_status,
            'activity_fee'    => $data['fee']    ?? $activity->activity_fee,
            'location_id'     => $data['loc_id'] ?? $activity->location_id,
            'image_path'      => $request->hasFile('image')
                                    ? $request->file('image')->store('activities', 'public')
                                    : $activity->image_path,
        ]);

        return response()->json($activity->load(['location', 'volunteers']));
    }

    /**
     * Remove the specified resource from storage.
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
        if ($activity->image_path && \Storage::disk('public')->exists($activity->image_path)) {
            \Storage::disk('public')->delete($activity->image_path);
        }

        $activity->delete();

        return response()->json([
            'message' => 'Activity deleted successfully'
        ], 200);
    }
}
