<?php

namespace App\Filament\Resources\OrderReportsResource;

use App\Filament\Resources\OrderReportsResource\Pages\ListReportProductQuantities;
use App\Models\FakeModelReports\ReportProductQuantities;
use Filament\Resources\Resource;

class ReportProductQuantitiesResource extends Resource
{
    protected static ?string $model = ReportProductQuantities::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    /**
     * @deprecated Use `getModelLabel()` instead.
     */
    public static function getLabel(): ?string
    {
        return __('lang.report_product_quantities');
    }
    protected static function getNavigationLabel(): string
    {
        return __('lang.report_product_quantities');
    }

    public static function getPluralLabel(): ?string
    {
        return __('lang.report_product_quantities');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReportProductQuantities::route('/'),
        ];
    }
}
