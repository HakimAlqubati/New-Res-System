<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderDetails;

class OrderObserver
{
    public function created(Order $order)
    {
    }
    public function updated(Order $order)
    {
        if (in_array($order->status, [Order::DELEVIRED, Order::READY_FOR_DELEVIRY])) {
            OrderDetails::where('order_id', $order->id)->update(['available_quantity' => 1]);
        }
    }
}
