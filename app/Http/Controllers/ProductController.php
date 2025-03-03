<?php

namespace App\Http\Controllers;

use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{

    public function index(Request $request)
    {
        // Validate that the store_id is provided in the request
        $request->validate([
            'store_id' => 'required|exists:stores,id',
        ]);

        // Get the store based on the provided store_id
        $store = Store::find($request->store_id);

        // Ensure the authenticated user is the vendor of the store
        if (Auth::user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Fetch products belonging to the store
        $products = Product::where('store_id', $request->store_id)->with(['store', 'category'])->get();

        return response()->json(ProductResource::collection($products));
    }


    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'store_id' => 'required|exists:stores,id',
            'name' => 'required|string|max:255',
            'measurement' => 'required|string|max:30',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $store = Store::find($request->store_id);

        if ($request->user()->id !== $store->vendor_id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product = Product::create($data);

        return response()->json(['message' => 'Product created successfully', 'product' => $product], 201);
    }

    public function update(Request $request, Product $product)
    {
        $user = $request->user();
        $product->load('store');
        $store = $product->store;

        if ($store->vendor_id != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'measurement' => 'required|string|max:30',
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->quantity = $request->quantity;

        if ($request->hasFile('image')) {
            $fileName = $request->file('image')->store('products', 'public');
            $product->image = $fileName;
        }

        if (!$product->save()) {
            return response()->json(['message' => 'Encountered an error updating the product.'], 400);
        }

        return response()->json(['message' => 'Product updated successfully', 'product' => $product], 201);
    }

    public function destroy(Request $request, Product $product)
    {
        $user = $request->user();
        $product->load('store');
        $store = $product->store;

        if ($store->vendor_id != $user->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }


        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return response()->json(['message' => 'Product deleted successfully']);
    }
}