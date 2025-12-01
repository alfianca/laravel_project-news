<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class NewsController extends Controller
{
    // 1️⃣ Tampilkan semua berita
    public function index(Request $request)
    {
        $query = News::query();

        // Search
        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', "%{$request->search}%")
                  ->orWhere('content', 'like', "%{$request->search}%");
            });
        }

        // Sorting & Pagination
        $sortBy   = $request->get('sort_by', 'created_at');
        $sortType = $request->get('sort_type', 'desc');
        $perPage  = $request->get('per_page', 10);

        $news = $query->orderBy($sortBy, $sortType)
                      ->paginate($perPage);

        return ApiResponse::success(
            "News list retrieved",
            $news
        );
    }

    // 2️⃣ Detail berita tertentu
    public function show(News $news)
    {
        return ApiResponse::success(
            "News detail retrieved",
            $news
        );
    }

    // 3️⃣ Tambah berita
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $news = News::create([
            'title'   => $validated['title'],
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        return ApiResponse::success(
            "News created successfully",
            $news,
            201
        );
    }

    // 4️⃣ Update berita
    public function update(Request $request, News $news)
    {
        // Cek pemilik berita
        if ($news->user_id !== Auth::id()) {
            return ApiResponse::error(
                "Unauthorized",
                null,
                403
            );
        }

        $validated = $request->validate([
            'title'   => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $news->update($validated);

        return ApiResponse::success(
            "News updated successfully",
            $news
        );
    }

    // 5️⃣ Hapus berita
    public function destroy(News $news)
    {
        // Cek pemilik berita
        if ($news->user_id !== Auth::id()) {
            return ApiResponse::error(
                "Unauthorized",
                null,
                403
            );
        }

        $news->delete();

        return ApiResponse::success(
            "News deleted successfully"
        );
    }
}
