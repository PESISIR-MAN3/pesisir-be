<?php

namespace App\Http\Controllers;

use App\Models\DonationMethod;
use Illuminate\Http\Request;

class DonationMethodController extends Controller
{
    /**
     * Display a listing of the resource.
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
     * Store a newly created resource in storage.
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
     * Display the specified resource.
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
     * Update the specified resource in storage.
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
     * Remove the specified resource from storage.
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
