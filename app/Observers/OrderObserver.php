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
        if ($order->status == Order::DELEVIRED) {

            OrderDetails::where('order_id', $order->id)->update([
                'available_quantity' => 1
            ]);
        }
    }
}
