<?php

namespace App\Tables\Columns;

use App\Models\OrderDetails;
use App\Models\OrderTransfer;
use Filament\Tables\Columns\Column;
use Illuminate\Support\Facades\DB;

class TotalTransfer extends Column
{
    protected string $view = 'tables.columns.total_order';
    public function getState()
    {
        // $totalPrice = OrderDetails::where('order_id', $this?->record?->id)
        //     ->where('quantity', '>', 0)
        //     ->where('available_quantity', '>', 0)
        //     ->sum('price');
        // return $totalPrice;
        $order = OrderTransfer::withSum('orderDetails', 'price')->find($this?->record?->id);

        $sum = $order->order_details_sum_price;
        return $sum;
    }
}
