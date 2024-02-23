<?php

declare(strict_types=1);

use BrosSquad\LaravelCrypto\Encoder\JsonEncoder;
use BrosSquad\LaravelCrypto\Encoder\PhpEncoder;
use BrosSquad\LaravelCrypto\Hashing\Blake2b as Blake2bHash;
use BrosSquad\LaravelCrypto\Signing\Hmac\HmacBlake2b as Blake2bHMAC;

return [
    /*
    |--------------------------------------------------------------------------
    | Crypto Encoder
    |--------------------------------------------------------------------------
    |
    | This option controls the default encoder that will be used to encode
    | and decode data thought the library. Can be any implementing class
    | of `Encoder` interface. Use `config` to pass any configuration
    | to the underlying encoder. There is no need to register encoder
    | in the service provider, it will be resolved automatically
    | if only everything that class requires is in `config`.
    |
    */

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

    /*
    |--------------------------------------------------------------------------
    | Hashing
    |--------------------------------------------------------------------------
    |
    | This option controls the default hashing algorithm that will be used
    | to hash data. Can be any implementing class of `Hashing` interface.
    | Use `config` to pass any configuration to the underlying hashing.
    |
    | In `blake2b` case, you can pass `key` and `outputLength` to the config.
    | `key` is used to have unique hash for your application even if the data
    | is the same. There is no difference between `HMAC` version and `Hash`
    | version of the algorithm when `key` is used.
    |
    */
    'hashing' => [
        'driver' => Blake2bHash::class,
        'config' => [
            Blake2bHash::class => [
                'key' => env('CRYPTO_BLAKE2B_HASHING_KEY'),
                'outputLength' => 32,
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Crypto Signing
    |--------------------------------------------------------------------------
    |
    | Used to crypto sign the data. Can be any implementing class of `Signer`
    | interface.
    | For the signer there is implementation with asymmetric and symmetric
    | MAC algorithm. For the asymmetric algorithm, you can use `EdDSA`
    | and for the symmetric MAC algorithm, you can use `Blake2b`, `Sha256` and `Sha512`.
    |
    | `Sha256` uses `sha512/256` implemented in `libsodium`.
    |
    */
    'signing' => [
        'driver' => Blake2bHMAC::class,
        'keys' => [
            'eddsa' => env('CRYPTO_EDDSA_PUBLIC_CRYPTO_KEY', storage_path('keys/eddsa.key')),
            'hmac' => env('CRYPTO_HMAC_KEY'),
        ],
    ],
];
