<?php

// namespace App\Filament\Widgets;
namespace App\Filament\UnusedWidgets;

use App\Models\Order;
use Closure;
use Filament\Tables;
use Filament\Widgets\TableWidget as BaseWidget;
use Illuminate\Database\Eloquent\Builder;

class LatestOrders extends BaseWidget
{
    protected function getTableQuery(): Builder
    {
        return Order::query()->latest();
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('customer.name')
                ->label('Customer'),
        ];
    }
    protected function getTableRecordsPerPageSelectOptions(): array
    {
        return [10, 25, 30, 50];
    }
}
