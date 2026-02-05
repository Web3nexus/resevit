<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Helpers\StorageHelper;

class ProfileController extends Controller
{
    /**
     * Get the authenticated user's profile.
     */
    public function show(Request $request)
    {
        $user = $request->user();

        // Ensure onboarding status and tenant info is included for Business Owners
        if ($user->hasRole('Business Owner')) {
            $tenant = \App\Models\Tenant::where('owner_user_id', $user->id)->first();
            $user->onboarding_status = $tenant->onboarding_status ?? 'active';
            $user->current_tenant = [
                'id' => $tenant->id,
                'name' => $tenant->name,
                'slug' => $tenant->slug,
                'domain' => $tenant->domain,
            ];
        }

        // Add default notification preferences if none exist
        if (!$user->notification_preferences) {
            $user->notification_preferences = [
                'new_reservations' => true,
                'order_updates' => true,
                'inventory_alerts' => false,
                'staff_messages' => true,
                'marketing_emails' => false,
            ];
        }

        return response()->json($user);
    }

    /**
     * Update notification preferences.
     */
    public function updateNotifications(Request $request)
    {
        $user = $request->user();
        $validated = $request->validate([
            'preferences' => 'required|array',
        ]);

        $user->update([
            'notification_preferences' => $validated['preferences']
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Notification preferences updated',
            'data' => $user->notification_preferences
        ]);
    }

    /**
     * Update the authenticated user's profile.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name' => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|max:2048', // 2MB Max
        ]);

        $user = $request->user();

        if ($request->hasFile('photo')) {
            // Delete old photo if exists
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $path = $request->file('photo')->store('profile-photos', 'public');

            $user->update([
                'profile_photo_path' => $path,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Profile photo updated',
                'url' => StorageHelper::getUrl($path),
            ]);
        }

        return response()->json(['message' => 'No photo uploaded'], 400);
    }
}
