<?php

namespace App\Tables\Columns;
 
use App\Models\OrderTransfer;
use Filament\Tables\Columns\Column;

class TotalTransfer extends Column
{
    protected string $view = 'tables.columns.total_order';
    public function getState()
    {
        $order = OrderTransfer::withSum('orderDetails', 'price')->find($this?->record?->id);
        $sum = $order->order_details_sum_price;
        return $sum;
    }
}
