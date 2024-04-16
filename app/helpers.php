<?php

use App\Models\Store;
use App\Models\User;

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
