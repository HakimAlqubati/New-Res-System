<?php

namespace App\Filament\Resources\PurchaseInvoiceReportResource\Reports\Pages;

use App\Filament\Resources\Reports\StoresReportResource;
use App\Models\Branch;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Filter;
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
        // $current_date = date('Y-m-d');  // Get the current date

        // // Get the start date of the current month
        // $start_of_month = date('Y-m-01', strtotime($current_date));

        // // Get the end date of the current month
        // $end_of_month = date('Y-m-t', strtotime($current_date));

        return [
            SelectFilter::make("branch_id")
                ->label(__('lang.branch'))
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Branch::get()->pluck('name', 'id')),
            Filter::make('date')
                ->form([
                    DatePicker::make('start_date')
                        ->label(__('lang.start_date')),
                    DatePicker::make('end_date')
                        ->label(__('lang.end_date')),
                ])
                ->query(function (Builder $query, array $data): Builder {
                    return $query;
                }),
            SelectFilter::make("product_id")
                ->label(__('lang.product'))
                ->multiple()
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Product::where('active', 1)->get()->pluck('name', 'id')),
        ];
    }


    protected function getViewData(): array
    {

        // $current_date = date('Y-m-d');  // Get the current date

        // // Get the start date of the current month
        // $start_of_month = date('Y-m-01', strtotime($current_date));

        // // Get the end date of the current month
        // $end_of_month = date('Y-m-t', strtotime($current_date));
        $product_ids = [];
        $branch_id = __filament_request_select('branch_id', 'all');
        $product_ids = __filament_request_select_multiple('product_id', [], true);
        $start_date =  __filament_request_key("date.start_date", null);
        $end_date = __filament_request_key("date.end_date", null);

        $branch_store_report_data = [];
        $total_quantity = 0;
        $branch_store_report_data = $this->getBranchStoreReportData($branch_id, $start_date, $end_date, $product_ids);

        if (count($branch_store_report_data) > 0) {
            $total_quantity = array_reduce($branch_store_report_data->toArray(), function ($carry, $item) {
                return $carry + $item->total_quantity;
            }, 0);
        }

        $start_date = (!is_null($start_date) ? date('Y-m-d', strtotime($start_date))  : __('lang.date_is_unspecified'));
        $end_date = (!is_null($end_date) ? date('Y-m-d', strtotime($end_date))  : __('lang.date_is_unspecified'));
        return [
            'branch_store_report_data' => $branch_store_report_data,
            'branch_id' => $branch_id,
            'total_quantity' => $total_quantity,
            'start_date' => $start_date,
            'end_date' => $end_date,
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    public function getBranchStoreReportData($branch_id, $start_date, $end_date, $product_ids)
    {

        $results = [];
        if (isset($branch_id) && is_numeric($branch_id)) {
            $query = DB::table('orders_details')
                ->select(
                    'products.id as product_id',
                    DB::raw("IF(JSON_VALID(products.name), REPLACE(JSON_EXTRACT(products.name, '$." . app()->getLocale() . "'), '\"', ''), products.name) AS product_name"),
                    'units.name as unit_name',
                    DB::raw('SUM(orders_details.available_quantity) as total_quantity')
                )
                ->join('orders', 'orders_details.order_id', '=', 'orders.id')
                ->join('products', 'orders_details.product_id', '=', 'products.id')
                ->join('units', 'orders_details.unit_id', '=', 'units.id')
                ->whereIn('orders.status', [
                    Order::DELEVIRED,
                    Order::READY_FOR_DELEVIRY
                ]);
            if (!is_null($start_date) && !is_null($end_date)) {
                $query->whereBetween('orders.created_at', [$start_date, $end_date]);
            }
            $query->where('orders.branch_id', $branch_id);
            if (count($product_ids) > 0) {
                $query->whereIn('orders_details.product_id', $product_ids);
            }
            $results = $query->groupBy('products.id', 'products.name', 'units.id', 'units.name', 'orders.branch_id')
                ->get();
        }
        return $results;
    }
}
