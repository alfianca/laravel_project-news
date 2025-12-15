<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    // Lihat profile
    public function view(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id || $authUser->role === 'admin';
    }

    // Update profile
    public function update(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id || $authUser->role === 'admin';
    }

    // Update password
    public function updatePassword(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }

    // Update photo
    public function updatePhoto(User $authUser, User $user): bool
    {
        return $authUser->id === $user->id;
    }
}
