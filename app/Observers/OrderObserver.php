<?php


namespace App\Observers;

use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class OrderObserver
{
    public function created(Order $order)
    {
        if (stripos($order->note, 'PREVIEW') === false) {
            $this->notifyIfPending($order, 'New order has been created!', 'Order Created');
        }
    }

    public function updated(Order $order)
    {
        // Check if status changed and is now 1 (pending)
        if ($order->isDirty('status') && $order->status->value === 1) {
            $this->notifyIfPending($order, 'Your order is now pending!', 'Order Pending');
        } else if ($order->isDirty('status') && $order->status->value == 2) {
            $this->notifyIfPending($order, 'Your order is now Confirmed!', 'Order Confirmed');
        } else if ($order->isDirty('status') && $order->status->value == 4) {
            $this->notifyIfPending($order, 'Your order is Assigned to a rider!', 'Order Update');
        } else if ($order->isDirty('status') && $order->status->value == 5) {
            $this->notifyIfPending($order, 'Your order has been delivered!', 'Order Delivered');
        } else if ($order->isDirty('status') && $order->status->value == 6) {
            $this->notifyIfPending($order, 'Your order has been canceled!', 'Order Canceled');
        }

        Log::info('Order updated', [
            'id' => $order->id,
            'token' => $user->expo_push_token ?? 'No token',
            'status' => $order->status,
            'was' => $order->getOriginal('status'),
            'isDirty' => $order->isDirty('status'),
        ]);
    }

    private function notifyIfPending(Order $order, string $message, string $title)
    {
        $user = $order->user;


        if ($user && $user->expo_push_token) {
            $response = Http::post('https://exp.host/--/api/v2/push/send', [
                'to' => $user->expo_push_token,
                'title' => $title,
                'body' => $message,
                'subtitle' => 'Check your orders for details.',
                'priority' => 'high',

            ]);
        }
    }
}
