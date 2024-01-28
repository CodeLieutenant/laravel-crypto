<?php

return [
    'public_key_crypto' => [
        'eddsa' => [
            'key' => env('EDDSA_PUBLIC_CRYPTO_KEY', storage_path('eddsa_crypto.key')),
        ]
    ],
    'hashing' => [
        'driver' => 'blake2b'
    ],
    'signing' => [
        'driver' => 'blake2b',
    ]
];
