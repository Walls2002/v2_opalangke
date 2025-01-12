<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
