<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Responses\ApiResponse;
use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CategoryController extends BaseController
{
    /**
     * Get all categories for authenticated user
     */
    public function index(Request $request): JsonResponse
    {
        $userId = $request->user()->id;
        $type = $request->query('type');

        $query = Category::where('user_id', $userId);

        if ($type && in_array($type, ['income', 'expense'])) {
            $query->where('type', $type);
        }

        $categories = $query->get();

        return ApiResponse::success(
            CategoryResource::collection($categories),
            'Categories retrieved successfully'
        );
    }

    /**
     * Store a new category
     */
    public function store(StoreCategoryRequest $request): JsonResponse
    {
        $userId = $request->user()->id;

        $category = Category::create([
            'user_id' => $userId,
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return ApiResponse::created(
            new CategoryResource($category),
            'Category created successfully'
        );
    }

    /**
     * Get single category
     */
    public function show(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to view this category');
        }

        return ApiResponse::success(
            new CategoryResource($category),
            'Category retrieved successfully'
        );
    }

    /**
     * Update category
     */
    public function update(StoreCategoryRequest $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to update this category');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'type' => $request->type,
            'icon' => $request->icon,
            'color' => $request->color,
        ]);

        return ApiResponse::success(
            new CategoryResource($category),
            'Category updated successfully'
        );
    }

    /**
     * Delete category
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        if ($category->user_id !== $request->user()->id) {
            return ApiResponse::unauthorized('Unauthorized to delete this category');
        }

        $category->delete();

        return ApiResponse::success(null, 'Category deleted successfully');
    }
}
