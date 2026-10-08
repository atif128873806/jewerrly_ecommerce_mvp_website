<?php

declare(strict_types=1);

if (! function_exists('money')) {
    /**
     * Format an integer catalog amount as the store's currency.
     *
     * Honors config('store.currency') so the whole storefront and admin can
     * switch currency in one place (e.g. PKR "Rs 10,200" or EUR "€102.00").
     */
    function money(int|float|null $amount): string
    {
        $currency = config('store.currency');
        $decimals = (int) ($currency['decimals'] ?? 0);
        $symbol = $currency['symbol'] ?? '';

        $value = (float) ($amount ?? 0) / (10 ** $decimals);

        return trim($symbol . ' ' . number_format($value, $decimals));
    }
}
