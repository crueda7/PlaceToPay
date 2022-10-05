<?php

namespace App\Repositories;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\ShoppingCart;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class OrderRepositoryImpl implements \App\Interfaces\OrderRepository
{

    public function orders(): Collection
    {
        return Order::with(['orderDetails.product'])
            ->where('orders.user_id', '=', Auth::user()->id)
            ->orderBy('created_at', 'DESC')->get();
    }

    public function order(): Order
    {
        $user = Auth::user();
        $order = new Order;
        $order['customer_name'] = $user['name'];
        $order['customer_email'] = $user['email'];
        $order['user_id'] = $user['id'];
        return $order;
    }

    public function orderById(int $orderId): Order
    {
        return Order::find($orderId);
    }

    public function save(array $newOrder): Order
    {
        $newOrder['user_id'] = Auth::user()->id;

        $order = Order::create($newOrder);

        collect($newOrder['cart'])
            ->map(function ($array) use ($order) {
                $orderDetail = new OrderDetail([
                    'quantity' => 1,
                    'product_id' => $array['product_id'],
                    'order_id' => $order->id,
                ]);
                $orderDetail->save();
                $cart = ShoppingCart::find($array['id']);
                if ($cart)
                    $cart->delete();
            });
        return $order;
    }

    public function pendingOrders(): Collection
    {
        return Order::where('status', '=', 'PENDING')->orWhere('status', 'OK')->get();
    }
}
