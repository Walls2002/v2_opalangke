<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'location_id' => 'required|exists:locations,id',
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'contact' => 'nullable|string|max:15',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'code' => 422,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::create([
            'location_id' => $request->location_id,
            'last_name' => $request->last_name,
            'first_name' => $request->first_name,
            'middle_name' =>  $request->filled('middle_name') ? $request->middle_name : "",
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'contact' => $request->contact,
            'role' => 'customer',
            'email_verified_at' => now(),
        ]);

        return response()->json([
            'code' => 201,
            'message' => 'Customer created successfully',
            'data' => $user
        ], 201);
    }

    public function verifyEmailExists(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $exists = DB::table('users')
            ->where('email', $request->email)
            ->exists();

        return response()->json(['exists' => $exists]);
    }
}
