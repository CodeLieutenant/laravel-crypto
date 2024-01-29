<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Encoder\JsonEncoder;
use BrosSquad\LaravelCrypto\Encoder\PhpEncoder;
use BrosSquad\LaravelCrypto\Hashing\Blake2b as Blake2bHash;
use BrosSquad\LaravelCrypto\Hashing\Sha256 as Sha256Hash;
use BrosSquad\LaravelCrypto\Hashing\Sha512 as Sha512Hash;
use BrosSquad\LaravelCrypto\Signing\Hmac\Blake2b as Blake2bHMAC;

return [
    'encoder' => [
        'driver' => PhpEncoder::class,
        'config' => [
            PhpEncoder::class => [
                'allowed_classes' => true,
            ],
            JsonEncoder::class => [
                'decode_as_array' => true,
            ]
        ],
    ],
    'hashing' => [
        'driver' => Blake2bHash::class,
        'config' => [
            Blake2bHash::ALGORITHM => [
                'key' => env('CRYPTO_BLAKE2B_HASHING_KEY'),
            ],
            Sha512Hash::ALGORITHM => [],
            Sha256Hash::ALGORITHM => [],
        ],
    ],
    'signing' => [
        'driver' => Blake2bHMAC::class,
        'keys' => [
            'eddsa' => env('EDDSA_PUBLIC_CRYPTO_KEY', storage_path('keys/eddsa.key'))
        ],
    ],
];
