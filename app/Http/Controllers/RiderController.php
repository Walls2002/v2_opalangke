<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiderController extends Controller
{
    // public function index(Request $request)
    // {
    //     $riders = Rider::with('vendor')->get();
    //     return response()->json($riders);
    // }

    public function index(Request $request)
    {
        $riders = Rider::query()
            ->with('vendor')
            ->where('vendor_id', $request->user()->id)
            ->get();
        return response()->json(['riders' => $riders]);
    }

    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'vendor_id' => 'required|exists:users,id',
    //         'name' => 'required|string|max:255',
    //         'contact_number' => 'required|string|max:20',
    //         'license_number' => 'required|string|max:50|unique:riders',
    //         'plate_number' => 'nullable|string|max:50',
    //         'email' => 'required|string|email|max:255|unique:riders',
    //         'password' => 'required|string|min:8',
    //     ]);

    //     $rider = Rider::create($validated);
    //     return response()->json(['message' => 'Rider created successfully.', 'rider' => $rider], 201);
    // }

    public function update(Request $request, Rider $rider)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_number' => 'required|string|max:20',
            'license_number' => "required|string|max:50|unique:riders,license_number,{$rider->id}",
            'plate_number' => 'nullable|string|max:50',
            'email' => "required|string|email|max:255|unique:riders,email,{$rider->id}",
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->has('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $rider->update($validated);
        return response()->json(['message' => 'Rider updated successfully.', 'rider' => $rider]);
    }

    public function destroy(Rider $rider)
    {
        $rider->delete();
        return response()->json(['message' => 'Rider deleted successfully.']);
    }
}
