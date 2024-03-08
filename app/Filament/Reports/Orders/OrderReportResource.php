<?php

namespace App\Filament\Reports\Orders;

use App\Core\BaseAuthResource;
use App\Filament\Reports\Orders\Pages\ListOrderReport;
use App\Models\Orders\OrderReport;
use Filament\Resources\Resource;

class OrderReportResource extends  Resource
{

    public static ?string $permissionSlug = "order_report";
    protected static ?string $model = OrderReport::class;
    protected static ?string $navigationIcon = 'heroicon-o-collection';
    protected static ?string $slug = 'order-report';
  
 
    public static function getPages(): array
    {
        return [
            'order-report' => ListOrderReport::route('order-report'),
        ];
    }

    public static function getPluralModelLabel(): string
    {
        return 'Order_report';
    }

    public static function getModelLabel(): string
    {
        return 'Order_report';
    }
}
