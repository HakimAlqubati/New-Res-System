<?php

namespace App\Filament\Resources\PurchaseInvoiceReportResource\Reports\Pages;

use App\Filament\Resources\Reports\StoresReportResource;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;

use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;

class ListBranchStoreReport extends ListRecords
{
    protected static string $resource = StoresReportResource::class;
    protected static string $view = 'filament.pages.stock-report.branch-store-report';


    protected function getTitle(): string
    {
        return __('lang.branch_store_report');
    }
    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make("branch_id")
                ->label(__('lang.branch'))
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Branch::get()->pluck('name', 'id')),
            // SelectFilter::make("product_id")
            //     ->label(__('lang.product'))
            //     ->searchable()
            //     ->multiple()
            //     ->query(function (Builder $q, $data) {
            //         return $q;
            //     })->options(Product::get()->pluck('name', 'id')),
        ];
    }


    protected function getViewData(): array
    {
        $branch_id = __filament_request_select('branch_id', 'all');
        $branch_store_report_data = [];
        $total_quantity = 0;
        $branch_store_report_data = $this->getBranchStoreReportData($branch_id);

        if (count($branch_store_report_data) > 0) {
            $total_quantity = array_reduce($branch_store_report_data->toArray(), function ($carry, $item) {
                return $carry + $item->total_quantity;
            }, 0);
        }

        return [
            'branch_store_report_data' => $branch_store_report_data,
            'branch_id' => $branch_id,
            'total_quantity' => $total_quantity,
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    public function getBranchStoreReportData($branch_id)
    {
        $results = [];
        if (isset($branch_id) && is_numeric($branch_id)) {
            $results = DB::table('orders_details')
                ->select(
                    'products.id as product_id',
                    DB::raw("IF(JSON_VALID(products.name), REPLACE(JSON_EXTRACT(products.name, '$.ar'), '\"', ''), products.name) AS product_name"),
                    'units.name as unit_name',
                    DB::raw('SUM(orders_details.available_quantity) as total_quantity')
                )
                ->join('orders', 'orders_details.order_id', '=', 'orders.id')
                ->join('products', 'orders_details.product_id', '=', 'products.id')
                ->join('units', 'orders_details.unit_id', '=', 'units.id')
                ->whereIn('orders.status', [
                    Order::DELEVIRED,
                    Order::READY_FOR_DELEVIRY
                ])
                // ->whereBetween('orders.created_at', ['2024-01-01', '2024-04-31'])
                ->where('orders.branch_id', $branch_id)
                ->groupBy('products.id', 'products.name', 'units.id', 'units.name', 'orders.branch_id')
                ->get();
        }
        return $results;
    }
}
