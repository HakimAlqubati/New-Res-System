<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportProducts implements ToModel
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        // Create a new product instance
        $product = new Product();

        $product->name = $row[0];
        $product->active = 1;
        $product->category_id = $row[1];
        $product->code = Str::slug($row[0]);
        $product->product_code = $row[2];
        $product->description = $row[0];

        // Save the product
        $product->save();

        return $product;

    }
}
