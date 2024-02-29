<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\User;
use Filament\Notifications\Notification;

class OrderObserver
{
    public function created(Order $order)
    {
        // $recipient = User::role([1, 3])->pluck('id');

        // Notification::make()
        //     ->title('Order no ' . $order->id . ' Has been created')
        //     ->sendToDatabase($recipient)
        // ->broadcast($recipient);
    }
    public function updated(Order $order)
    {
        // if (in_array($order->status, [Order::DELEVIRED, Order::READY_FOR_DELEVIRY])) {
        //     OrderDetails::where('order_id', $order->id)->update(['available_in_store' => 1]);
        // }
    }
}
