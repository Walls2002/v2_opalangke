<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function publicIndex(Request $request)
    {
        $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'include_subcategories' => ['nullable', 'boolean'],
        ]);

        $query = Product::with(['category', 'store.location']);

        if ($request->category_id) {
            $category = Category::with(['children'])->find($request->category_id);

            if ($request->boolean('include_subcategories') === false) {
                $query->where('category_id', $category->id);
            } else {
                $categoryIds = $category->children->map(fn(Category $subCategory) => $subCategory->id);
                $categoryIds[] = $category->id;

                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products = $query->get();

        return response()->json(ProductResource::collection($products));
    }

    public function index(Request $request)
    {
        $request->validate([
            'category_id' => ['nullable', 'exists:categories,id'],
            'include_subcategories' => ['nullable', 'boolean'],
        ]);

        $query = Product::with(['category', 'store.location'])
            ->whereRelation(
                'store',
                'location_id',
                '=',
                $request->user()->location_id
            );

        if ($request->category_id) {
            $category = Category::with(['children'])->find($request->category_id);

            if ($category->parent_id === null) {
                $query->where('category_id', $category->id);
            } else {
                $categoryIds = $category->children->map(fn(Category $subCategory) => $subCategory->id);
                $categoryIds[] = $category->id;

                $query->whereIn('category_id', $categoryIds);
            }
        }

        $products = $query->get();

        return response()->json(ProductResource::collection($products));
    }

    public function show(Request $request, Product $product)
    {
        return response()->json([
            'message' => 'Product found',
            'product' => $product,
        ], 200);
    }
}
