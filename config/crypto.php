<?php

return [
    'public_key_crypto' => [
        'eddsa' => [
            'public_key' => env('EDDSA_PUBLIC_CRYPTO_PRIVATE_KEY', storage_path('eddsa_crypto_private.key')),
            'private_key' => env('EDDSA_PUBLIC_CRYPTO_PUBLIC_KEY', storage_path('eddsa_crypto_public.key')),
        ]
    ],
    'hashing' => [
        'driver' => 'blake2b'
    ],
    'signing' => [
        'driver' => 'blake2b',
    ]
];
