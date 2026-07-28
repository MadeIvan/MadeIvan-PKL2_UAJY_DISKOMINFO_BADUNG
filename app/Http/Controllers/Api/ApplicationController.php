<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ApplicationController extends Controller
{
    public function index(): JsonResponse
    {
        $applications = Application::query()->latest()->get();

        return response()->json([
            'message' => 'Applications retrieved successfully.',
            'data' => $applications,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category_name' => ['nullable', 'string', 'max:150'],
            'status' => ['required', 'in:active,inactive,archived'],
            'is_public' => ['sometimes', 'boolean'],
        ]);

        $validated['slug'] = $this->generateUniqueSlug($validated['name']);

        $application = Application::create($validated);

        return response()->json([
            'message' => 'Application created successfully.',
            'data' => $application,
        ], 201);
    }

    public function show(Application $application): JsonResponse
    {
        return response()->json([
            'message' => 'Application retrieved successfully.',
            'data' => $application,
        ]);
    }

    public function update(
        Request $request,
        Application $application
    ): JsonResponse {
        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:200'],
            'description' => ['nullable', 'string'],
            'category_name' => ['nullable', 'string', 'max:150'],
            'status' => ['sometimes', 'required', 'in:active,inactive,archived'],
            'is_public' => ['sometimes', 'boolean'],
        ]);

        if (
            isset($validated['name']) &&
            $validated['name'] !== $application->name
        ) {
            $validated['slug'] = $this->generateUniqueSlug(
                $validated['name'],
                $application->id
            );
        }

        $application->update($validated);

        return response()->json([
            'message' => 'Application updated successfully.',
            'data' => $application->refresh(),
        ]);
    }

    public function destroy(Application $application): JsonResponse
    {
        $application->delete();

        return response()->json([
            'message' => 'Application deleted successfully.',
        ]);
    }

    private function generateUniqueSlug(
        string $name,
        ?int $ignoreId = null
    ): string {
        $baseSlug = Str::slug($name);
        $slug = $baseSlug;
        $counter = 2;

        while (
            Application::query()
                ->when(
                    $ignoreId,
                    fn ($query) => $query->where('id', '!=', $ignoreId)
                )
                ->where('slug', $slug)
                ->exists()
        ) {
            $slug = "{$baseSlug}-{$counter}";
            $counter++;
        }

        return $slug;
    }
}