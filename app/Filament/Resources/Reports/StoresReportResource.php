<?php

namespace App\Filament\Resources\Reports;

use App\FakeModelReports\Models\StoreReportReport;
use App\Filament\Resources\PurchaseInvoiceReportResource\Reports\Pages\ListStoresReport;
use Filament\Resources\Resource;

class StoresReportResource extends Resource
{
    protected static ?string $model = StoreReportReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    /**
     * @deprecated Use `getModelLabel()` instead.
     */
    public static function getLabel(): ?string
    {
        return __('lang.stores_report');
    }
    protected static function getNavigationLabel(): string
    {
        return __('lang.stores_report');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListStoresReport::route('/'),
        ];
    }
}
