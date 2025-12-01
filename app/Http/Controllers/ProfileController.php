<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class ProfileController extends Controller
{
    // 🔹 1) GET PROFILE
    public function show()
    {
        return ApiResponse::success(
            Auth::user(),
            'Profile fetched successfully'
        );
    }

    // 🔹 2) UPDATE PROFILE
    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name'  => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return ApiResponse::success(
            $user,
            'Profile updated successfully'
        );
    }

    // 🔹 3) UPDATE PASSWORD
    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:8|confirmed'
        ]);

        $user = Auth::user();

        if (!Hash::check($request->old_password, $user->password)) {
            return ApiResponse::error(
                'Old password does not match',
                400
            );
        }

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        return ApiResponse::success(
            null,
            'Password updated successfully'
        );
    }

    // 🔹 4) UPDATE PHOTO
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120' // max 5MB
        ]);

        $user = Auth::user();

        // Hapus foto lama
        if ($user->photo && file_exists(public_path('uploads/profile/' . $user->photo))) {
            unlink(public_path('uploads/profile/' . $user->photo));
        }

        // Simpan foto baru
        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);

        $user->update([
            'photo' => $filename
        ]);

        return ApiResponse::success('Photo updated successfully',['photo_url' => url('uploads/profile/' . $filename)]);}
}
