<?php

namespace App\Filament\Resources\OrderReportsResource\Pages;

use App\Filament\Resources\OrderReportsResource\GeneralReportOfProductsResource;
use App\Models\Order;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\DB;

class GeneralReportProductDetails extends Page
{
    protected static string $resource = GeneralReportOfProductsResource::class;

    public string $start_date;
    public string $end_date;
    public $category_id;
    public $branch_id;
    function __construct()
    {
        $this->start_date  = $_GET['start_date'] ?? '';
        $this->end_date = $_GET['end_date'] ?? '';
        $this->category_id = $_GET['category_id'];
        $this->branch_id = $_GET['branch_id'];
    }
    protected static string $view = 'filament.pages.order-reports.general-report-product-details';
    protected function getViewData(): array
    {
        $report_data = [];
        $report_data = $this->getReportDetails($this->start_date, $this->end_date, $this->branch_id, $this->category_id);

        return [
            'report_data' => $report_data,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'category' => \App\Models\Category::find($this->category_id)?->name,
            'branch' => \App\Models\Branch::find($this->branch_id)?->name,
        ];
    }

    protected static function getNavigationLabel(): string
    {
        return __('lang.report_details');
    }



    protected function getLayoutData(): array
    {
        return [
            'breadcrumbs' => $this->getBreadcrumbs(),
            'title' => __('lang.report_details'),
            'maxContentWidth' => $this->getMaxContentWidth(),
        ];
    }


    public function getReportDetails($start_date, $end_date, $branch_id, $category_id)
    {
        $data = DB::table('orders_details')
            ->join('orders', 'orders_details.order_id', '=', 'orders.id')
            ->join('products', 'orders_details.product_id', '=', 'products.id')
            ->join('units', 'orders_details.unit_id', '=', 'units.id')
            // ->select('products.category_id', 'orders_details.product_id as p_id' )
            ->when($branch_id, function ($query) use ($branch_id) {
                return $query->where('orders.branch_id', $branch_id);
            })
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                return $query->whereBetween('orders.created_at', [$start_date, $end_date]);
            })
            // ->when($year && $month, function ($query) use ($year, $month) {
            //     return $query->whereRaw('YEAR(orders.created_at) = ? AND MONTH(orders.created_at) = ?', [$year, $month]);
            // })
            ->whereIn('orders.status', [Order::DELEVIRED, Order::READY_FOR_DELEVIRY])
            ->where('products.category_id', $category_id)
            ->groupBy(
                'orders_details.product_id',
                'products.category_id',
                'orders_details.unit_id',
                'products.name',
                'units.name',
            )
            ->get([
                'products.category_id',
                'orders_details.product_id',
                DB::raw("IF(JSON_VALID(products.name), REPLACE(JSON_EXTRACT(products.name, '$." . app()->getLocale() . "'), '\"', ''), products.name) as product_name"),
                'units.name as unit_name',
                'orders_details.unit_id as unit_id',
                DB::raw('ROUND(SUM(orders_details.available_quantity), 0) as available_quantity'),
                DB::raw('(SUM(orders_details.price)) as price'),
            ]);
        $final_result = [];
        foreach ($data as   $val_data) {
            $obj = new \stdClass();
            $obj->category_id = $val_data->category_id;
            $obj->product_id = $val_data->product_id;
            $obj->product_name = $val_data->product_name;
            $obj->unit_name = $val_data->unit_name;
            $obj->unit_id = $val_data->unit_id;
            $obj->quantity = $val_data->available_quantity;
            $obj->price = formatMoney($val_data->price, getDefaultCurrency());
            $obj->total_price = formatMoney(($val_data->price * $val_data->available_quantity), getDefaultCurrency());
            $obj->amount = number_format($val_data->price, 2);
            $obj->symbol = getDefaultCurrency();
            $final_result[] = $obj;
        }
        return $final_result;
    }

    public function goBack()
    {
        return back();
    }
}