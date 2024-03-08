<?php

namespace App\Filament\Reports\Orders\Pages;

use App\Filament\Reports\Orders\OrderReportResource;
use Filament\Resources\Pages\ListRecords;

class ListOrderReport  extends ListRecords
{
    protected static string $resource = OrderReportResource::class;
    protected static string $view = 'vendor.filament.components.reports.order-report';
    protected function getTableFilters(): array
    {
        return [];
    }
    protected function getViewData(): array
    {
        return [];
    }
}
