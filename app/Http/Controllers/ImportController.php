<?php

namespace App\Http\Controllers;

use App\Imports\ImportProducts;
use App\Imports\ImportUnitPrices;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
class ImportController extends Controller
{
    public function import_products_view()
    {
        return view('import_products');
    }


    public function import_unit_prices_view()
    {
        return view('import_unit_prices');
    }

    public function importProducts(Request $request)
    {
         
        try {
            Excel::import(new ImportProducts, $request->file('file')->store('files'));
        } catch (\Exception $e) {
            // Print or log the exception
            echo "Exception: " . $e->getMessage();
        }

        return redirect()->back();
    }

    public function importUnitPrices(Request $request)
    {
         
        try {
            Excel::import(new ImportUnitPrices, $request->file('file')->store('files'));
        } catch (\Exception $e) {
            // Print or log the exception
            echo "Exception: " . $e->getMessage();
        }

        return redirect()->back();
    }
}
