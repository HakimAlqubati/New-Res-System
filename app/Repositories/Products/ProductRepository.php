<?php

namespace App\Repositories\Products;

use App\Http\Resources\ProductResource;
use App\Interfaces\Products\ProductRepositoryInterface;
use App\Models\Branch;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{

    protected $model;

    public function __construct(Product $model)
    {
        $this->model = $model;
    }

    function index($request)
    {
        // Get the value of the ID and category ID filters from the request, or null if they're not set.
        $id = $request->input('id');
        $categoryId = $request->input('category_id');

        // Query the database to get all active products, or filter by ID and/or category ID if they're set.
        $products = Product::active()->when($id, function ($query) use ($id) {
            return $query->where('id', $id);
        })->when($categoryId, function ($query) use ($categoryId) {
            return $query->where('category_id', $categoryId);
        })->get();

        // Return a collection of product resources.
        return ProductResource::collection($products);
    }
    function report($request)
    {
        $from_date = $_GET['from_date'] ?? null;
        $to_date = $_GET['to_date'] ?? null;
        $month = $_GET['month'] ?? null;
        $year = $_GET['year'] ?? null;
        $branch_id = $_GET['branch_id'] ?? null;
        $strSelect = 'SELECT DISTINCT 
        products.id as product_id,
        products.name as product_name,
        orders_details.unit_id as unit_id,
        units.name as unit_name,
        COUNT(orders_details.product_id) as count,
        orders.branch_id as branch_id,
        branches.name as branch_name
        FROM 
        products 
        INNER JOIN orders_details ON (products.id = orders_details.product_id)
        INNER JOIN orders ON (orders.id = orders_details.order_id)
        inner join branches on (orders.branch_id = branches.id)
        INNER JOIN units ON (orders_details.unit_id = units.id)';
        $params = array();
        $where = array();

        $currnetRole = auth()->user()?->roles[0]?->id;
        if ($currnetRole == 7) {
            $where[] = 'orders.customer_id = ?';
            $params[] = $request->user()->id;
        }
        if ($from_date && $to_date) {
            $where[] = 'DATE(orders.created_at) BETWEEN ? AND ?';
            $params[] = $from_date;
            $params[] = $to_date;
        }

        if ($year && $month) {
            $where[] = 'YEAR(orders.created_at) = ? AND MONTH(orders.created_at) = ?';
            $params[] = $year;
            $params[] = $month;
        }

        if ($branch_id) {
            $where[] = 'orders.branch_id = ?';
            $params[] = $branch_id;
        }
        if (!empty($where)) {
            $strSelect .= ' WHERE ' . implode(' AND ', $where);
        }
        $strSelect .= ' GROUP BY 
                products.id,
                products.name,
                orders_details.unit_id,
                units.name,
                orders.branch_id,
                branches.name
                ORDER BY 
                products.id ASC';
        $results = DB::select($strSelect, $params);
        return $results;
    }

    function reportv2($request)
    {
        $branch_id = $request->input('branch_id');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $year = $request->input('year');
        $month = $request->input('month');

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
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('orders.created_at', [$from_date, $to_date]);
            })
            ->when($year && $month, function ($query) use ($year, $month) {
                return $query->whereRaw('YEAR(orders.created_at) = ? AND MONTH(orders.created_at) = ?', [$year, $month]);
            })
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

        foreach ($categories as $cat_id => $cat_name) {
            $obj = new \stdClass();
            $obj->category_id = $cat_id;
            $obj->category_name = $cat_name;
            $obj->available_quantity =  isset($data[$cat_id]) ? $data[$cat_id]['available_quantity'] : 0;
            $obj->price = isset($data[$cat_id]) ? $data[$cat_id]['price'] : 0;
            $final_result[] = $obj;
        }

        return [
            'branches' => Branch::where('active', 1)->pluck('name', 'id'),
            'data' => $final_result
        ];
    }

    public function reportv2Details($request, $category_id)
    {

        $branch_id = $request->input('branch_id');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');
        $year = $request->input('year');
        $month = $request->input('month');

        $data = DB::table('orders_details')
            ->join('orders', 'orders_details.order_id', '=', 'orders.id')
            ->join('products', 'orders_details.product_id', '=', 'products.id')
            ->join('units', 'orders_details.unit_id', '=', 'units.id')
            // ->select('products.category_id', 'orders_details.product_id as p_id' )
            ->when($branch_id, function ($query) use ($branch_id) {
                return $query->where('orders.branch_id', $branch_id);
            })
            ->when($from_date && $to_date, function ($query) use ($from_date, $to_date) {
                return $query->whereBetween('orders.created_at', [$from_date, $to_date]);
            })->when($year && $month, function ($query) use ($year, $month) {
                return $query->whereRaw('YEAR(orders.created_at) = ? AND MONTH(orders.created_at) = ?', [$year, $month]);
            })
            ->where('products.category_id', $category_id)
            ->groupBy(
                'orders_details.product_id',
                'products.category_id',
                'orders_details.unit_id',
                // 'orders_details.available_quantity',
                'products.name',
                'units.name',
    //             DB::raw('YEAR(orders.created_at)'),
    // DB::raw('MONTH(orders.created_at)')
            )
            ->get([
                'products.category_id',
                'orders_details.product_id',
                'products.name as product_name',
                'units.name as unit_name',
                'orders_details.unit_id as unit_id',
               DB::raw('SUM(orders_details.available_quantity) as available_quantity') 
            ]);
        return $data;
    }
}
