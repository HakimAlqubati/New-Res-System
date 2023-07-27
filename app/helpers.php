<?php



function getName()
{
    return 'Eng. Hakeem';
}

function formatMoney($amount, $currency = '$')
{
    return $currency .' ' . number_format($amount, 2);
}
