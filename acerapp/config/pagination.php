<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Pagination Per Page
    |--------------------------------------------------------------------------
    |
    | This value determines the number of items to display per page
    | in paginated listings across the application.
    |
    */

    'per_page' => env('PAGINATION_PER_PAGE', 15),

    /*
    |--------------------------------------------------------------------------
    | Admin Pagination Per Page
    |--------------------------------------------------------------------------
    |
    | This value determines the number of items to display per page
    | in admin panel listings.
    |
    */

    'admin_per_page' => env('ADMIN_PAGINATION_PER_PAGE', 15),

    /*
    |--------------------------------------------------------------------------
    | Public Pagination Per Page
    |--------------------------------------------------------------------------
    |
    | This value determines the number of items to display per page
    | in public-facing listings.
    |
    */

    'public_per_page' => env('PUBLIC_PAGINATION_PER_PAGE', 15),
];
