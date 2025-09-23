<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Complaint;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Complaints",
 *     description="API Endpoints for managing complaints"
 * )
 */
class ComplaintController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/complaints",
     *     tags={"Complaints"},
     *     summary="Get list of complaints",
     *     @OA\Response(
     *         response=200,
     *         description="List of complaints with location"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/complaints",
     *     tags={"Complaints"},
     *     summary="Create a new complaint",
     *     description="Store a newly created complaint with location and image",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","address","phone","desc","date","image","loc_name","loc_address","lat","long"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", format="email", example="john@example.com"),
     *             @OA\Property(property="address", type="string", example="Jl. Sudirman No. 123"),
     *             @OA\Property(property="phone", type="string", example="08123456789"),
     *             @OA\Property(property="desc", type="string", example="Illegal trash dumping"),
     *             @OA\Property(property="date", type="string", format="date", example="2025-09-23"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="loc_name", type="string", example="Kelurahan A"),
     *             @OA\Property(property="loc_address", type="string", example="Jl. Kenanga, RT 01 RW 02"),
     *             @OA\Property(property="lat", type="number", format="float", example=-7.8014),
     *             @OA\Property(property="long", type="number", format="float", example=110.3647)
     *         )
     *     ),
     *     @OA\Response(response=201, description="Complaint created"),
     *     @OA\Response(response=422, description="Validation error")
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'email' => 'required|email',
            'address' => 'required|string',
            'phone' => 'required|string',
            'desc' => 'required|string',
            'date' => 'required|date',
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
            'actual_date'         => $data['date'],
            'image_path'          => $path,
            'location_id'         => $location->id,
        ]);

        return response()->json($complaint->load('location'), 201);
    }

     /**
     * @OA\Get(
     *     path="/api/complaints/{id}",
     *     tags={"Complaints"},
     *     summary="Get a single complaint",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Complaint ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Complaint details"),
     *     @OA\Response(response=404, description="Complaint not found")
     * )
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
     * @OA\Delete(
     *     path="/api/complaints/{id}",
     *     tags={"Complaints"},
     *     summary="Delete a complaint",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Complaint ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Complaint deleted"),
     *     @OA\Response(response=404, description="Complaint not found")
     * )
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
