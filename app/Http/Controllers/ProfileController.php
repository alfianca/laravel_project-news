<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helpers\ApiResponse;

class ProfileController extends Controller
{
    /**
     * 🔹 1) GET PROFILE (user yang sedang login)
     */
    public function show()
    {
        $user = Auth::user();

        // Authorization via policy
        $this->authorize('view', $user);

        return ApiResponse::success(
            'Profile fetched successfully',
            $user
        );
    }

    /**
     * 🔹 2) UPDATE PROFILE (name & email)
     */
    public function update(Request $request)
    {
        $user = Auth::user();

        // Authorization via policy
        $this->authorize('update', $user);

        $validated = $request->validate([
            'name'  => 'nullable|string|max:255',
            'email' => 'nullable|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return ApiResponse::success(
            'Profile updated successfully',
            $user
        );
    }

    /**
     * 🔹 3) UPDATE PASSWORD
     */
    public function updatePassword(Request $request)
    {
        $user = Auth::user();

        // Authorization via policy
        $this->authorize('update', $user);

        $request->validate([
            'old_password' => 'required',
            'password'     => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($request->old_password, $user->password)) {
            return ApiResponse::error(
                'Old password does not match',
                null,
                400
            );
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        return ApiResponse::success(
            'Password updated successfully'
        );
    }

    /**
     * 🔹 4) UPDATE PHOTO PROFILE
     */
    public function updatePhoto(Request $request)
    {
        $user = Auth::user();

        // Authorization via policy
        $this->authorize('update', $user);

        $request->validate([
            'photo' => 'required|image|mimes:jpg,jpeg,png|max:5120', // 5MB
        ]);

        // Hapus foto lama jika ada
        if ($user->photo && file_exists(public_path('uploads/profile/' . $user->photo))) {
            unlink(public_path('uploads/profile/' . $user->photo));
        }

        // Simpan foto baru
        $file = $request->file('photo');
        $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/profile'), $filename);

        $user->update([
            'photo' => $filename,
        ]);

        return ApiResponse::success(
            'Photo updated successfully',
            [
                'photo_url' => url('uploads/profile/' . $filename),
            ]
        );
    }
}
