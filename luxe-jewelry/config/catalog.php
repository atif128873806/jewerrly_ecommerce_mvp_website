<?php

/**
 * Storefront branding. Catalog data (products, categories, collections,
 * reviews) now lives in the database — see the models in app/Models and
 * database/seeders/DatabaseSeeder.php.
 *
 * Change `brand` to rename the store everywhere (header, footer, titles).
 */

return [
    'brand' => env('STORE_BRAND', 'ELARA'),
];
