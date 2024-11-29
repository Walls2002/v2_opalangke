<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductCatalogController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query()
            ->with(['store.location']);

        if ($request->location_id) {
            $location = Location::find($request->location_id);

            if (!$location) {
                return response()->json(['message' => 'location not found.'], 404);
            }

            $query->whereRelation('store', 'location_id', '=', $location->id);
        }

        $products = $query->get();

        return response()->json($products);
    }
}
