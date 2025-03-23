<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RiderController extends Controller
{
    /**
     * Make a rider.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => 'required|exists:locations,id',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'required|string|max:50',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact' => 'nullable|string|max:15',
            'license_number' => 'required|string|max:15|unique:riders,license_number',
            'plate_number' => 'required|string|max:15|unique:riders,plate_number',
        ]);

        $user = User::create([
            'location_id' => $request->location_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'email' => $request->email,
            'password' => $request->password,
            'contact' => $request->contact,
            'role' => 'rider',
            'email_verified_at' => now(),
        ]);

        $rider = Rider::create([
            'user_id' => $user->id,
            'license_number' => $request->license_number,
            'plate_number' => $request->plate_number,
        ]);

        return response()->json(['message' => 'Rider created successfully.', 'rider' => $rider], 201);
    }

    public function update(Request $request, Rider $rider)
    {
        $validated = $request->validate([
            'location_id' => 'required|exists:locations,id',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'required|string|max:50',
            'contact_number' => 'required|string|max:20',
            'license_number' => "required|string|max:50|unique:riders,license_number,{$rider->id}",
            'plate_number' => "required|string|max:50|unique:riders,plate_number,{$rider->id}",
            'email' => "required|string|email|max:255|unique:users,email,{$rider->id}",
            'password' => 'nullable|string|min:8',
        ]);

        if ($request->has('password')) {
            $validated['password'] = bcrypt($request->password);
        }

        $rider->load('user');

        $rider->user->update($validated);
        $rider->update([
            'license_number' => $request->license_number,
            'plate_number' => $request->plate_number,
        ]);

        return response()->json(['message' => 'Rider updated successfully.', 'rider' => $rider]);
    }

    public function updateVerify(Request $request, Rider $rider)
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $rider->load('user');
        $user = $rider->user;

        $user->email_verified_at = now();

        if (!$user->save()) {
            return response()->json(['message' => 'Error verifying rider account.']);
        }

        return response()->json(['message' => 'Rider verified successfully.', 'rider' => $rider]);
    }

    public function destroy(Rider $rider)
    {
        $rider->riderStores()->delete();
        $rider->is_active = false;

        if (!$rider->save()) {
            return response()->json(['message' => 'Error deactivating rider account.']);
        }

        return response()->json(['message' => 'Rider deactivated successfully.']);
    }
}
