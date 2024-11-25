<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    /**
     * Checkout the items in the cart.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function store(Request $request, Store $store): JsonResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        DB::beginTransaction();
        try {
            $customer = $request->user();

            $cartItems = $this->getCartItemsFromStore($customer, $store->id);
            $order = $this->createOrder($customer, $store, $cartItems, $request->address, $request->note);
            $this->createOrderItems($order, $store, $cartItems);
            $this->clearCartItems($cartItems);

            DB::commit();

            return response()->json(['message' => 'Check out success', 'order' => $order], 200);
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Throwable $th) {
            DB::rollBack();

            logger('checkout error', ['error' => $th]);

            return response()->json(['message' => 'Encountered an error while checking out.'], 400);
        }
    }

    private function getCartItemsFromStore(User $customer, string $storeId): Collection
    {
        $cartItems = Cart::query()
            ->with(['product'])
            ->where('user_id', $customer->id)
            ->where('store_id', $storeId)
            ->get();

        if ($cartItems->count() < 0) {
            throw new \InvalidArgumentException("There must be at least one product to create a order.");
        }

        return $cartItems;
    }

    private function createOrder(User $customer, Store $store, Collection $cartItems, string $address, string $note): Order
    {
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item->quantity * $item->product->price;
        }

        $order = Order::create([
            'user_id' => $customer->id,
            'store_id' => $store->id,
            'address' => $address,
            'note' => $note,
            'status' => OrderStatus::PENDING,
            'total_price' => $totalPrice,
        ]);

        if (!$order->id) {
            throw new \Exception("Encountered an error creating the order.");
        }

        return $order;
    }

    private function createOrderItems(Order $order, Store $store, Collection $cartItems): void
    {
        foreach ($cartItems as $item) {
            $orderItem = new OrderItem();
            $orderItem->order_id = $order->id;
            $orderItem->name = "{$item->product->name}";
            $orderItem->unit_price = $item->product->price;
            $orderItem->quantity = $item->quantity;


            if (!$orderItem->save()) {
                throw new \Exception("Encountered an error creating the order item.");
            }

            $product = $item->product;
            $product->quantity -= $item->quantity;

            if ($product->quantity < 0) {
                throw new \InvalidArgumentException("Vendor does not have enough {$product->name} in stock.");
            }

            if (!$product->save()) {
                throw new \Exception("Encountered an error updating product quantities.");
            }
        }
    }

    private function clearCartItems(Collection $cartItems): void
    {
        $cartItems->each(function (Cart $cart) {
            if (!$cart->delete()) {
                throw new \Exception("Encountered an error clearing the cart.");
            }
        });
    }
}
