<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Complaint;
use Illuminate\Http\Request;

class complaintController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Complaint::with('location')->get());
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
            'desc' => 'required|string',
            'complaint_date' => 'required|date',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'loc_name' => 'required|string',
            'loc_address' => 'required|string',
            'lat' => 'required|numeric|between:-90,90',
            'long' => 'required|numeric|between:-180,180',
        ]);

        // Upload image
        $path = $request->file('image')->store('complaints', 'public');

        // Cek apakah lokasi sudah ada
        $location = Location::where('location_name', $data['loc_name'])
            ->where('location_address', $data['loc_address'])
            ->first();

        if (!$location) {
            // Simpan lokasi baru
            $location = Location::create([
                'location_name'    => $data['loc_name'],
                'location_address' => $data['loc_address'],
                'latitude'         => $data['lat'],
                'longitude'        => $data['lot']
            ]);
        }

        // Simpan complaint
        $complaint = complaint::create([
            'complainant_name'    => $data['name'],
            'complainant_email'   => $data['email'],
            'complainant_address' => $data['address'],
            'complainant_phone'   => $data['phone'],
            'complaint_desc'      => $data['desc'],
            'actual_date'      => $data['date'],
            'image_path'       => $path,
            'location_id'      => $location->id,
        ]);

        return response()->json($complaint->load('location'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $complaint = complaint::with(['location'])->findOrFail($id);
        return response()->json($complaint);
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
        $complaint = complaint::find($id);

        if (!$complaint) {
            return response()->json([
                'message' => 'Complaint not found'
            ], 404);
        }

        // Hapus file image_path kalau ada
        if ($complaint->image_path && \Storage::disk('public')->exists($complaint->image_path)) {
            \Storage::disk('public')->delete($complaint->image_path);
        }

        $complaint->delete();

        return response()->json([
            'message' => 'Complaint deleted successfully'
        ], 200);
    }
}
