<?php

use App\Models\Order;
use App\Models\PurchaseInvoiceDetail;
use App\Models\Store;
use App\Models\SystemSetting;
use App\Models\UnitPrice;
use App\Models\User;
use Illuminate\Support\Facades\DB;

function getName()
{
    return 'Eng. Hakeem';
}

/**
 * to format money
 */
function formatMoney($amount, $currency = '$')
{
    return $currency . ' ' . number_format($amount, 2);
}

/**
 * to return current role
 */
function getCurrentRole()
{
    return auth()->user()?->roles[0]?->id;
}

/**
 * to get branch id
 */
function getBranchId()
{
    return auth()->user()?->branch?->id;
}

/**
 * to add filament request select 
 */
function __filament_request_select($key, $default = null)
{
    if (request()->isMethod('post')) {
        $qu = request()->all();
        $data = data_get($qu, "serverMemo.data.tableFilters." . $key . ".value");

        if (data_get($qu, "updates.0.payload.name") == "tableFilters.$key.value") {
            $data = data_get($qu, "updates.0.payload.value", $data);
        }

        if (data_get($qu, "updates.0.payload.params.0") == "tableFilters.$key.value") {
            $data = data_get($qu, "updates.0.payload.params.1", $data);
        }

        if (is_array($data)) {
            return $default;
        }

        return $data ?? $default;
    } else {
        $qu = request()->query();
        $data = data_get($qu, "tableFilters." . $key . ".value", $default);
        if (is_array($data)) {
            return $default;
        }
        return $data;
    }
}

function __filament_request_select_multiple($key, $default = null, $multiple = false, $type = null)
{
    if (!empty($type)) {
        $valueType = $type;
        $multiple = true;
    } else {
        $valueType = $multiple ? 'values' : 'value';
    }

    if (request()->isMethod('post')) {
        $qu = request()->all();
        $data = data_get_recursive($qu, "serverMemo.data.tableFilters." . $key . ".$valueType");

        if (data_get_recursive($qu, "updates.0.payload.name") == "tableFilters.$key.$valueType") {
            $data = data_get_recursive($qu, "updates.0.payload.$valueType", $data);
        }

        if (data_get_recursive($qu, "updates.0.payload.params.0") == "tableFilters.$key.$valueType") {
            $data = data_get_recursive($qu, "updates.0.payload.params.1", $data);
        }

        if ($multiple) {
            return is_array($data) ? $data : $default;
        }

        return $data ?? $default;
    } else {
        $qu = request()->query();
        $data = data_get($qu, "tableFilters." . $key . ".$valueType", $default);
        if (is_array($data) && !$multiple) {
            return $default;
        }
        return $data;
    }
}

function data_get_recursive($target, $key, $default = null)
{
    if (is_null($key)) {
        return $target;
    }

    $key = is_array($key) ? $key : explode('.', $key);

    foreach ($key as $i => $segment) {
        unset($key[$i]);

        if (is_null($segment)) {
            return $target;
        }

        if ($segment === '*') {
            if ($target instanceof Collection) {
                $target = $target->all();
            } elseif (!is_array($target)) {
                return value($default);
            }

            $result = [];

            foreach ($target as $item) {
                $value = data_get_recursive($item, $key);

                if (is_array($value)) {
                    $result = array_merge($result, $value);
                } else {
                    $result[] = $value;
                }
            }

            return in_array('*', $key) ? Arr::collapse($result) : $result;
        }

        if (Arr::accessible($target) && Arr::exists($target, $segment)) {
            $target = $target[$segment];
        } elseif (is_object($target) && isset($target->{$segment})) {
            $target = $target->{$segment};
        } else {
            return value($default);
        }
    }

    return $target;
}


/**
 * to add filament request date filter
 */
function __filament_request_key($key, $default = null)
{
    if (request()->isMethod('post')) {
        $qu = request()->all();
        $data = data_get($qu, "serverMemo.data.tableFilters." . $key);

        if (data_get($qu, "updates.0.payload.params.0") == "tableFilters.$key") {
            $data = data_get($qu, "updates.0.payload.params.1", $data);
        }

        if (is_array($data)) {
            return $default;
        }

        return $data ?? $default;
    } else {
        $qu = request()->query();
        $data = data_get($qu, "tableFilters." . $key, $default);

        if (is_array($data)) {
            return $default;
        }
        return $data;
    }
}

/**
 * get admins to notify [Super admin - Manager] roles
 */

function getAdminsToNotify()
{
    $adminIds = [];
    $adminIds =  User::whereHas("roles", function ($q) {
        $q->whereIn("id", [1, 3]);
    })->select('id', 'name')->get()->pluck('id')->toArray();
    $recipients = User::whereIn('id', $adminIds)->get(['id', 'name']);
    return $recipients;
}

/**
 * get default store
 */
function getDefaultStore()
{
    $defaultStoreId = Store::where('default_store', 1)->where('active', 1)->select('id')->first()?->id;
    if (is_null($defaultStoreId)) {
        $defaultStoreId = 0;
    }
    return $defaultStoreId;
}

/**
 * to get default currency
 */
function getDefaultCurrency()
{
    $defaultCurrency = 'RM';
    $systemSettingsCurrency = SystemSetting::select('currency_symbol')->first();
    if ($systemSettingsCurrency) {
        $defaultCurrency = $systemSettingsCurrency->currency_symbol;
    }
    return $defaultCurrency;
}

/**
 * to get method of calculating prices of orders
 */
