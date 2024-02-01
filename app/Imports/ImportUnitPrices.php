<?php

namespace App\Imports;

use App\Models\UnitPrice;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToModel;

class ImportUnitPrices implements ToModel
{
    /**
     * @param Collection $collection
     */
    public function model(array $row)
    { 
        // dd($row);
        return new UnitPrice([
            'product_id' => $row[0],
            'unit_id' => $row[1],
            'price' => $row[2],
        ]);
    }
}
