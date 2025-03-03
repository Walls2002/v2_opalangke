<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function publicIndex(Request $request)
    {
        $query = Product::with(['category', 'store.location']);

        $products = $query->get();

        return response()->json(ProductResource::collection($products));
    }

    public function index(Request $request)
    {
        $query = Product::with(['category', 'store'])
            ->whereRelation(
                'store',
                'location_id',
                '=',
                $request->user()->location_id
            );

        $products = $query->get();

        return response()->json(ProductResource::collection($products));
    }
}