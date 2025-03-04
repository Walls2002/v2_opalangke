<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        return response()->json(Location::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'province' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'city_code' => 'required|string|max:255',
            'barangay' => 'required|string|max:255',
            'barangay_code' => 'required|string|max:255',
            'shipping_fee' => 'required|decimal:0,2|min:0.00,max:100000',
        ]);

        $location = Location::create($validated);

        return response()->json(['message' => 'Location created successfully!', 'data' => $location], 201);
    }

    public function show($id)
    {
        $location = Location::findOrFail($id);
        return response()->json($location);
    }

    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $validated = $request->validate([
            'province' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'city_code' => 'sometimes|string|max:255',
            'barangay' => 'sometimes|string|max:255',
            'barangay_code' => 'sometimes|string|max:255',
            'shipping_fee' => 'sometimes|decimal:0,2|min:0.00,max:100000',
        ]);

        $location->update($validated);

        return response()->json(['message' => 'Location updated successfully!', 'data' => $location]);
    }

    public function destroy($id)
    {
        $location = Location::findOrFail($id);
        $location->delete();

        return response()->json(['message' => 'Location deleted successfully!']);
    }
}
