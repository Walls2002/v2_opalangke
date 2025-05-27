<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class ChangePasswordController extends Controller
{
    public function update(Request $request)
    {
        $request->validate([
            'old_password' => ['current_password:sanctum'],
            'new_password' => ['required', 'confirmed'],
        ]);

        $user = $request->user();

        $user->password = $request->new_password;

        if (!$user->save()) {
            return response()->json(['message' => 'Encountered an error during password update.'], 401);
        }

        return response()->json(['message' => 'Password updated.'], 200);
    }
    public function changePassword(Request $request)
    {
        $request->validate([
            'newPassword' => 'nullable|string|min:8',
            'email' => 'required|email',
        ]);


        try {
            $user = DB::table('users')->where('email', $request->email)->first();

            if (!$user) {
                return response()->json([
                    'code' => 404,
                    'message' => 'User not found.'
                ], 404);
            }

            DB::table('users')
                ->where('email', $request->email)
                ->update(['password' => bcrypt($request->newPassword)]);

            return response()->json([
                'code' => 200,
                'message' => 'Password changed successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'code' => 500,
                'message' => 'Failed to change password.'
            ], 500);
        }
    }
}
