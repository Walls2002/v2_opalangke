<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Get all the categories
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $categories = Category::query()->orderBy('name', 'asc')->get();

        return response()->json(
            data: [
                'message' => 'Categories fetched.',
                'categories' => $categories,
            ],
            status: 200,
        );
    }

    /**
     * Get all the parent categories.
     *
     * All the categories that has no parent.
     *
     * @return JsonResponse
     */
    public function parents(): JsonResponse
    {
        $categories = Category::query()->whereNull('parent_id')->orderBy('name', 'asc')->get();

        return response()->json(
            data: [
                'message' => 'Category parents fetched.',
                'categories' => $categories,
            ],
            status: 200,
        );
    }

    /**
     * Get all the child categories of a category.
     *
     * All the categories that has the given category as the parent.
     *
     * @return JsonResponse
     */
    public function children(Request $request): JsonResponse
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id']
        ]);

        $categories = Category::query()->where('parent_id', $request->category_id)->orderBy('name', 'asc')->get();
        return response()->json(
            data: [
                'message' => 'Category children fetched.',
                'categories' => $categories,
            ],
            status: 200,
        );
    }

    /**
     * Create a new category or subcategory
     *
     * @param Request $request
     * @return void
     */
    public function store(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $request->validate([
            'parent_id' => ['nullable', 'exists:categories,id'],
            'name' => ['required', 'unique:categories,name'],
        ]);

        if ($request->parent_id) {
            $parentCategory = Category::find($request->parent_id);

            if ($parentCategory->parent_id !== null) {
                return response()->json(
                    data: [
                        'status' => false,
                        'message' => 'Can not a subcategory to another subcategory.'
                    ],
                    status: 422,
                );
            }
        }

        $category = Category::create([
            'parent_id' => $request->parent_id,
            'name' => $request->name,
        ]);

        return response()->json(['message' => 'Category created successfully!', 'category' => $category], 201);
    }

    /**
     * Update a category or subcategory
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $request->validate([
            'name' => ['required', Rule::unique('categories', 'name')->ignore($category->id)],
        ]);

        $category->name = $request->name;

        if (!$category->save()) {
            return response()->json(
                data: ['message' => 'Encountered error saving changes.'],
                status: 400,
            );
        }

        return response()->json(['message' => 'Category updated successfully!', 'category' => $category], 200);
    }

    /**
     * Delete the category or subcategory.
     *
     * Must not be used by products or have subcategories or the delete will fail.
     *
     * @param Request $request
     * @param Category $category
     * @return JsonResponse
     */
    public function destroy(Request $request, Category $category): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        try {
            $category->delete();

            return response()->json(['message' => 'Category delete successfully!'], 200);
        } catch (\Throwable $th) {
            return response()->json(['message' => 'Category delete failed, make sure the category you are trying to delete does not have subcategories or products in it.'], 400);
        }
    }
}
