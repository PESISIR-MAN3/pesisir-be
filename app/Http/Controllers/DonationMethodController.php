<?php

namespace App\Http\Controllers;

use App\Models\DonationMethod;
use Illuminate\Http\Request;

/**
 * @OA\Tag(
 *     name="Donation Methods",
 *     description="API Endpoints for managing donation methods"
 * )
 * @OA\Schema(
 *     schema="DonationMethod",
 *     type="object",
 *     title="Donation Method",
 *     description="Donation Method model"
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="method_name", type="string", example="BCA"),
 *     @OA\Property(property="account_number", type="string", example="1234567890"),
 *     @OA\Property(property="owner_name", type="string", example="John Doe"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 */
class DonationMethodController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/donation-methods",
     *     summary="Get all donation methods",
     *     tags={"Donation Methods"},
     *     @OA\Response(
     *         response=200,
     *         description="List of donation methods",
     *         @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/DonationMethod"))
     *     )
     * )
     */
    public function index()
    {
        return response()->json(DonationMethod::all());
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
     *     path="/api/donation-methods",
     *     summary="Create a new donation method",
     *     tags={"Donation Methods"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"method", "number", "owner"},
     *             @OA\Property(property="method", type="string", example="BCA"),
     *             @OA\Property(property="number", type="string", example="1234567890"),
     *             @OA\Property(property="owner", type="string", example="John Doe")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Donation method created",
     *         @OA\JsonContent(ref="#/components/schemas/DonationMethod")
     *     )
     * )
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'method' => 'required|string',
            'number' => 'required|integer',
            'owner' => 'required|string'
        ]);

        $donation_method = DonationMethod::create([
            'method_name' => $data['method'],
            'account_number' => $data['number'],
            'owner_name' => $data['owner']
        ]);

        return response()->json($donation_method, 201);
    }

    /**
     * @OA\Get(
     *     path="/api/donation-methods/{id}",
     *     summary="Get donation method by ID",
     *     tags={"Donation Methods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Donation method ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Donation method detail", @OA\JsonContent(ref="#/components/schemas/DonationMethod")),
     *     @OA\Response(response=404, description="Donation method not found")
     * )
     */
    public function show(string $id)
    {
        $donation_method = DonationMethod::findOrFail($id);
        return response()->json($donation_method);
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
     *     path="/api/donation-methods/{id}",
     *     summary="Update donation method",
     *     tags={"Donation Methods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Donation method ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         @OA\JsonContent(
     *             @OA\Property(property="method", type="string", example="BRI"),
     *             @OA\Property(property="number", type="string", example="9876543210"),
     *             @OA\Property(property="owner", type="string", example="Jane Doe")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Donation method updated", @OA\JsonContent(ref="#/components/schemas/DonationMethod")),
     *     @OA\Response(response=404, description="Donation method not found")
     * )
     */
    public function update(Request $request, string $id)
    {
        $donation_method = DonationMethod::findOrFail($id);

        $data = $request->validate([
            'name'      => 'sometimes|required|string|unique:locations,location_name,' . $id,
            'number'   => 'sometimes|required|string|unique:locations,location_address,' . $id,
            'owner'  => 'sometimes|required|numeric|between:-90,90',
        ]);

        $donation_method->update([
            'method_name'    => $data['name']      ?? $donation_method->method_name,
            'account_number' => $data['number']   ?? $donation_method->account_number,
            'owner_name'         => $data['owner']  ?? $donation_method->owner_name,
        ]);

        return response()->json($donation_method->refresh(), 200);
    }

    /**
     * @OA\Delete(
     *     path="/api/donation-methods/{id}",
     *     summary="Delete donation method",
     *     tags={"Donation Methods"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Donation method ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Donation method deleted"),
     *     @OA\Response(response=404, description="Donation method not found")
     * )
     */
    public function destroy(string $id)
    {
        $donation_method = DonationMethod::find($id);

        if (!$donation_method) {
            return response()->json([
                'message' => 'Method not found'
            ], 404);
        }

        $donation_method->delete();

        return response()->json([
            'message' => 'Method deleted successfully'
        ], 200);
    }
}
