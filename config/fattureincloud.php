<?php

return [
    'auth_middleware' => env('FT_CLOUD_AUTH_MIDDLEWARE', 'auth:web'),
    'client_id' => env('FT_CLOUD_CLIENT'),
    'client_secret' => env('FT_CLOUD_SECRET'),
];
