<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Report;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Report::with('location')->get());
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
            'image' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'loc_name' => 'required|string',
            'loc_address' => 'required|string',
        ]);

        // Upload image
        $path = $request->file('image')->store('reports', 'public');

        // Cek apakah lokasi sudah ada
        $location = Location::where('location_name', $data['loc_name'])
            ->where('location_address', $data['loc_address'])
            ->first();

        if (!$location) {
            // Generate random lat/lon (dummy sementara)
            $lat = -90 + mt_rand() / mt_getrandmax() * 180;
            $lon = -180 + mt_rand() / mt_getrandmax() * 360;

            // Simpan lokasi baru
            $location = Location::create([
                'location_name'    => $data['loc_name'],
                'location_address' => $data['loc_address'],
                'latitude'         => $lat,
                'longitude'        => $lon,
            ]);
        }

        // Simpan report
        $report = Report::create([
            'reporter_name'    => $data['name'],
            'reporter_email'   => $data['email'],
            'reporter_address' => $data['address'],
            'reporter_phone'   => $data['phone'],
            'report_desc'      => $data['desc'],
            'image_path'       => $path,
            'location_id'      => $location->id,
        ]);

        return response()->json($report->load('location'), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $report = Report::with(['location'])->findOrFail($id);
        return response()->json($report);
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
        $report = Report::find($id);

        if (!$report) {
            return response()->json([
                'message' => 'Report not found'
            ], 404);
        }

        // Hapus file image_path kalau ada
        if ($report->image_path && \Storage::disk('public')->exists($report->image_path)) {
            \Storage::disk('public')->delete($report->image_path);
        }

        $report->delete();

        return response()->json([
            'message' => 'Report deleted successfully'
        ], 200);
    }
}
