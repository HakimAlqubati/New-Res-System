<?php

namespace App\Repositories\Products;

use App\Http\Resources\ProductResource;
use App\Interfaces\Products\ProductRepositoryInterface;
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

    function reportv2($request){
        $strSelect = 'SELECT categories.id as category_id, categories.name as category_name
        , (select count(products.id) from products where categories.id = products.category_id) as product_count
         FROM categories';
        $params = null;
        $results = DB::select($strSelect);
        return $results;
    }
}
