<?php

namespace App\Filament\Resources;

use App\FakeModelReports\Models\PurchaseInvoiceReport;
use App\Filament\Resources\PurchaseInvoiceReportResource\Pages\ListPurchaseInvoiceReport;
use App\Filament\Resources\StockReportResource\RelationManagers;
use Filament\Forms;
use Filament\Resources\Form;
use Filament\Resources\Resource;
use Filament\Resources\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

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

    public static function getPages(): array
    {
        return [
            'index' => ListPurchaseInvoiceReport::route('/'),
        ];
    }
}
