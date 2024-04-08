<?php

namespace App\Filament\Resources\Reports;

use App\Models\FakeModelReports\PurchaseInvoiceReport;
use App\Filament\Resources\PurchaseInvoiceReportResource\Reports\Pages\ListPurchaseInvoiceReport; 
use Filament\Resources\Resource; 

class PurchaseInvoiceReportResource extends Resource
{
    protected static ?string $model = PurchaseInvoiceReport::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    /**
     * @deprecated Use `getModelLabel()` instead.
     */
    public static function getLabel(): ?string
    {
        return __('lang.purchase_invoice_report');
    }
    protected static function getNavigationLabel(): string
    {
        return __('lang.purchase_invoice_report');
    }

    public static function getPluralLabel(): ?string
    {
        return __('lang.purchase_invoice_report');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseInvoiceReport::route('/'),
        ];
    }
}
