<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;
use Mews\Purifier\Facades\Purifier;

class NewsController extends Controller
{
    // 1️⃣ List berita (search + sort + pagination)
    public function index(Request $request)
    {
        try {
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

            return ApiResponse::success("News list retrieved", $news);

        } catch (\Exception $e) {
            return ApiResponse::error("Failed to load news", $e->getMessage(), 500);
        }
    }

    // 2️⃣ Detail berita
    public function show(News $news)
    {
        return ApiResponse::success("News detail retrieved", $news);
    }

    // 3️⃣ Tambah berita
    public function store(Request $request)
    {
        // ✅ Authorization via Policy
        $this->authorize('create', News::class);

        try {
            $validated = $request->validate([
                'title'    => 'required|string|min:5|max:150',
                'content'  => 'required|string|min:20',
                'category' => 'nullable|string|in:politik,olahraga,teknologi,ekonomi,hiburan',
                'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // Sanitasi content (anti XSS)
            $validated['content'] = Purifier::clean($validated['content']);

            // Upload image
            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')
                                              ->store('news_images', 'public');
            }

            $validated['user_id'] = Auth::id();

            $news = News::create($validated);

            return ApiResponse::success("News created successfully", $news, 201);

        } catch (\Exception $e) {
            return ApiResponse::error("Failed to create news", $e->getMessage(), 500);
        }
    }

    // 4️⃣ Update berita
    public function update(Request $request, News $news)
    {
        // ✅ Authorization via Policy
        $this->authorize('update', $news);

        try {
            $validated = $request->validate([
                'title'    => 'required|string|min:5|max:150',
                'content'  => 'required|string|min:20',
                'category' => 'nullable|string|in:politik,olahraga,teknologi,ekonomi,hiburan',
                'image'    => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            $validated['content'] = Purifier::clean($validated['content']);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')
                                              ->store('news_images', 'public');
            }

            $news->update($validated);

            return ApiResponse::success("News updated successfully", $news);

        } catch (\Exception $e) {
            return ApiResponse::error("Failed to update news", $e->getMessage(), 500);
        }
    }

    // 5️⃣ Hapus berita
    public function destroy(News $news)
    {
        // ✅ Authorization via Policy
        $this->authorize('delete', $news);

        try {
            $news->delete();

            return ApiResponse::success("News deleted successfully");

        } catch (\Exception $e) {
            return ApiResponse::error("Failed to delete news", $e->getMessage(), 500);
        }
    }
}
