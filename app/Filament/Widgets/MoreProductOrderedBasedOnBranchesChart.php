<?php

namespace App\Filament\Widgets;

use App\Models\Branch;
use App\Models\Order;
use Filament\Widgets\ChartWidget;
use Filament\Widgets\CustomChartWidget;
use Illuminate\Support\Facades\DB;

class MoreProductOrderedBasedOnBranchesChart extends CustomChartWidget
{
    public ?string $yearid = '0000';
    public ?string $month = '0';
    public ?string $branchid = '0';
    public ?int $productscount = 15;

    public $branches;
    protected static ?string $heading = 'أكثر المنتجات المطلوبة';
    protected static ?string $maxHeight = '300px';
    protected static string $view = 'vendor.filament.widgets.more-product-ordered-based-on-branches';

    public function  __construct()
    {
        $this->branches = $this->getBranches();
    }
    protected function getData(): array
    {

        $yearid = $this->yearid;
        $month = $this->month;
        $branch_id = $this->branchid;

        $query = DB::table('orders_details')
            ->join('products', 'orders_details.product_id', '=', 'products.id')
            ->join('orders', 'orders_details.order_id', '=', 'orders.id')
            ->join('branches', 'orders.branch_id', '=', 'branches.id')
            ->select(
                'orders_details.product_id',
                'products.name as product_name',
                DB::raw('ROUND(SUM(orders_details.available_quantity), 2) as quantity'),
            )
            ->groupBy(
                'orders_details.product_id',
                'products.name',
            )
            ->orderByDesc(DB::raw('sum(orders_details.available_quantity)'))
            ->limit($this->productscount);
        if ($yearid !== '0000') {
            $query->whereYear('orders.created_at', '=', $yearid);
        }
        if ($month !== '0') {
            $query->whereMonth('orders.created_at', '=', $month);
        }
        if ($branch_id !== '0') {
            $query->where('orders.branch_id', $branch_id);
        }

        $data = $query->get();

        $productsNamesArray = [];
        $quantitiesArray = [];
        foreach ($data as $item) {
            $productsNamesArray[] = $item->product_name;
            $quantitiesArray[] = $item->quantity;
        }
        $labels = $productsNamesArray;

        return [
            'datasets' => [
                [
                    'label' => 'الكمية المطلوبة (مجمعة)',
                    'data' => $quantitiesArray,
                    'backgroundColor' => '#0000FF',
                ],
            ],
            'labels' => $labels,
        ];
    }

    protected function getBranches()
    {
        $branches = Branch::pluck('name', 'id')->prepend('الكل', 0);
        return $branches;
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
