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
            'activity_name' => 'required|string',
            'activity_desc' => 'nullable|string',
            'activity_date' => 'required|date',
            'location_id' => 'required|exists:locations,id',
        ]);

        $activity = Activity::create($data);
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
            'activity_name' => 'sometimes|required|string',
            'activity_desc' => 'nullable|string',
            'activity_date' => 'sometimes|required|date',
            'location_id' => 'sometimes|required|exists:locations,id',
        ]);

        $activity->update($data);

        return response()->json($activity->load(['location', 'volunteers']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $activity = Activity::findOrFail($id);
        $activity->delete();
        return response()->json(['message' => 'Activity deleted']);
    }
}
