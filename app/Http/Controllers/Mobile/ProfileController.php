<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProfileController extends Controller
{
    use ApiResponse;

    public function update(Request $request)
    {
        try {
            $user = $request->user();
            $updateData = $request->only(['name', 'email', 'phone']);

            // Validate email uniqueness if email is being updated
            if (isset($updateData['email']) && $updateData['email'] !== $user->email) {
                $request->validate([
                    'email' => 'required|email|unique:users,email,' . $user->id,
                ]);
            }

            // Update user profile
            $user->update($updateData);

            // Load updated user with relationships
            $user->load(['roles']);

            // Format response
            $formattedUser = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->type,
                'profile_photo_url' => $user->profile_photo_url,
                'school_id' => $user->school_id,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
            ];

            // Trigger real-time update
            broadcast(new \App\Events\ProfileUpdated($user))->toOthers();

            return $this->successResponse($formattedUser, 'Profile updated successfully');
        } catch (\Exception $e) {
            Log::error("Error updating profile: " . $e->getMessage());
            return $this->errorResponse('Failed to update profile.', 500);
        }
    }

    public function uploadPhoto(Request $request)
    {
        try {
            $user = $request->user();

            // Validate file upload
            $request->validate([
                'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Delete old profile photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            // Store new profile photo
            $file = $request->file('profile_photo');
            $filename = 'profile_photos/' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('', $filename, 'public');

            // Update user's profile photo path
            $user->update(['profile_photo_path' => $path]);

            // Generate URL
            $profilePhotoUrl = Storage::disk('public')->url($path);

            // Load updated user
            $user->load(['roles']);

            // Format response
            $formattedUser = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->type,
                'profile_photo_url' => $profilePhotoUrl,
                'school_id' => $user->school_id,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
            ];

            // Trigger real-time update
            broadcast(new \App\Events\ProfileUpdated($user))->toOthers();

            return $this->successResponse([
                'profile_photo_url' => $profilePhotoUrl,
                'user' => $formattedUser
            ], 'Profile photo uploaded successfully');
        } catch (\Exception $e) {
            Log::error("Error uploading profile photo: " . $e->getMessage());
            return $this->errorResponse('Failed to upload profile photo.', 500);
        }
    }

    public function getProfile(Request $request)
    {
        try {
            $user = $request->user();
            $user->load(['roles']);

            // Format response
            $formattedUser = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $user->type,
                'profile_photo_url' => $user->profile_photo_url,
                'school_id' => $user->school_id,
                'created_at' => $user->created_at->toISOString(),
                'updated_at' => $user->updated_at->toISOString(),
            ];

            return $this->successResponse($formattedUser, 'Profile retrieved successfully');
        } catch (\Exception $e) {
            Log::error("Error getting profile: " . $e->getMessage());
            return $this->errorResponse('Failed to retrieve profile.', 500);
        }
    }
}