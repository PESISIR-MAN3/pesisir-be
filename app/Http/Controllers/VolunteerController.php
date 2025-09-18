<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

class VolunteerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Volunteer::all());
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
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'gender' => 'required|string',
            'reason_desc' => 'required|string',
            'payment_method' => 'required|string',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'act_id' => 'required|exists:activities,id',
        ]);

        // Upload image
        $path = $request->file('image')->store('volunteers', 'public');

        $volunteer = Volunteer::create([
            'volunteer_name' => $data['name'],
            'volunteer_email' => $data['email'],
            'volunteer_address' => $data['address'],
            'volunteer_phone' => $data['phone'],
            'volunteer_gender' => $data['gender'],
            'reason_desc' => $data['reason_desc'],
            'payment_method' => $data['payment_method'],
            'image_slip' => $path,
            'activity_id' => $data['act_id']
        ]);
        return response()->json($volunteer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $volunteer = Volunteer::with(['activity'])->findOrFail($id);
        return response()->json($volunteer);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $volunteer = Volunteer::find($id);

        if (!$volunteer) {
            return response()->json([
                'message' => 'Volunteer not found'
            ], 404);
        }

        // Hapus file image_slip kalau ada
        if ($volunteer->image_slip && \Storage::disk('public')->exists($volunteer->image_slip)) {
            \Storage::disk('public')->delete($volunteer->image_slip);
        }

        $volunteer->delete();

        return response()->json([
            'message' => 'Volunteer deleted successfully'
        ], 200);
    }
}
