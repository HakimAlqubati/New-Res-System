<?php

namespace App\Filament\Resources\PurchaseInvoiceReportResource\Reports\Pages;

use App\Filament\Resources\Reports\PurchaseInvoiceReportResource;
use App\Models\Product;
use App\Models\Store;
use App\Models\Supplier;

use Filament\Forms\Components\Builder; 
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;

class ListPurchaseInvoiceReport extends ListRecords
{
    protected static string $resource = PurchaseInvoiceReportResource::class;
    protected static string $view = 'filament.pages.stock-report.purchase-invoice-report';


    protected function getTableFilters(): array
    {
        return [
            SelectFilter::make("store_id")
                ->label(__('lang.store'))
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Store::get()->pluck('name', 'id')),
            SelectFilter::make("supplier_id")
                ->label(__('lang.supplier'))
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Supplier::get()->pluck('name', 'id')),
            SelectFilter::make("product_id")
                ->label(__('lang.product'))
                ->searchable()
                ->multiple()
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Product::get()->pluck('name', 'id')),
        ];
    }


    protected function getViewData(): array
    {
        $store_id = __filament_request_select('store_id', 'all');
        $supplier_id = __filament_request_select('supplier_id', 'all');
        $product_id = __filament_request_select('product_id', 'all');

        $purchase_invoice_data = $this->getPurchasesInvoiceData($product_id, $store_id, $supplier_id);


        return [
            'purchase_invoice_data' => $purchase_invoice_data,
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    } 

    public function getPurchasesInvoiceData($product_id, $store_id, $supplier_id)
    {
        $store_name = 'All';
        $supplier_name = 'All';
        $query = DB::table('purchase_invoices')
            ->select(
                'purchase_invoice_details.product_id as product_id',
                'products.name as product_name',
                'units.name as unit_name',
                'purchase_invoice_details.quantity as quantity',
                'purchase_invoice_details.price as unit_price',
                'purchase_invoices.date as purchase_date',
                'users.name as supplier_name',
                'stores.name as store_name'
            )
            ->join('purchase_invoice_details', 'purchase_invoices.id', '=', 'purchase_invoice_details.purchase_invoice_id')
            ->join('products', 'purchase_invoice_details.product_id', '=', 'products.id')
            ->join('units', 'purchase_invoice_details.unit_id', '=', 'units.id')
            ->join('users', 'purchase_invoices.supplier_id', '=', 'users.id')
            ->join('stores', 'purchase_invoices.store_id', '=', 'stores.id')
            ->where('purchase_invoices.active', 1);

        if (is_numeric($store_id)) {
            $query->where('purchase_invoices.store_id', $store_id);
            $store_name = Store::find($store_id)?->name;
        }

        if (is_numeric($supplier_id)) {
            $query->where('purchase_invoices.supplier_id', $supplier_id);
            $supplier_name = Supplier::find($supplier_id)?->name;
        }

        if (is_numeric($product_id)) {
            $query->where('purchase_invoice_details.product_id', $product_id);
        }

        $results = $query->get();
        return [
            'results' => $results,
            'supplier_name' => $supplier_name,
            'store_name' => $store_name,
        ];
    }
}
