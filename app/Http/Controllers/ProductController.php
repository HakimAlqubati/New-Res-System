<?php

namespace App\Http\Controllers;

use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $products = Product::with('order_details.unit')
            ->get()
            ->groupBy('id')
            ->map(function ($item, $key) {
                $units = $item->pluck('order_details')->flatten(1)->pluck('unit.name')->unique()->toArray();
                $quantities = [];
                foreach ($units as $unit) {
                    $quantity = $item->pluck('order_details')->flatten(1)->where('unit.name', $unit)->sum('quantity');
                    $quantities[$unit] = $quantity;
                }
                $item->units = $units;
                $item->quantities = $quantities;
                return $item;
            });
        return $products; 
    }
}
