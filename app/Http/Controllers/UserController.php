<?php

namespace App\Http\Controllers;

use App\Models\Rider;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Get all the users and riders in one api call.
     *
     * @return void
     */
    public function indexAll()
    {
        $users = User::all();
        $riders = Rider::all();

        $usersArray = $users->toArray();
        $ridersArray = $riders->toArray();

        $allUsers = array_merge($usersArray, $ridersArray);

        return response()->json($allUsers, 200);
    }

    public function index()
    {
        $vendors = User::where('role', 'vendor')->get();
        return response()->json($vendors, 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact' => 'nullable|string|max:15',
            'plate_number' => 'nullable|string|max:50',
            'role' => 'required|in:admin,vendor,customer,rider',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact' => $request->contact,
            'plate_number' => $request->plate_number,
            'role' => $request->role,
            'email_verified_at' => now(),
        ]);

        return response()->json($user, 201);
    }

    public function storeVendor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'contact' => 'nullable|string|max:15',
            'plate_number' => 'nullable|string|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact' => $request->contact,
            'plate_number' => $request->plate_number,
            'role' => 'vendor',
            'email_verified_at' => null,
        ]);

        return response()->json($user, 201);
    }

    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user, 200);
    }

    public function verifyVendorUser(Request $request, User $user)
    {
        $verifiedBy = $request->user();
        if ($verifiedBy?->role != 'admin') {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        if ($user->role != 'vendor') {
            return response()->json(['message' => 'User is not a vendor'], 422);
        }

        if ($user->email_verified_at) {
            return response()->json(['message' => 'Vendor is already verified.'], 422);
        }

        $user->email_verified_at = now();
        if (!$user->save()) {
            return response()->json(['message' => 'Encountered an error verifying the vendor.'], 400);
        }

        return response()->json($user, 200);
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:users,email,' . $id,
            'password' => 'nullable|string|min:8',
            'contact' => 'nullable|string|max:15',
            'plate_number' => 'nullable|string|max:50',
            'role' => 'nullable|in:admin,vendor,customer,rider',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $user->update(array_merge(
            $request->except('password'),
            $request->password ? ['password' => Hash::make($request->password)] : []
        ));

        return response()->json($user, 200);
    }

    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        $user->delete();

        return response()->json(['message' => 'User deleted successfully'], 200);
    }
}
