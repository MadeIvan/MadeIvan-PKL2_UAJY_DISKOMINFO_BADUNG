<?php

namespace App\Admin\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;

class CategoryController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Category::orderBy('name')->withCount('applications');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('slug', 'like', "%{$search}%");
        }

        if ($request->boolean('all')) {
            return response()->json($query->get());
        }

        // Include soft-deleted categories in the paginated table view
        $paginatedQuery = clone $query;
        $categories = $paginatedQuery->withTrashed()->paginate(10);

        return response()->json([
            'data' => $categories->items(),
            'meta' => [
                'current_page' => $categories->currentPage(),
                'last_page' => $categories->lastPage(),
                'total' => $categories->total(),
                'total_categories' => Category::count(),
                'empty_categories' => Category::doesntHave('applications')->count(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $category = Category::create($validated);

        return response()->json([
            'message' => 'Kategori berhasil dibuat.',
            'data' => $category,
        ], 201);
    }

    public function show(Category $category): JsonResponse
    {
        return response()->json($category);
    }

    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:categories,name,' . $category->id,
            'description' => 'nullable|string',
        ]);

        if (isset($validated['name']) && $validated['name'] !== $category->name) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        $category->update($validated);

        return response()->json([
            'message' => 'Kategori berhasil diperbarui.',
            'data' => $category,
        ]);
    }

    public function destroy(Category $category): JsonResponse
    {
        if ($category->applications()->exists()) {
            // Soft delete
            $category->delete();
            return response()->json([
                'message' => 'Kategori disembunyikan (soft delete) karena masih digunakan oleh aplikasi.',
            ]);
        }

        // Hard delete
        $category->forceDelete();
        return response()->json([
            'message' => 'Kategori berhasil dihapus permanen.',
        ]);
    }

    public function restore(int $id): JsonResponse
    {
        $category = Category::withTrashed()->findOrFail($id);
        
        if (!$category->trashed()) {
            return response()->json([
                'message' => 'Kategori tidak dalam status terhapus.',
            ], 400);
        }

        $category->restore();

        return response()->json([
            'message' => 'Kategori berhasil dipulihkan.',
            'data' => $category,
        ]);
    }
}
