<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Check in User model
        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $token = $user->createToken('auth_token')->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'user_type' => 'user',
            ]);
        }

        // Check in Rider model

        $rider = Rider::where('email', $request->email)->first();

        if ($rider && Hash::check($request->password, $rider->password)) {
            $token = $rider->createToken('auth_token', ['role-rider'])->plainTextToken;

            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $rider,
                'user_type' => 'rider',
            ]);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
    }
}
