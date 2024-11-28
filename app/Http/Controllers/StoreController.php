<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Location;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class StoreController extends Controller
{
    /**
     * Display a listing of the stores.
     */
    public function index(Request $request, Store $store)
    {
        $store = Store::query()
            ->with(['vendor', 'location'])
            ->where('vendor_id', $request->user()->id)
            ->first();

        if (!$store) {
            return response()->json(['message' => 'Logged user does not have a store.'], 404);
        }
        return response()->json($store);
    }

    /**
     * Show the form for creating a new store.
     */
    public function create()
    {
        $vendors = User::where('role', 'vendor')->get();
        $locations = Location::all();
        return response()->json(['vendors' => $vendors, 'locations' => $locations]);
    }

    /**
     * Store a newly created store in storage.
     */
    public function store(Request $request)
    {
        // Ensure you are passing an `id` to update an existing record
        $id = $request->input('id');

        // If `id` exists, perform an update, otherwise create a new store
        if ($id) {
            // Find the store by ID, or fail with an exception
            $store = Store::findOrFail($id);

            // Validate request data
            $validated = $request->validate([
                'store_name' => 'required|string|max:255',
                'street' => 'nullable|string|max:255',
                'contact_number' => 'nullable|string|max:20',
                'location_id' => 'required|integer|exists:locations,id',
                'vendor_id' => 'required|integer|exists:users,id',
                'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            // Check if image exists, then upload
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('stores', 'public');
                $validated['image'] = $imagePath;
            }

            // Update the store with the validated data
            $store->update($validated);

            return response()->json([
                'message' => 'Store updated successfully.',
                'store' => $store,
            ], 200);
        } else {
            // Otherwise, create a new store (if no id is passed)
            $validated = $request->validate([
                'vendor_id' => 'required|exists:users,id',
                'location_id' => 'required|exists:locations,id',
                'store_name' => 'required|string|max:255',
                'image' => 'nullable|image|max:2048',
                'street' => 'required|string|max:255',
                'contact_number' => 'required|string|max:20',
            ]);

            if ($request->hasFile('image')) {
                $validated['image'] = $request->file('image')->store('stores', 'public');
            }

            // Create the new store
            $store = Store::create($validated);

            return response()->json($store, 201);
        }
    }


    // /**
    //  * Display the specified store.
    //  */
    // public function show(Store $store)
    // {
    //     $store->load(['vendor', 'location']);
    //     return response()->json($store);
    // }

    /**
     * Display the logged user's store.
     */
    // public function show(Request $request, Store $store)
    // {
    //     $store = Store::query()
    //         ->with(['vendor', 'location'])
    //         ->where('vendor_id', $request->user()->id)
    //         ->first();

    //     if (!$store) {
    //         return response()->json(['message' => 'Logged user does not have a store.'], 404);
    //     }
    //     return response()->json($store);
    // }

    /**
     * Update the specified store in storage.
     */


    /**
     * Remove the specified store from storage.
     */
    public function destroy(Store $store)
    {
        $store->delete();
        return response()->json(['message' => 'Store deleted successfully.']);
    }
}
