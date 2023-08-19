<?php

namespace App\Tables\Columns;

use Filament\Tables\Columns\Column;

class CountItemsTransfer extends Column
{
    protected string $view = 'tables.columns.count_items_transfer';
    public function getState()
    {
        return $this?->record?->orderDetails?->count();
    }
}
