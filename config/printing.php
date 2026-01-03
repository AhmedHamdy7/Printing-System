<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination Settings
    |--------------------------------------------------------------------------
    |
    | Default pagination limit for listing pages
    |
    */
    'pagination_limit' => env('PAGINATION_LIMIT', 10),

    /*
    |--------------------------------------------------------------------------
    | Invoice Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to invoice generation and management
    |
    */
    'invoice' => [
        'number_prefix' => 'INV',
        'number_format' => 'INV-%d-%05d', // Format: INV-YEAR-00001
        'pdf_orientation' => 'portrait',
        'pdf_paper_size' => 'a4',
    ],

    /*
    |--------------------------------------------------------------------------
    | Report Settings
    |--------------------------------------------------------------------------
    |
    | Default date ranges for reports
    |
    */
    'reports' => [
        'default_range_days' => 30, // Default date range for reports (last 30 days)
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Settings
    |--------------------------------------------------------------------------
    |
    | Settings related to product management
    |
    */
    'products' => [
        'default_price' => 0.00,
        'price_decimal_places' => 2,
    ],
];
