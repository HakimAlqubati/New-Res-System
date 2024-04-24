<?php

namespace App\Filament\Resources\PurchaseInvoiceReportResource\Reports\Pages;

use App\Filament\Resources\Reports\StoresReportResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;

use Filament\Forms\Components\Builder;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;

class ListStoresReport extends ListRecords
{
    protected static string $resource = StoresReportResource::class;
    protected static string $view = 'filament.pages.stock-report.stores-report';


    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make("store_id")
                ->label(__('lang.store'))

                ->default(getDefaultStore())
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Store::get()->pluck('name', 'id')),

            SelectFilter::make("supplier_id")
                ->label(__('lang.supplier'))
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Supplier::get()->pluck('name', 'id')),

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
        $store_id = __filament_request_select('store_id', 'all');
        $supplier_id = __filament_request_select('supplier_id', 'all');
        $product_id = __filament_request_select('product_id', 'all');

        $stores_report_data = $this->getStoresReportData($product_id, $store_id, $supplier_id);



        return [
            'stores_report_data' => $stores_report_data,
            'store_id' => $store_id,
            'supplier_id' => $supplier_id,
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }

    public function getStoresReportData($product_id, $store_id, $supplier_id)
    {

        $subquery1 = DB::table('purchase_invoice_details')
            ->select([
                'purchase_invoice_details.product_id AS product_id',
                DB::raw("IF(JSON_VALID(products.name), REPLACE(JSON_EXTRACT(products.name, '$." . app()->getLocale() . "'), '\"', ''), products.name) AS product_name"),
                'units.name AS unit_name',
                DB::raw('SUM(purchase_invoice_details.quantity) AS purchase_quantity')
            ])
            ->join('purchase_invoices', 'purchase_invoice_details.purchase_invoice_id', '=', 'purchase_invoices.id')
            ->join('products', 'purchase_invoice_details.product_id', '=', 'products.id')
            ->join('units', 'purchase_invoice_details.unit_id', '=', 'units.id');
        if (isset($store_id) && $store_id != '' && $store_id != 0 && $store_id != 'all') {
            $subquery1->where('purchase_invoices.store_id', $store_id);
        }

        if (isset($supplier_id) && $supplier_id != '' && $supplier_id != 0 && $supplier_id != 'all') {
            $subquery1->where('purchase_invoices.supplier_id', $supplier_id);
        }

        $subquery1 = $subquery1->groupBy('purchase_invoice_details.product_id', 'purchase_invoice_details.unit_id', 'products.name', 'units.name');

        $subquery2 = DB::table('orders_details')
            ->select([
                'orders_details.product_id AS product_id',
                DB::raw('SUM(orders_details.available_quantity) AS ordered_quantity')
            ])
            ->join('orders', 'orders_details.order_id', '=', 'orders.id')
            ->where('orders.created_at', '>=', DB::raw("DATE('2024-03-11')"))
            ->whereIn('orders.status', [
                Order::READY_FOR_DELEVIRY,
                Order::DELEVIRED
            ])
            ->groupBy('orders_details.product_id', 'orders_details.unit_id');

        $query = DB::table(DB::raw("({$subquery1->toSql()}) AS p"))
            ->leftJoin(DB::raw("({$subquery2->toSql()}) AS o"), 'p.product_id', '=', 'o.product_id')
            ->mergeBindings($subquery1)
            ->mergeBindings($subquery2)
            ->select([
                'p.product_id',
                'p.product_name',
                'p.unit_name',
                DB::raw('COALESCE(p.purchase_quantity, 0) AS income'),
                DB::raw('COALESCE(o.ordered_quantity, 0) AS ordered'),
                DB::raw('(COALESCE(p.purchase_quantity, 0) - COALESCE(o.ordered_quantity, 0)) AS remaining')
            ]);

        $results = $query->get();
        return $results;
    }
}
