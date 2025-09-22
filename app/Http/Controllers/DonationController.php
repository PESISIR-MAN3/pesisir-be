<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use App\Models\DonationMethod;
use Illuminate\Http\Request;

class DonationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return response()->json(Donation::all());
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
     * Display the specified resource.
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
     * Remove the specified resource from storage.
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
