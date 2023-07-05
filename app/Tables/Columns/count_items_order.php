<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class count_items_order extends Column
{
    protected string $view = 'tables.columns.count_items_order';
    public function getState()
    {
        return $this?->record?->orderDetails?->count();
    }
}
