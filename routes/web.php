<?php

use App\Http\Controllers\ImportController;
use App\Http\Controllers\OrderController;
use App\Models\Order;
use App\Models\OrderDetails;
use App\Models\UnitPrice;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/totestpdf', function () {
    $order = Order::find(52);
    $orderDetails = $order?->orderDetails;
    return view('export.order_pdf', compact('order', 'orderDetails'));
});
Route::get('/totest', function () {

    $units_prices = UnitPrice::get();
    foreach ($units_prices as $key => $value) {
        $product_id = $value->product_id;
        $unit_id = $value->unit_id;
        $price = $value->price;
        OrderDetails::where('product_id', $product_id)->where('unit_id', $unit_id)->update(['price' => $price]);
    }

    // return redirect(url('/admin'));
});
Route::get('/toviewrepeated', function () {
    /**
     * order IDs
     * (71,82,84,86,89,90,91,92,95,103,104,106,107,110,111,112,115)
     * 
     */
    $repeated_order_details = DB::table('orders_details')->select(
        'order_id',
        'product_id',
        'id',
        'unit_id',
        'available_quantity',
        'quantity',
        'created_by'
    )->get();
    foreach ($repeated_order_details as $key => $value) {
        $res[$value->order_id][$value->product_id][$value->unit_id][] = $value;
    }
    foreach ($res as $k => $v) {

        foreach ($v as $kk => $vv) {
            foreach ($vv as $kkk => $vvv) {
                # code...
                if (count($vvv) > 1) {
                    $ress[] = $vvv;
                }
            }
        }
    }
    // return $ress;
    foreach ($ress as $r => $rv) {
        $sum_qty = 0;
        $sum_av_qty = 0;
        foreach ($rv as $rr => $rrvv) {
            $sum_qty += $rrvv->quantity;
            $sum_av_qty += $rrvv->available_quantity;

            if ($rr == 0) {
                OrderDetails::find($rrvv->id)->delete();
            }
            if ($rr == 1) {
                OrderDetails::find($rv[1]->id)->update([
                    'quantity' => $sum_qty,
                    'available_quantity' => $sum_av_qty,
                ]);
                // $ressss[$rrvv->order_id][] = [
                //     'id' => $rrvv->id,
                //     'sum_qty' => $sum_qty,
                //     'sum_av_qty' => $sum_av_qty,
                // ];
            }
        }
    } 
    return $ress;
});
Route::get('/', function () {

    return redirect(url('/admin'));
});

Route::get('orders/export/{id}', [OrderController::class, 'export']);
Route::get('orders/export-transfer/{id}', [OrderController::class, 'exportTransfer']);


Route::get('/import_page_units', [ImportController::class, 'import_units_view']);
Route::post('/import_units', [
    ImportController::class,
    'importUnits'
])->name('import_units');

Route::get('/import_page_products', [ImportController::class, 'import_products_view']);
Route::post('/import_products', [
    ImportController::class,
    'importProducts'
])->name('import_products');

Route::get('/import_page_purchase_invoice_details', [ImportController::class, 'import_purchase_invoice_details_view']);
Route::post('/import_purchase_invoice_details', [
    ImportController::class,
    'importpurchaseInvoiceDetails'
])->name('import_purchase_invoice_details');

Route::get('/import_page_item_types', [ImportController::class, 'import_item_types_view']);
Route::post('/import_item_types', [
    ImportController::class,
    'importItemTypes'
])->name('import_item_types');

Route::get('/import_page_categories', [ImportController::class, 'import_categories_view']);
Route::post('/import_categories', [
    ImportController::class,
    'importCategories'
])->name('import_categories');

Route::get('/import_page_unit_prices', [ImportController::class, 'import_unit_prices_view']);
Route::post('/import_unit_prices', [
    ImportController::class,
    'importUnitPrices'
])->name('import_unit_prices');
