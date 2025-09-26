<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationMethod;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Donations",
 *     description="API Endpoints for managing donations"
 * )
 *
 * @OA\Schema(
 *     schema="Donation",
 *     type="object",
 *     title="Donation",
 *     description="Donation model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="donation_amount", type="integer", example=50000),
 *     @OA\Property(property="image_slip", type="string", example="donations/slip1.png"),
 *     @OA\Property(property="donation_method_id", type="integer", example=2),
 *     @OA\Property(property="created_at", type="string", format="date-time", example="2025-09-23T10:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", example="2025-09-23T10:00:00Z")
 * )
 *
 */
class DonationController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/donations",
     *     summary="Get list of donations",
     *     tags={"Donations"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful operation",
     *     )
     * )
     */
    public function index()
    {
        return response()->json(
            Donation::orderBy('id', 'desc')
            ->get()
        );
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
     *     path="/api/donations",
     *     summary="Create a new donation",
     *     tags={"Donations"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 required={"amount", "image", "method_id"},
     *                 @OA\Property(property="amount", type="integer", example=50000),
     *                 @OA\Property(property="image", type="string", format="binary"),
     *                 @OA\Property(property="method_id", type="integer", example=1)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Donation created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/Donation")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'amount' => 'required|integer|min:10000',
            'image' => 'required|file|mimes:jpg,jpeg,png|max:10240',
            'method_id' => 'required|exists:donation_methods,id'
        ]);

        // Upload file
        $path = $request->file('image')->store('donations', 'public');

        $donation = Donation::create([
            'donation_amount' => $data['amount'],
            'image_slip'      => $path,
            'donation_method_id' => $data['method_id']
        ]);

        return response()->json($donation, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/donations/{id}",
     *     summary="Get donation by ID",
     *     tags={"Donations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Donation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Donation detail",
     *         @OA\JsonContent(ref="#/components/schemas/Donation")
     *     ),
     *     @OA\Response(response=404, description="Donation not found")
     * )
     */
    public function show(string $id)
    {
        return response()->json(Donation::findOrFail($id), 200);
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
     *     path="/api/donations/{id}",
     *     summary="Delete donation",
     *     tags={"Donations"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Donation ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Donation deleted successfully"),
     *     @OA\Response(response=404, description="Donation not found")
     * )
     */
    public function destroy(string $id)
    {
        $donation = Donation::find($id);

        if (!$donation) {
            return response()->json([
                'message' => 'Donation not found'
            ], 404);
        }

        if ($donation->image_slip && \Storage::disk('public')->exists($donation->image_slip)) {
            \Storage::disk('public')->delete($donation->image_slip);
        }

        $donation->delete();

        return response()->json([
            'message' => 'Donation deleted successfully'
        ], 200);
    }
}
