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
        return response()->json(Volunteer::with('activity')->get());
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
            'volunteer_name' => 'required|string',
            'volunteer_email' => 'required|email|unique:volunteers,volunteer_email',
            'volunteer_address' => 'required|string',
            'volunteer_phone' => 'required|string',
            'volunteer_gender' => 'required|string',
            'reason_desc' => 'required|string',
            'activity_id' => 'required|exists:activities,id',
        ]);

        // cek apakah volunteer sudah ikut activity lain
        $exists = Volunteer::where('email', $data['email'])->exists();
        if ($exists) {
            return response()->json(['message' => 'Volunteer already registered'], 400);
        }

        $volunteer = Volunteer::create($data);
        return response()->json($volunteer, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }
}
