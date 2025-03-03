<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Http\Resources\ProductResource;
use App\Http\Resources\StoreOrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AdminStoreController extends Controller
{
    /**
     * Show all the stores.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeIndex(Request $request): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $store = Store::query()
            ->with(['vendor', 'location'])
            ->get();

        return response()->json(['stores' => $store]);
    }

    /**
     * Show the store.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function storeShow(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $store->load(['vendor', 'location']);

        return response()->json(['stores' => $store]);
    }

    /**
     * Show the store products.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function productIndex(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $query = Product::with(['category', 'store'])
            ->where('store_id', $store->id);

        $products = $query->get();

        return response()->json(ProductResource::collection($products));
    }

    /**
     * Show the store product.
     *
     * @param Request $request
     * @param Store $store
     * @param Product $product
     * @return JsonResponse
     */
    public function productShow(Request $request, Store $store, Product $product): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $query = Product::with(['category', 'store'])
            ->where('id', $product->id)
            ->where('store_id', $store->id);

        $product = $query->first();

        return response()->json(new ProductResource($product));
    }

    /**
     * Show the store orders.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function orderIndex(Request $request, Store $store): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        $query = Order::query()
            ->with(['items', 'user', 'rider'])
            ->where('store_id', $store->id)
            ->orderBy('created_at', 'desc');

        $data = $query->get();

        return response()->json(['message' => 'Vendor orders fetched.', 'orders' => StoreOrderResource::collection($data)], 200);
    }

    /**
     * Show the order.
     *
     * @param Request $request
     * @param Store $store
     * @param Order $order
     * @return JsonResponse
     */
    public function orderShow(Request $request, Store $store, Order $order): JsonResponse
    {
        if ($request->user()->role !== 'admin') {
            return response()->json(
                data: ['message' => 'Unauthorized.'],
                status: 422,
            );
        }

        if ($order->store_id !== $store->id) {
            return response()->json(
                data: ['message' => 'Store and order store id does not match.'],
                status: 422,
            );
        }

        $order->load(['items', 'user', 'rider', 'userVoucher.voucher']);

        return response()->json(['message' => 'Order fetched.', 'order' => new StoreOrderResource($order)], 200);
    }
}
