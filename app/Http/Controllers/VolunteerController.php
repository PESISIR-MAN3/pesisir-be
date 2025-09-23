<?php

namespace App\Http\Controllers;

use App\Models\Volunteer;
use Illuminate\Http\Request;

/**
 * 
 * @OA\Schema(
 *     schema="Volunteer",
 *     type="object",
 *     title="Volunteer",
 *     description="Volunteer model",
 *     @OA\Property(property="id", type="integer", example=10),
 *     @OA\Property(property="volunteer_name", type="string", example="John Doe"),
 *     @OA\Property(property="volunteer_email", type="string", format="email", example="johndoe@example.com"),
 *     @OA\Property(property="volunteer_address", type="string", example="Jl. Merdeka No. 10, Jakarta"),
 *     @OA\Property(property="volunteer_phone", type="string", example="+628123456789"),
 *     @OA\Property(property="volunteer_gender", type="string", example="male"),
 *     @OA\Property(property="reason_desc", type="string", example="I want to help the community"),
 *     @OA\Property(property="payment_method", type="string", example="Bank Transfer"),
 *     @OA\Property(property="image_slip", type="string", example="volunteers/12345.jpg"),
 *     @OA\Property(property="activity_id", type="integer", example=5),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-23T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-23T12:00:00Z")
 * )
 * 
 * @OA\Tag(
 *     name="Volunteers",
 *     description="API Endpoints for managing volunteers"
 * )
 */
class VolunteerController extends Controller
{
     /**
     * @OA\Get(
     *     path="/api/volunteers",
     *     tags={"Volunteers"},
     *     summary="Get list of volunteers",
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation"
     *     )
     * )
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
     * @OA\Post(
     *     path="/api/volunteers",
     *     tags={"Volunteers"},
     *     summary="Create new volunteer",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","address","phone","gender","reason_desc","payment_method","act_id"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="email", type="string", format="email"),
     *             @OA\Property(property="address", type="string"),
     *             @OA\Property(property="phone", type="string"),
     *             @OA\Property(property="gender", type="string"),
     *             @OA\Property(property="reason_desc", type="string"),
     *             @OA\Property(property="payment_method", type="string"),
     *             @OA\Property(property="image", type="string", format="binary"),
     *             @OA\Property(property="act_id", type="integer"),
     *         )
     *     ),
     *     @OA\Response(response=201, description="Volunteer created successfully"),
     *     @OA\Response(response=400, description="Invalid input")
     * )
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
     * @OA\Get(
     *     path="/api/volunteers/{id}",
     *     tags={"Volunteers"},
     *     summary="Get volunteer by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful operation"),
     *     @OA\Response(response=404, description="Volunteer not found")
     * )
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
     * @OA\Delete(
     *     path="/api/volunteers/{id}",
     *     tags={"Volunteers"},
     *     summary="Delete volunteer by ID",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Volunteer deleted successfully"),
     *     @OA\Response(response=404, description="Volunteer not found")
     * )
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