function getCalculatingPriceOfOrdersMethod()
{
    $defaultMethod = 'from_unit_prices';
    $systemSettingsCalculatingMethod = SystemSetting::select('calculating_orders_price_method')->first();
    if ($systemSettingsCalculatingMethod && ($systemSettingsCalculatingMethod->calculating_orders_price_method != $defaultMethod)) {
        $defaultMethod = $systemSettingsCalculatingMethod->calculating_orders_price_method;
    }
    return $defaultMethod;
}

/**
 * get price from unit price by product_id & unit_id
 */
function getUnitPrice($product_id, $unit_id)
{
    return  UnitPrice::where(
        'product_id',
        $product_id
    )->where('unit_id', $unit_id)->first()->price;
}

/**
 * get remaning quantities from orders - purchases invoices
 */
function getPurchaseInvoicesRemaningQuantities($product_id, $unit_id)
{
}

/**
 * get sum qty of specific product with (product_id,unit_id) from purchase invoices
 */
function getSumQtyOfProductFromPurchases($product_id, $unit_id)
{

    $result = DB::table('purchase_invoice_details')
        ->select(
            DB::raw('SUM(quantity) as total_quantity'),
            'price',
            'purchase_invoice_id'
        )
        ->join(
            'purchase_invoices',
            'purchase_invoice_details.purchase_invoice_id',
            '=',
            'purchase_invoices.id'
        )
        ->where('product_id', $product_id)
        ->where('purchase_invoices.active', 1)
        ->where('unit_id', $unit_id)
        ->groupBy('price', 'purchase_invoice_id')
        ->orderBy('purchase_invoice_id', 'asc')
        ->get();
    return $result;
}

/**
 * get sum qty of specific product with (product_id,unit_id) from orders
 */
function getSumQtyOfProductFromOrders($product_id, $unit_id, $purchase_invoic_id)
{

    $orderdQty = DB::table('orders_details')
        ->join('orders', 'orders_details.order_id', '=', 'orders.id')
        ->whereIn('orders.status', [Order::DELEVIRED, Order::READY_FOR_DELEVIRY])
        ->where('orders_details.product_id', $product_id)
        ->where('orders_details.unit_id', $unit_id)
        ->where('orders_details.purchase_invoice_id', $purchase_invoic_id)
        ->select(DB::raw('SUM(available_quantity) as total_available_quantity'))
        ->first()->total_available_quantity;
    if ($orderdQty == null) {
        $orderdQty = 0;
    }
    return $orderdQty;
}



/**
 * to compare purchased quantities with orderd quantities
 */
function comparePurchasedWithOrderdQties($product_id, $unit_id)
{

    $data = getSumQtyOfProductFromPurchases($product_id, $unit_id);
    foreach ($data as   $value) {
        $purchased_qty = $value->total_quantity;
        $price = $value->price;
        $purchase_invoice_id = $value->purchase_invoice_id;
        $ordersQty = getSumQtyOfProductFromOrders($product_id, $unit_id, $purchase_invoice_id);
        if ($purchased_qty > $ordersQty) {
            $fdata[] = [
                'purchased_qty' => $purchased_qty,
                'price' => $price,
                'purchase_invoice_id' => $purchase_invoice_id,
                'orderd_qty' => $ordersQty,
                'remaning_qty' => $purchased_qty - $ordersQty,
            ];
        }
    }
    return $fdata;
}

/**
 * calculate using fifo
 */
function calculateFifoMethod($product_id, $unit_id, $already_ordered_qty, $orderId)
{
    $comparedData =  comparePurchasedWithOrderdQties($product_id, $unit_id);

    $orderDetailsData = [];
    foreach ($comparedData as $key => $value) {
        $price = $value['price'];
        $purchase_invoice_id = $value['purchase_invoice_id'];
        $remaning_qty = $value['remaning_qty'];

        if ($already_ordered_qty <= $remaning_qty) {
            $orderDetailsData = [
                'order_id' => $orderId,
                'product_id' => $product_id,
                'unit_id' => $unit_id,
                'quantity' => $already_ordered_qty,
                'available_quantity' => $already_ordered_qty,
                'created_by' => auth()?->user()?->id,
                'purchase_invoice_id' => $purchase_invoice_id,
                'price' =>  $price
            ];
            break;
        } else if ($already_ordered_qty > $remaning_qty) {
            $orderDetailsData = calculateIfAlreadyQtyBiggerThanRemaning($comparedData, $already_ordered_qty, $product_id, $unit_id);
        }
    }
    return $orderDetailsData;
}

/**
 * function does that 
 * calculate the price if already ordered quantity is bigger than remaning quantity
 */
function calculateIfAlreadyQtyBiggerThanRemaning($comparedData, $already_ordered_qty, $product_id, $unit_id)
{
    $orderDetailsData = [];
    for ($i = 0; $i < count($comparedData); $i++) {


        if ($already_ordered_qty > $comparedData[$i]['remaning_qty']) {
            $qty = ($comparedData[$i]['purchased_qty'] - $comparedData[$i]['orderd_qty']);
        } else if ($already_ordered_qty <= $comparedData[$i]['remaning_qty']) {
            $qty = $already_ordered_qty;
        }
        $orderDetailsData[] = [
            'order_id' => 0,
            'product_id' => $product_id,
            'unit_id' => $unit_id,
            'quantity' => $qty,
            'available_quantity' => $qty,
            'created_by' => auth()?->user()?->id,
            'purchase_invoice_id' => $comparedData[$i]['purchase_invoice_id'],
            'price' => $comparedData[$i]['price']
        ];
        $already_ordered_qty = ($already_ordered_qty - $qty);

        if ($already_ordered_qty > 0) {
            continue;
        } else {
            break;
        }
    }
    return $orderDetailsData;
}
