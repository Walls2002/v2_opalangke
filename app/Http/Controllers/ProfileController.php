<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'last_name' => 'required|string|max:50',
            'first_name' => 'required|string|max:50',
            'middle_name' => 'required|string|max:50',
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
            'contact' => 'nullable|string|max:15',
        ]);

        $user->last_name = $request->last_name;
        $user->first_name = $request->first_name;
        $user->middle_name = $request->middle_name;
        $user->contact = $request->contact;
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

    public function changeProfilePhoto(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $fileName = $request->file('image')->store('profiles', 'public');
        $user->profile_picture = $fileName;

        if (!$user->save()) {
            return response()->json(['message' => 'Encountered an error updating the profile picture.'], 400);
        }

        return response()->json(['message' => 'Profile picture updated successfully'], 200);
    }
}