<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $rules['name'] = ['required', 'string', 'min:3', 'max:100'];
        $rules['contact'] = ['required'];

        if (get_class($user) === 'App\Models\Rider') {
            $rules['email'] = ['required', 'email', Rule::unique('riders', 'email')->ignore($user->id)];
            $user->contact_number = $request->contact;
        } elseif (get_class($user) === 'App\Models\User') {
            $rules['email'] = ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)];
            $user->contact = $request->contact;
        }

        $request->validate(rules: $rules);

        $user->name = $request->name;
        $user->email = $request->email;

        if (!$user->save()) {
            return response()->json(['message' => 'Encountered an error updating the user profile.'], 401);
        }

        return response()->json(['message' => 'Profile updated.', 'user' => $user], 200);
    }

    public function changeLocation(Request $request)
    {
        $request->validate([
            'location_id' => ['required', 'exists:locations,id'],
        ]);

        $user = $request->user();
        $user->location_id = $request->location_id;

        if (!$user->save()) {
            return response()->json(['message' => 'Encountered an error while updating your location.'], 400);
        }

        return response()->json(['message' => 'User location updated successfully'], 200);
    }
}
