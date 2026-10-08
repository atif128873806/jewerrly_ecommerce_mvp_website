<?php

/**
 * Store-wide commerce settings (currency, shipping).
 *
 * Amounts are stored as integers in the catalog. With a 0-decimal currency
 * like PKR the integer is the whole-rupee value (e.g. 10200 => Rs 10,200);
 * with a 2-decimal currency it is minor units (e.g. 10200 => €102.00).
 */

return [
    'currency' => [
        'code' => env('STORE_CURRENCY_CODE', 'PKR'),
        'symbol' => env('STORE_CURRENCY_SYMBOL', 'Rs'),
        'decimals' => (int) env('STORE_CURRENCY_DECIMALS', 0),
    ],

    // Free shipping at or above this amount; flat fee below it.
    'free_shipping_threshold' => (int) env('STORE_FREE_SHIPPING', 15000),
    'shipping_fee' => (int) env('STORE_SHIPPING_FEE', 695),
];
