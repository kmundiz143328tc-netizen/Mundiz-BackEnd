<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    // GET /api/profile
    public function show()
    {
        return response()->json(auth()->user());
    }

    // PUT /api/profile
    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'                  => 'sometimes|string|max:255',
            'email'                 => 'sometimes|email|unique:users,email,' . $user->id,
            'current_password'      => 'required_with:new_password|string',
            'new_password'          => 'nullable|string|min:6|confirmed',
        ]);

        // Check current password if changing password
        if (!empty($validated['current_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json(['message' => 'Current password is incorrect.'], 422);
            }
        }

        $updateData = [];
        if (isset($validated['name']))         $updateData['name']     = $validated['name'];
        if (isset($validated['email']))        $updateData['email']    = $validated['email'];
        if (!empty($validated['new_password'])) $updateData['password'] = Hash::make($validated['new_password']);

        $user->update($updateData);

        ActivityLog::log('updated', 'Profile', "Admin updated their profile");

        return response()->json([
            'message' => 'Profile updated successfully.',
            'user'    => $user->fresh(),
        ]);
    }
}