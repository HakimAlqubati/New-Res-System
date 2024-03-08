<?php



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
