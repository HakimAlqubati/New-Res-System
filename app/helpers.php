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
