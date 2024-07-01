<?php

namespace App\Filament\Resources\OrderReportsResource\Pages;

use App\Filament\Resources\OrderReportsResource\GeneralReportOfProductsResource;

use App\Models\Branch;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\DatePicker;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\Layout;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Support\Facades\DB;
use niklasravnsborg\LaravelPdf\Facades\Pdf;
use Filament\Pages\Actions\Action;

class ListGeneralReportOfProducts extends ListRecords
{
    protected static string $resource = GeneralReportOfProductsResource::class;
    protected static string $view = 'filament.pages.order-reports.general-report-products';


    protected function getTableFilters(): array
    {
        return [

            SelectFilter::make("branch_id")
                ->label(__('lang.branch'))
                ->query(function (Builder $q, $data) {
                    return $q;
                })->options(Branch::where('active', 1)
                    ->get()->pluck('name', 'id')),
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
        ];
    }


    protected function getViewData(): array
    {

        $branch_id = __filament_request_select('branch_id', 'choose');
        $start_date =  __filament_request_key("date.start_date", null);
        $end_date = __filament_request_key("date.end_date", null);

        $report_data['data'] = [];
        $total_quantity = 0;
        $total_price = 0;

        $report_data  = $this->getReportData($start_date, $end_date, $branch_id);


        $start_date = (!is_null($start_date) ? date('Y-m-d', strtotime($start_date))  : __('lang.date_is_unspecified'));
        $end_date = (!is_null($end_date) ? date('Y-m-d', strtotime($end_date))  : __('lang.date_is_unspecified'));
        if (isset($report_data['total_price'])) {
            $total_price = $report_data['total_price'];
        }
        if (isset($report_data['total_quantity'])) {
            $total_quantity = $report_data['total_quantity'];
        }

        return [
            'report_data' => $report_data['data'],
            'branch_id' => $branch_id,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'total_quantity' =>  $total_quantity,
            'total_price' =>  $total_price
        ];
    }

    protected function getTableFiltersLayout(): ?string
    {
        return Layout::AboveContent;
    }


    function getReportData($start_date, $end_date, $branch_id)
    {

        $data = DB::table('orders_details')
            ->join('orders', 'orders_details.order_id', '=', 'orders.id')
            ->join('products', 'orders_details.product_id', '=', 'products.id')
            ->select(
                'products.category_id',
                DB::raw('SUM(orders_details.available_quantity) as available_quantity'),
                DB::raw('SUM(orders_details.price) as price')
            )

            ->when($branch_id, function ($query) use ($branch_id) {
                return $query->where('orders.branch_id', $branch_id);
            })
            ->when($start_date && $end_date, function ($query) use ($start_date, $end_date) {
                
                $s_d = date('Y-m-d', strtotime($start_date)) . ' 00:00:00';
                $e_d = date('Y-m-d', strtotime($end_date)) . ' 23:59:59';
                
                return $query->whereBetween('orders.created_at', [$s_d, $e_d]);
            })
            // ->when($year && $month, function ($query) use ($year, $month) {
            //     return $query->whereRaw('YEAR(orders.created_at) = ? AND MONTH(orders.created_at) = ?', [$year, $month]);
            // })
            // ->whereIn('orders.status', [Order::DELEVIRED, Order::READY_FOR_DELEVIRY])
            // ->where('orders.active', 1)
            ->whereNull('orders.deleted_at')
            ->groupBy('products.category_id')
            ->get()
            ->mapWithKeys(function ($item) {
                if (is_object($item)) {
                    return [$item->category_id => [
                        'available_quantity' => $item->available_quantity,
                        'price' => $item->price
                    ]];
                }
            })
            ->all();

        $categories = DB::table('categories')->where('active', 1)->get(['id', 'name'])->pluck('name', 'id');

        $final_result['data'] = [];
        $total_price = 0;
        $total_quantity = 0;
        foreach ($categories as $cat_id => $cat_name) {
            $obj = new \stdClass();
            $obj->category_id = $cat_id;
            $obj->url_report_details = "admin/general-report-products/details/$cat_id?start_date=$start_date&end_date=$end_date&branch_id=$branch_id&category_id=$cat_id'";
            $obj->category = $cat_name;
            $obj->quantity =  round(isset($data[$cat_id]) ? $data[$cat_id]['available_quantity'] : 0, 0);
            $price = (isset($data[$cat_id]) ? $data[$cat_id]['price'] : '0.00');
            $obj->price =  formatMoney($price, getDefaultCurrency());
            $obj->amount = number_format($price, 2);
            $obj->symbol = getDefaultCurrency();
            $total_price += $price;
            $total_quantity += $obj->quantity;
            $final_result['data'][] = $obj;
        }
        $final_result['total_price'] = number_format($total_price, 2) . ' ' . getDefaultCurrency();
        $final_result['total_quantity'] = number_format($total_quantity, 2);

        return $final_result;
    }



    protected function getActions(): array
    {
        return  [Action::make('Export to PDF')->label(__('lang.export_pdf'))
            ->action('exportToPdf')
            ->color('success'),];
    }

    public function exportToPdf()
    {

        $data = $this->getViewData();

        $data = [
            'report_data' => $data['report_data'],
            'branch_id' => $data['branch_id'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'total_quantity' => $data['total_quantity'],
            'total_price' => $data['total_price']
        ];


        $pdf = Pdf::loadView('export.reports.general-report-products', $data);

        return response()
            ->streamDownload(function () use ($pdf) {
                $pdf->stream("general-report-products" . '.pdf');
            }, "general-report-products" . '.pdf');
    }
}
