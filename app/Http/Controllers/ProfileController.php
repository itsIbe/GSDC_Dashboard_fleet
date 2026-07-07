<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\AuditLog;
AuditLog::create([
    'user_id' => Auth::id(),
    'action' => 'Login',
    'ip_address' => request()->ip(),
    'description' => 'User Updated profile pic',
]);
class ProfileController extends Controller
{
    /**
     * Show profile edit page (optional).
     */
    public function edit()
    {
      
    }

    /**
     * Update profile picture.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = Auth::user();

        // Store new image in storage/app/public/profile_photos
        $path = $request->file('profile_photo')->store('profile_photos', 'public');

        // Delete old photo if exists
        if ($user->profile_photo_url && Storage::disk('public')->exists($user->profile_photo_url)) {
            Storage::disk('public')->delete($user->profile_photo_url);
        }

        // Save new path in database
        $user->profile_photo_url = $path;
        $user->save();

        return back()->with('success', 'Profile photo updated successfully.');
    }
}
