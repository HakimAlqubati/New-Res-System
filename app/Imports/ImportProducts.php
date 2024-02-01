<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportProducts implements ToModel
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    {
        // dd($row);
        return new Product([
            'name' => $row[0].'-'. $row[1],
            'code' => $row[3],
            'description' => $row[4],
            'active' => $row[6],
            'category_id' => $row[2], 
        ]);
    }
}
