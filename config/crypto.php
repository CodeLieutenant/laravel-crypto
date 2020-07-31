<?php

return [
    'public_key' => env('PUBLIC_CRYPTO_PRIVATE_KEY', storage_path('crypto_private.key')),
    'private_key' => env('PUBLIC_CRYPTO_PUBLIC_KEY', storage_path('crypto_public.key')),
];
