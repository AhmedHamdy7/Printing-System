<?php

if (!function_exists('format_currency')) {
    function format_currency($amount, $decimals = 2)
    {
        return number_format($amount, $decimals) . ' EGP';
    }
}

if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        return 'EGP';
    }
}
