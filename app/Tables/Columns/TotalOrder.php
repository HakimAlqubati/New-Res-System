<?php

namespace App\Tables\Columns;

use App\Models\Order;
use Filament\Tables\Columns\Column;

class TotalOrder extends Column
{
    protected string $view = 'tables.columns.total_order';
    public function getState()
    {
        $order = Order::withSum('orderDetails', 'price')->find($this?->record?->id);
        
        $sum = $order?->order_details_sum_price;
        return $sum;
    }
}
