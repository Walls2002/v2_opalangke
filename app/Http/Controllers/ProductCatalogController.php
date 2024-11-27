<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request, string $location)
    {
        $location = Location::find($location);

        if (!$location) {
            return response()->json(['message' => 'location not found.'], 404);
        }

        $products = Product::query()
            ->whereRelation('store', 'location_id', '=', $location->id)
            ->get();

        return response()->json($products);
    }
}
