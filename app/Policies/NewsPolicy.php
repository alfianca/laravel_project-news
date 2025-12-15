<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;

class NewsPolicy
{
    /**
     * Lihat daftar berita
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Lihat detail berita
     */
    public function view(User $user, News $news): bool
    {
        return true;
    }

    /**
     * Buat berita
     * (hanya admin)
     */
    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Update berita
     * (admin atau pemilik berita)
     */
    public function update(User $user, News $news): bool
    {
        return $user->role === 'admin'
            || $user->id === $news->user_id;
    }

    /**
     * Hapus berita
     * (admin atau pemilik berita)
     */
    public function delete(User $user, News $news): bool
    {
        return $user->role === 'admin'
            || $user->id === $news->user_id;
    }

    /**
     * Restore (optional)
     */
    public function restore(User $user, News $news): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Force delete (optional)
     */
    public function forceDelete(User $user, News $news): bool
    {
        return $user->role === 'admin';
    }
}
