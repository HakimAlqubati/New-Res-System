<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    /**
     * get products report
     */
    public function reportProducts()
    {
        $from_date = $_GET['from_date'] ?? null;
        $to_date = $_GET['to_date'] ?? null;
        $strSelect = 'SELECT DISTINCT 
            products.id as product_id,
            products.name as product_name,
            orders_details.unit_id as unit_id,
            units.name as unit_name,
            COUNT(orders_details.product_id) as count,
            orders.branch_id as branch_id,
            branches.name as branch_name
            -- , DATE(orders.created_at) as created_date
        FROM 
            products 
            INNER JOIN orders_details ON (products.id = orders_details.product_id)
            INNER JOIN orders ON (orders.id = orders_details.order_id)
            inner join branches on (orders.branch_id = branches.id)
            INNER JOIN units ON (orders_details.unit_id = units.id)';
        $params = array();
        if ($from_date && $to_date) {
            $strSelect .= ' WHERE orders.created_at BETWEEN ? AND ?';
            $params[] = $from_date;
            $params[] = $to_date;
        } elseif ($from_date) {
            $strSelect .= ' WHERE orders.created_at >= ?';
            $params[] = $from_date;
        } elseif ($to_date) {
            $strSelect .= ' WHERE orders.created_at <= ?';
            $params[] = $to_date;
        }
        $strSelect .= ' GROUP BY 
            products.id,
            products.name,
            orders_details.unit_id,
            units.name,
            -- DATE(orders.created_at),
            orders.branch_id,
            branches.name
        ORDER BY 
            products.id ASC';
        $results = DB::select($strSelect, $params);
        return $results;
    }
}
