<?php

namespace App\Filament\Widgets;

use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\CustomChartWidget;
use Illuminate\Support\Facades\DB;

class BranchesOrdersChart extends CustomChartWidget
{
    public ?string $yearid = '0000';
    protected static ?string $heading = 'الفروع الأكثر طلباً   ';
    protected static ?string $maxHeight = '300px';
    protected static string $view = 'vendor.filament.widgets.branches-orders-chart';
    protected function getData(): array
    { 
        $yearid = $this->yearid;
        $query   = DB::table('orders_details')
            ->join('orders', 'orders_details.order_id', '=', 'orders.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->select(
                'branches.name as branch_name',
                DB::raw('round(sum(orders_details.available_quantity), 2) as quantity'),
                DB::raw('round(sum(orders_details.price), 2) as price')
            )
            ->groupBy('orders.branch_id', 'branches.name')
            ->whereIn('orders.status', [Order::DELEVIRED, Order::READY_FOR_DELEVIRY]);
        if ($yearid !== '0000') {
            $query->whereYear('orders.created_at', '=', $yearid);
        }
        $data = $query->get()->toArray();
        $branchesNamesArray = [];
        $quantitiesArray = [];
        $pricesArray = [];
        foreach ($data as $item) {
            $branchesNamesArray[] = $item->branch_name;
            $quantitiesArray[] = $item->quantity;
            $pricesArray[] = $item->price;
        }
        $labels = $branchesNamesArray;

        return [
            'datasets' => [
                [
                    'label' => 'بحسب الكميات',
                    'data' => $quantitiesArray,
                    'backgroundColor' => '#FF0000',
                ],
                [
                    'label' => 'بحسب الأسعار',
                    'data' => $pricesArray,
                    'backgroundColor' => '#00FF00',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getFilters(): ?array
    {
        return [
            'day' => 'Today',
            'week' => 'this week',
            'month' => 'this month',
            'year' => 'This year',
        ];
    }
    protected function getType(): string
    {
        return 'bar';
    }

    public function getColumnSpan(): int|string|array
    {
        return 2;
    }
}
