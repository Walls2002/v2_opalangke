<?php

namespace App\Http\Controllers;

use App\Enums\OrderStatus;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use App\Models\User;
use App\Models\UserVoucher;
use App\Models\Voucher;
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
            'voucher_code' => ['nullable', 'exists:vouchers,code'],
        ]);

        DB::beginTransaction();
        try {
            $customer = $request->user();

            $cartItems = $this->getCartItemsFromStore($customer, $store->id);

            if ($request->voucher_code) {
                $voucher = $this->verifyVoucher($customer, $cartItems, $request->voucher_code);
            }

            $order = $this->createOrder($customer, $store, $cartItems, $request->address, $request->note, $voucher ?? null);
            $this->createOrderItems($order, $store, $cartItems);
            $this->clearCartItems($cartItems);

            DB::commit();

            return response()->json(['message' => 'Check out success', 'order' => $order], 201);
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Throwable $th) {
            DB::rollBack();

            logger('checkout error', ['error' => $th]);

            return response()->json(['message' => 'Encountered an error while checking out.'], 400);
        }
    }

    private function verifyVoucher(User $customer, Collection $cartItems, string $voucherCode): Voucher
    {
        $userVoucher = UserVoucher::with(['voucher'])
            ->where('user_id', $customer->id)
            ->where('used_at', null)
            ->where('expired_at', '>', now())
            ->whereRelation('voucher', 'code', '=', $voucherCode)
            ->whereRelation('voucher', 'is_deleted', '=', false)
            ->orderBy('expired_at', 'ASC')
            ->first();

        if (!$userVoucher) {
            throw new \InvalidArgumentException('This voucher is invalid, and can not be used.');
        }

        $voucher = $userVoucher->voucher;
        $totalPrice = 0;

        foreach ($cartItems as $item) {
            $totalPrice += $item->quantity * $item->product->price;
        }

        if ($totalPrice < $voucher->min_order_price) {
            throw new \InvalidArgumentException('Total price does not meet the minimum spending cost of the voucher.');
        }

        $userVoucher->used_at = now();
        if (!$userVoucher->save()) {
            throw new \InvalidArgumentException('Encountered an error applying the voucher.');
        }

        return $voucher;
    }

    private function getCartItemsFromStore(User $customer, string $storeId): Collection
    {
        $cartItems = Cart::query()
            ->with(['product'])
            ->where('user_id', $customer->id)
            ->where('store_id', $storeId)
            ->get();

        if ($cartItems->count() <= 0) {
            throw new \InvalidArgumentException("There must be at least one product to create a order.");
        }

        return $cartItems;
    }

    private function createOrder(User $customer, Store $store, Collection $cartItems, string $address, string $note, ?Voucher $voucher): Order
    {
        $shippingFee = $store->location->shipping_fee;
        $totalItemPrice = 0;

        foreach ($cartItems as $item) {
            $totalItemPrice += $item->quantity * $item->product->price;
        }

        if ($voucher) {
            if ($voucher->is_percent) {
                $voucherDiscount = $voucher->value / 100;
                $discount = round($totalItemPrice * $voucherDiscount);
                $finalPrice = round($totalItemPrice - $discount, 2);
            } else {
                $voucherDiscount = $voucher->value;
                $discount = $voucher->value;
                $finalPrice = round($totalItemPrice - $voucherDiscount, 2);
            }
        } else {
            $finalPrice = $totalItemPrice;
        }

        $finalPrice += $shippingFee;
        $finalPrice = $finalPrice < 0 ? 0 : $finalPrice;

        $order = Order::create([
            'user_id' => $customer->id,
            'store_id' => $store->id,
            'address' => $address,
            'note' => $note,
            'status' => OrderStatus::PENDING,
            'total_item_price' => $totalItemPrice,
            'final_price' => $finalPrice,
            'shipping_fee' => $shippingFee,
            'discount' => $discount,
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

    /**
     * Preview the checkout of items in the cart.
     *
     * @param Request $request
     * @param Store $store
     * @return JsonResponse
     */
    public function storePreview(Request $request, Store $store): JsonResponse
    {
        $request->validate([
            'address' => ['required', 'string', 'max:255'],
            'note' => ['nullable', 'string', 'max:255'],
            'voucher_code' => ['nullable', 'exists:vouchers,code'],
        ]);

        DB::beginTransaction();
        try {
            $customer = $request->user();

            $cartItems = $this->getCartItemsFromStore($customer, $store->id);

            if ($request->voucher_code) {
                $voucher = $this->verifyVoucher($customer, $cartItems, $request->voucher_code);
            }

            $order = $this->createOrder($customer, $store, $cartItems, $request->address, $request->note, $voucher ?? null);
            $this->createOrderItems($order, $store, $cartItems);
            $this->clearCartItems($cartItems);

            DB::rollBack();

            return response()->json(['message' => 'Check out preview displayed.', 'order' => $order], 200);
        } catch (\InvalidArgumentException $e) {
            DB::rollBack();

            return response()->json(['message' => $e->getMessage()], 400);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => 'Encountered an error while previewing the check out.'], 400);
        }
    }
}